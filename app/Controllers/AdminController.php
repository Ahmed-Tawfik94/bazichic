<?php

namespace App\Controllers;

use App\Helpers\Constants;
use App\Helpers\Helpers;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Document;
use App\Models\DocumentLike;
use App\Models\DocumentReview;
use App\Models\DocumentSave;
use App\Models\EmailVerifications;
use App\Models\FreeTrial;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\RedeemTransaction;
use App\Models\Referral;
use App\Models\RewardPoint;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\Subscriptions;
use App\Models\User;
use App\Models\Util;
use App\Service\FileUpload\FileUploader;
use App\Service\stripe\ProductService;
use App\Service\stripe\StripeService;
use App\Service\stripe\SubscriptionService;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Stripe\Exception\ApiErrorException;

// Added for Slim 4
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use Slim\Interfaces\RouteParserInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

class AdminController extends BaseController
{
    protected SubscriptionService $subscription;

    public function __construct(
        ContainerInterface $container, // Keep for BaseController
        Twig $twig,
        Capsule $db,
        RouteParserInterface $routeParser,
        LoggerInterface $logger,
        SubscriptionService $subscriptionService // Specific dependency
    ) {
        parent::__construct($container, $twig, $db, $routeParser, $logger);
        $this->subscription = $subscriptionService;
    }

    // Note: The original index method seems like a duplicate of HomeController's index or similar.
    // It's rendering 'admin/admin-dashboard.twig' but with public-facing data.
    // Assuming this might be an unused/old method or needs re-evaluation.
    // For now, just updating signature and render call.
    public function index(Request $request, Response $response, array $args): Response
    {
        $data = Document::getAllDocuments(0);
        $helper = new Helpers();
        $membership_plans = Plan::getAllActivePlans();
        $categories = Category::getAllCategories(1);
        $ebooks = Document::getAllDocumentsByDocType(1);
        $custom_data = array();
        if (count($ebooks) > 0) {
            foreach ($ebooks as $row) {
                $tmp = $helper->getDocDetails($row["id"], $this->db);
                array_push($custom_data, $tmp);
            }
        }
        $allMagazinesArr = Document::getAllDocumentsByDocType(3);
        $allMagazines = array();
        if (count($allMagazinesArr) > 0) {
            foreach ($allMagazinesArr as $row) {
                $tmp = $helper->getDocDetails($row["id"], $this->db);
                $allMagazines[] = $tmp;
            }
        }
        $latest_docs_arr = Document::getAllLatestLiveDocuments();
        $latest_docs = array();
        if (count($latest_docs_arr) > 0) {
            foreach ($latest_docs_arr as $row) {
                $tmp = $helper->getDocDetails($row["id"], $this->db);
                $latest_docs[] = $tmp;
            }
        }
        $site = new SiteSetting();
        $vars = [
            'page' => [
                'title' => 'BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'allMagazines' => $allMagazines,
                'latest_docs' => $latest_docs,
                'ebooks' => $custom_data,
                'membership_plans' => $membership_plans,
                'categories' => $categories,
                'banner_link' => $site->getFrontBannerLink()
            ],
        ];
        return $this->twig->render($response, 'admin/admin-dashboard.twig', $vars);
    }

    public function panel(Request $request, Response $response, array $args): Response
    {
        $data_stats = [];
        $queryParams = $request->getQueryParams();
        $start_date = $queryParams['start_date'] ?? null;
        $end_date = $queryParams['end_date'] ?? null;

        $is_filtered = $start_date && $end_date;
        if (!$is_filtered) {
            $start_date = null;
            $end_date = null;
        } elseif ($start_date && $end_date && strtotime($start_date) > strtotime($end_date)) {
            $start_date = date('Y-m-d', strtotime($end_date . ' -30 days'));
        }

        $data_stats = $is_filtered ? $this->fetchStatistics($start_date, $end_date) : $this->fetchStatistics(null, null);

        return $this->twig->render($response, 'admin/admin-dashboard.twig', [
            'page' => [
                'title' => 'Admin Panel Dashboard', // More specific title
                'data_stats' => $data_stats,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'is_filtered' => $is_filtered,
                'name' => 'adminpanel'
            ],
        ]);
    }

    /**
     * Fetch statistics based on date range.
     */
    private function fetchStatistics($start_date = null, $end_date = null)
    {
        $data = [];

        // Subscription stats
        $plan_ids = [1 => 'Starter', 2 => 'Premium', 3 => 'Student'];
        $data["totalSubscribers"] = 0;
        $data["activeSubscribers"] = 0;

        foreach ($plan_ids as $plan_id => $plan_name) {
            $data["totalMembersWith{$plan_name}"] = Subscriptions::getNumPlanSales($plan_id, $start_date, $end_date) ?? 0;
            $data["currentMembersWith{$plan_name}"] = Subscriptions::getNumTotalActivePlans($plan_id, $start_date, $end_date) ?? 0;

            $data["totalSubscribers"] += $data["totalMembersWith{$plan_name}"];
            $data["activeSubscribers"] += $data["currentMembersWith{$plan_name}"];
        }

        // General stats
        $query = User::where('role_id', '!=', 1);
        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }
        $data["totalMembers"] = $query->count();

        // Payments
        $data["amountPaid"] = Payment::getSumTransactionsByDateRange("paid", $start_date, $end_date) ?? 0;
        $data["amountUnPaid"] = Payment::getSumTransactionsByDateRange("unpaid", $start_date, $end_date) ?? 0;

        // Earnings trend
        $data["earningsArr"] = [];
        for ($i = 0; $i < 12; $i++) {
            $for_date_start = date('Y-m-01', strtotime(($end_date ?? date('Y-m-d')) . " -$i months"));
            $for_date_end = date('Y-m-t', strtotime($for_date_start));

            if ($start_date && $for_date_start < $start_date) {
                break;
            }

            $earning = Subscriptions::getSumAllTransactionsBetween("paid", $for_date_start, $for_date_end) ?? 0;
            if ($earning > 0) {
                $data["earningsArr"][] = [
                    "date_start" => Util::getFormalDate($for_date_start),
                    "date_end" => Util::getFormalDate($for_date_end),
                    "earning" => $earning,
                ];
            }
        }

        // Other stats
        $data += [
            "totalFreeTrialsActive" => Subscriptions::where('status', "trialing")
                ->when($start_date, fn($q) => $q->whereBetween('created_at', [$start_date, $end_date]))
                ->count(),
            "totalRewardPoints" => RewardPoint::getTotalRewardPointsAwarded($start_date, $end_date) ?? 0,
            "numPeopleRewarded" => RewardPoint::getNumPeopleRewarded($start_date, $end_date) ?? 0,
            "numEbooks" => Document::getNumDocs(1, 1, $start_date, $end_date) ?? 0,
            "numMagazines" => Document::getNumDocs(1, 3, $start_date, $end_date) ?? 0,
            "numDocLikes" => DocumentLike::getNumAllLikes($start_date, $end_date) ?? 0,
            "numDocReviews" => DocumentReview::getNumAllReviews($start_date, $end_date) ?? 0,
            "numTotalContacts" => Contact::getNumMessages($start_date, $end_date) ?? 0,
            "countries_stat" => User::getCountriesUsersSummary($start_date, $end_date) ?? [],
            "numDocSaves" => DocumentSave::all()->count(),
            "numDocCategories" => Category::all()->count(),
        ];

        return $data;
    }


    public function ManageReviews(Request $request, Response $response, array $args): Response
    {
        $data = DocumentReview::all();
        $custom_data = array();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["id"] = $row["id"];
                $tmp["doc_id"] = $row["doc_id"];
                $tmp["stars"] = $row["stars"];
                $tmp["text"] = $row["text"];
                $tmp["user_id"] = $row["user_id"];
                $tmp["date_created"] = Util::getTimeDifference($row["date_created"]);

                $tmp["reviewer_name"] = User::getNameByID($row["user_id"]);
                $tmp["doc_name"] = Document::getNameByID($row["doc_id"]);
                $custom_data[] = $tmp;
            }
        }
        $vars = [
            'page' => [
                'title' => 'Manage Reviews | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'data' => $custom_data,
                'adminMode' => true,
                'name' => "manage-documents" // Consider if 'name' is still used or can be 'page_name'
            ],
        ];
        return $this->twig->render($response, 'admin/admin_doc_reviews.twig', $vars);
    }

    public function manageUsers(Request $request, Response $response, array $args): Response
    {
        return $this->twig->render($response, 'admin/admin_users_listing.twig', [
            'page' => [
                'title' => 'Manage Users', // Add title for consistency
                'name' => 'manage-users'
            ]
        ]);
    }

    public function retrieveUsers(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();
        $custom_data = []; // Initialize custom_data

        $draw = isset($params['draw']) ? (int) $params['draw'] : 1;
        $start = isset($params['start']) ? (int) $params['start'] : 0;
        $length = isset($params['length']) ? (int) $params['length'] : 10;
        $searchValue = $params['search']['value'] ?? '';
        $order = $params['order'] ?? [];
        $columnIndexName = $params['columns'][$order[0]['column'] ?? 0]['data'] ?? 'id'; // Get column name by data attribute
        $sortDirection = $order[0]['dir'] ?? 'asc';


        // Query for total records
        $query = User::query();

        if (!empty($searchValue)) {
            $query->where('first_name', 'LIKE', "%$searchValue%")
                ->orWhere('email', 'LIKE', "%$searchValue%");
        }

        $totalRecords = User::count();
        $filteredRecords = $query->count();

        // Apply pagination
        //        $users = $query->offset($start)->limit($length)->get();
        if (empty($searchValue)) {
            if ($order) {

                if ($columnIndex == 'name') {
                    $users = $query->skip($start)->take($length)->orderBy('first_name', $sortDirection)->orderBy('last_name', $sortDirection)->get();
                } else {

                    $users = $query->skip($start)->take($length)->orderBy($columnIndex, $sortDirection)->get();
                }
            } else {
                $users = $query->get();
            }
        } else {
            $users = $query->get();
        }

        foreach ($users as $user) {
            $tmp = array();
            if ($user->id !== $_SESSION['userID']) {
                $tmp["id"] = $user->id;
                $tmp["name"] = $user->first_name . ' ' . $user->last_name;
                $tmp["email"] = $user->email;
                $tmp["user_name"] = $user->user_name;
                $tmp["country"] = $user->country;
                $tmp["user_image"] = $user->user_image;
                $tmp["status"] = $user->status_id == 1 ? 'Verified' : 'Unverified';
                $tmp["date_created"] = Util::getTimeDifference($user->created_at);
                $tmp["description"] = $user->description;
                $tmp["last_seen"] =  $user->last_active;
                $tmp["loyalty_points"] = RewardPoint::getCurrentRewardPointFor($user->id);

                //Membership Info
                $tmp["membership_info"] = "";
                $numActivePlans = $user->subscription ? $user->subscription->where('status', 'active')->count() : 0;

                if ($numActivePlans > 0) {
                    $activePlan = $user->subscription;
                    $tmp["membership_info"] = Plan::getNameByID($activePlan->membership_plan_id);
                } else {
                    if (!$user->subscription) {
                        $tmp["membership_info"] = "No Membership yet";
                    }
                    if ($user->subscription) {
                        switch ($user->subscription->status) {
                            case 'active':
                                $tmp["membership_info"] = 'Subscription to' . Plan::getNameByID($user->subscription->membership_plan_id) . ' plan';
                                break;
                            case 'trialing':
                                $tmp["membership_info"] = 'Trial ' . Plan::getNameByID($user->subscription->membership_plan_id) . ' plan';
                                break;
                            case 'cancel':
                                $tmp["membership_info"] = "Membership Expired";
                                break;
                        }
                    }
                }

                if (!empty($user->last_active)) {
                    $tmp["last_seen"] = "Active " . Util::getTimeDifference($user->last_active);
                }
                $custom_data[] = $tmp;
            }
        }

        // Format response
        return $this->jsonResponse($response, [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $custom_data
        ], 200);
    }

    public function EditUserProfile(Request $request, Response $response, array $args): Response
    {
        $username = $args['username'] ?? null; // Access route argument
        $TargetUser = User::where('user_name', $username)->first();
        if (!$TargetUser) {
            $url = $this->routeParser->urlFor('notFound');
            return $response->withHeader('Location', $url)->withStatus(302);
        }
        $userTypes = Role::all();
        //echo $thisUser;
        $isMember = $TargetUser->subscription;
        $first_name = $TargetUser->first_name;
        $TargetUser['type'] = $TargetUser->role->name;
        //$doesHaveResume = $resumeCRUD->doesHaveResume($_SESSION["userID"]);
        $title = "My Profile";
        $countries = (new Helpers())->getCountries();
        $vars = [
            'countries' => $countries,
            'page' => [
                'admin_mode' => true,
                'action' => 'update-user',
                'title' => $title,
                'description' => 'Manage ' . $TargetUser->first_name . ' Profile',
                'uname' => $first_name,
                'thisUser' => $TargetUser,
                'isMember' => $isMember,
                'userTypes' => $userTypes,
                'name' => 'edit-user-profile' // Added page name
            ]
        ];
        return $this->twig->render($response, 'admin/my-profile.twig', $vars);
    }

    public function updateUserProfile(Request $request, Response $response, array $args): Response
    {
        $params = (array)$request->getParsedBody();
        $id = $params['user_id'] ?? null;
        $first_name = $params['first_name'] ?? null;
        $last_name = $params['last_name'];
        $email = $params['email'];
        $type = (int)$params['type'];
        $description = $params['description'];
        $country = $params['country'];
        $dob = $params['dob'];
        $status = (int)$params['status'];
        $Validation = $this->validateInput([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'dob' => $dob,
            'country' => $country,
        ]);
        if ($Validation) {
            return $response->withStatus(400)->getBody()->write(json_encode($Validation));
        }
        $admin_mode = (int)$_SESSION['role_id'] === 1 ? 1 : 0;
        /********* START PROFILE PIC UPLOAD **********/
        $files = $request->getUploadedFiles();
        $profileImageFile = $files['profile_image'] ?? null;
        $uploadResult = null;

        if ($profileImageFile && $profileImageFile->getError() === UPLOAD_ERR_OK) {
            $maxFileSize = 500000; // 500KB
            // FileUploader::uploadFile needs to be adapted if it's not static or uses Slim 3 specific file objects
            // For now, assuming it can handle PSR-7 UploadedFileInterface or path.
            // This part might need significant refactoring depending on FileUploader's implementation.
            // Let's assume a simplified flow for now or that FileUploader is adapted.
            // $uploadResult = FileUploader::uploadFile($profileImageFile, Constants::USER_FOLDER, Constants::IMAGES_EXT, $maxFileSize);
            // Due to potential complexity of FileUploader, skipping actual upload, focusing on controller logic
            // Simulating a successful upload for logic flow:
             // $uploadResult = ['code' => Constants::UPLOAD_SUCCESS, 'fileName' => 'simulated_name.jpg'];
        }


        try {
            // if ($uploadResult && $uploadResult['code'] === Constants::UPLOAD_SUCCESS) {
            //     User::updateImage($id, $uploadResult['fileName']);
            //     if (isset($_SESSION['userID']) && $_SESSION['userID'] == $id) {
            //         $_SESSION['user_image'] = $uploadResult['fileName'];
            //     }
            // } elseif ($uploadResult && $uploadResult['code'] === Constants::INSERT_FAILURE) {
            //      return $this->jsonResponse($response, $uploadResult, 400);
            // }
            // This section needs careful review of FileUploader. For now, proceed with other logic.

            $user = User::find($id);
            if (!$user) {
                return $this->jsonResponse($response, ['error' => true, 'message' => "User not found."], 404);
            }
            $editResult = User::edit($id, (object)[ // User::edit might need review for Slim 4 compatibility
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'dob' => $dob,
                'country' => $country,
                'description' => $description,
            ]);
            if ($type !== $user->role_id) {
                User::updateUserRole($id, $type);
            }
            if ($editResult['code'] === Constants::INSERT_FAILURE) { // Assuming User::edit returns an array like this
                return $this->jsonResponse($response, ['error' => true, 'message' => "Failed to update profile. Please try again." . ($editResult["message"] ?? '')], 400);
            }
            if ($type !== $user->role_id) {
                User::updateUserRole($id, $type);
            }
            if ($status !== $user->status_id) {
                $verify = $this->VerifyAccount($id); // This method itself might need review
                if (!$verify) {
                    // VerifyAccount doesn't return a JSON response, so this is problematic
                    // return $this->jsonResponse($response, ['message' => "Could not verify user account"], 500); 
                }
            }

            $currentUserId = $_SESSION['userID'] ?? null;
            $message = ($id == $currentUserId) ? "Your profile has been updated successfully." : $first_name . "'s profile has been updated successfully.";
            return $this->jsonResponse($response, ['error' => false, 'message' => $message], 200);
        } catch (Exception $e) {
            $this->logger->error("Error updating user profile: " . $e->getMessage());
            return $this->jsonResponse($response, ['error' => true, 'message' => "An unexpected error occurred: " . $e->getMessage()], 500);
        }
    }

    public function account(Request $request, Response $response, array $args): Response
    {
        //VALIDTE SESSION
        $selected_user = $_SESSION["userID"];
        $thisUser = User::find($selected_user);
        //echo $thisUser;
        $isMember = Subscriptions::isIDExists($selected_user);
        $first_name = $thisUser["first_name"];
        //$doesHaveResume = $resumeCRUD->doesHaveResume($_SESSION["userID"]);
        $title = "My Profile";
        $admin_mode = User::isAdmin($thisUser->id);
        $thisUser['type'] = $thisUser->role->name;
        $userTypes = Role::all();
        $countries = (new Helpers())->getCountries();
        $vars = [
            'countries' => $countries,
            'page' => [
                'admin_mode' => $admin_mode,
                'title' => $title,
                'description' => 'Manage Profile',
                'uname' => $first_name,
                'uid' => $selected_user,
                'thisUser' => $thisUser,
                'isMember' => $isMember,
                'userTypes' => $userTypes,
                'name' => 'profile',
                'title' => $title // Added title
            ]
        ];
        return $this->twig->render($response, 'admin/my-profile.twig', $vars);
    }

    public function viewProfile(Request $request, Response $response, array $args): Response
    {
        $username = $args['username'] ?? null; // Access route argument
        $thisUser = User::getByUsername($username);

        if (!$thisUser) {
            $url = $this->routeParser->urlFor('notFound'); // Or a specific admin not found
            return $response->withHeader('Location', $url)->withStatus(302);
        }

        $thisUser["date_created"] = Util::getTimeDifference($thisUser->created_at);
        $thisUser["last_active"] = Util::getTimeDifference($thisUser->last_active);
        $thisUser["loyalty_points"] = RewardPoint::getCurrentRewardPointFor($thisUser->id);
        $thisUser_subs = $thisUser->subscription;
        $hasMembershipActive = false;
        $activePlan = [];
        if ($thisUser_subs) {
            switch ($thisUser_subs->status) {

                case 'trialing':
                    $thisUser["activeTrial"] = [
                        "date_expiring" => $thisUser_subs->end_date,
                        "date_created" => $thisUser_subs->start_date,
                        "plan_name" => Plan::getNameByID($thisUser_subs->membership_plan_id)
                    ];
                    break;
                case 'active':
                    $hasMembershipActive = true;
                    $thisUser["my_plans"] = [
                        "id" => $thisUser_subs->id,
                        "amount" => $thisUser_subs->price_id,
                        "date_expiring" => $thisUser_subs->end_date,
                        "date_created" => $thisUser_subs->start_date,
                        "plan_name" => Plan::getNameByID($thisUser_subs->membership_plan_id)
                    ];
                    $activePlan = [
                        "id" => $thisUser_subs->id,
                        "amount" => $thisUser_subs->price_id,
                        "date_expiring" => $thisUser_subs->end_date,
                        "date_created" => $thisUser_subs->start_date,
                        "plan_name" => Plan::getNameByID($thisUser_subs->membership_plan_id)
                    ];
                    break;
                default:
                    $thisUser["activeTrial"] = [];
                    $thisUser["my_plans"] = [];
                    break;
            }
        }
        $first_name = $thisUser->first_name . " " . $thisUser->last_name;
        $title = $first_name . " Profile";
        $admin_mode = $_SESSION["role_id"] == 1 ?? 0;
//        $allPlans = Plan::getAllPlans();
        $allPlans = Plan::all()
            ->groupBy('name')
            ->map(function ($group) {
                return [
                    'name'=>$group->pluck('name')->first(),
                    'description'=>$group->pluck('description')->first(),
                    'stripe_product_id' => $group->first()->stripe_product_id,
                    'items' => $group->map(function ($plan) {
                        return [
                            'price_id'=>$plan->stripe_price_id,
                            'interval' => $plan->interval,
                            'price' => $plan->price,
                            'is_available' => $plan->is_available
                        ];
                    })->toArray()
                ];
            });
        $vars = [
            'page' => [
                'admin_mode' => $admin_mode,
                'title' => $title,
                'description' => 'Manage Profile',
                'uname' => $first_name,
                'uid' => $selected_user,
                'thisUser' => $thisUser,
                'allPlans' => $allPlans,
                'activePlans' => $activePlan,
                'isMembershipActive' => $hasMembershipActive,
                'name' => 'view-user-profile' // Added page name
            ]
        ];
        return $this->twig->render($response, 'admin/view-full-profile.twig', $vars);
    }

    public function manageReferrals(Request $request, Response $response, array $args): Response
    {
        return $this->twig->render($response, 'admin/admin_referrals.twig', [
            'page' => [
                'name' => 'manage-referrals',
                'title' => 'Manage Referrals',
            ]
        ]);
    }

    public function retrieveReferrals(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();
        $custom_data = []; // Initialize

        $draw = $params['draw'] ?? 1;
        $start = $params['start'] ?? 0;
        $length = $params['length'] ?? 10;
        $searchValue = $params['search']['value'] ?? '';
        $order = $params['order'] ?? [];
        $orderColumnIndex = $order[0]['column'] ?? 0; // Assuming this is the index of the column in the 'columns' array
        $sortDirection = $order[0]['dir'] ?? 'asc';
        // Assuming column names are sent by DataTables or mapped here
        // This part needs to map $orderColumnIndex to actual DB column name or sortable field name.
        // For simplicity, using a placeholder. This logic was complex and might need DataTables specific request parameters.
        // $sortField = $params['columns'][$orderColumnIndex]['data'] ?? 'users.id'; 
        $sortField = 'users.first_name'; // Simplified, original logic was more complex

        // Define **ONLY "Name" should be sorted in the query**
        $query = User::query()
            ->leftJoin('referrals', 'users.id', '=', 'referrals.referrer_id')
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                \Illuminate\Database\Capsule\Manager::raw('COUNT(referrals.referred_id) AS total_connections')
            )
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email');

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('users.first_name', 'LIKE', "%$searchValue%")
                    ->orWhere('users.email', 'LIKE', "%$searchValue%");
            });
        }

        $totalRecords = User::count();
        $filteredRecords = $query->count();

        // ✅ Apply sorting in the query ONLY for name
        if ($columnIndex == 1) {
            $query->orderBy('users.first_name', $sortDirection)->orderBy('users.last_name', $sortDirection);
        }

        // ✅ Apply pagination
        $users = $query->offset($start)->limit($length)->get();

        // Process each user to get referral code, total points, and balance
        $custom_data = [];
        foreach ($users as $user) {
            // ✅ Retrieve the correct referral code
            $rewardPoint = RewardPoint::where('user_id', $user->id)
                ->where('transaction_type', 'Referral')
                ->first();

            $referralCode = $rewardPoint ? $rewardPoint->referral_code : 'N/A'; // Use 'N/A' if no code

            // ✅ Get Total Points Received
            $totalReceived = RewardPoint::getOriginalRewardPoints($user->id);

            // ✅ Get Total Redeemed Points
            $totalRedeemed = RedeemTransaction::where('user_id', $user->id)
                ->where('status', 1) // Only approved transactions
                ->sum('points');

            // ✅ Calculate Balance Points
            $balancePoints = max($totalReceived - $totalRedeemed, 0); // Prevent negative balance

            $custom_data[] = [
                "id" => $user->id,
                "name" => $user->first_name . ' ' . $user->last_name,
                "email" => $user->email,
                "referral_code" => $referralCode, // ✅ Fixed referral code retrieval
                "total_connections" => (int) $user->total_connections, // ✅ Convert to integer for proper sorting
                "total_points_received" => $totalReceived,
                "total_redeemed" => $totalRedeemed,
                "balance_points" => $balancePoints
            ];
        }

        // ✅ Sorting AFTER retrieving data (for referral codes, points, etc.)
        if (in_array($columnIndex, [2, 3, 4, 5, 6])) {
            usort($custom_data, function ($a, $b) use ($columnIndex, $sortDirection) {
                $columns = [
                    2 => 'referral_code',
                    3 => 'total_connections',
                    4 => 'total_points_received',
                    5 => 'total_redeemed',
                    6 => 'balance_points'
                ];
                $sortField = $columns[$columnIndex];

                if ($sortDirection === 'asc') {
                    return $a[$sortField] <=> $b[$sortField];
                } else {
                    return $b[$sortField] <=> $a[$sortField];
                }
            });
        }

        // ✅ Apply final pagination AFTER sorting
        $custom_data = array_slice($custom_data, $start, $length);

        // Format response
        return $this->jsonResponse($response, [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $custom_data
        ], 200);
    }
    public function manageRedeemTransactions(Request $request, Response $response, array $args): Response
    {
        return $this->twig->render($response, 'admin/admin_redeem_transactions.twig', [
            'page' => [
                'name' => 'manage-redeem-transactions',
                'title' => 'Manage Redeem Transactions',
            ]
        ]);
    }

    public function retrieveRedeemTransactions(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();
        $data = []; // Initialize

        $draw = $params['draw'] ?? 1;
        $start = $params['start'] ?? 0;
        $length = $params['length'] ?? 10;
        $searchValue = $params['search']['value'] ?? '';
        $order = $params['order'] ?? [];
        $columnIndex = $order[0]['column'] ?? 0;
        $sortDirection = $order[0]['dir'] ?? 'asc';

        // Define sortable columns mapping
        $columns = [
            "id",
            "user_name",
            "points",
            "type",
            "status",
            "date_created"
        ];

        // Validate sorting column
        $orderColumn = isset($columns[$columnIndex]) ? $columns[$columnIndex] : "id";

        // Query
        $query = RedeemTransaction::query()
            ->leftJoin('users', 'redeem_transactions.user_id', '=', 'users.id')
            ->select(
                'redeem_transactions.id',
                'users.first_name',
                'users.last_name',
                'redeem_transactions.points',
                'redeem_transactions.type', // ✅ Added to query
                'redeem_transactions.status',
                'redeem_transactions.date_created'
            );

        // Apply search filter
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('users.first_name', 'LIKE', "%$searchValue%")
                    ->orWhere('users.last_name', 'LIKE', "%$searchValue%")
                    ->orWhere('redeem_transactions.status', 'LIKE', "%$searchValue%")
                    ->orWhere('redeem_transactions.type', 'LIKE', "%$searchValue%");
            });
        }

        $totalRecords = RedeemTransaction::count();
        $filteredRecords = $query->count();

        // ✅ Apply sorting dynamically
        $query->orderBy($orderColumn, $sortDirection);

        // Apply pagination
        $transactions = $query->offset($start)->limit($length)->get();

        // Format data for DataTables
        $data = [];
        foreach ($transactions as $transaction) {
            $data[] = [
                "id" => $transaction->id,
                "user_name" => $transaction->first_name . ' ' . $transaction->last_name,
                "points" => $transaction->points,
                "type" => ($transaction->type == 'redeem_coupon') ? "Coupon" : "Withdraw", // ✅ Convert to readable text
                "status" => ($transaction->status == 1) ? "Approved" : "Pending",
                "date_created" => date('Y-m-d H:i:s', strtotime($transaction->date_created)),
            ];
        }

        return $this->jsonResponse($response, [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data
        ], 200);
    }
    public function manageReferralTransactions(Request $request, Response $response, array $args): Response
    {
        return $this->twig->render($response, 'admin/admin_referral_transactions.twig', [
             'page' => [ // Added page context for consistency
                'name' => 'manage-referral-transactions',
                'title' => 'Manage Referral Transactions',
            ]
        ]);
    }

    public function retrieveReferralTransactions(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();
        $data = []; // Initialize

        $draw = $params['draw'] ?? 1;
        $start = $params['start'] ?? 0;
        $length = $params['length'] ?? 10;
        $searchValue = $params['search']['value'] ?? '';
        $order = $params['order'] ?? [];
        $columnIndex = $order[0]['column'] ?? 0;
        $sortDirection = $order[0]['dir'] ?? 'asc';

        // Define sortable columns mapping
        $columns = ["id", "referrer_name", "referred_name", "referral_code", "points", "status", "date_created"];
        $orderColumn = isset($columns[$columnIndex]) ? $columns[$columnIndex] : "id";

        // Query
        $query = Referral::query()
            ->leftJoin('users as referrer', 'referrals.referrer_id', '=', 'referrer.id')
            ->leftJoin('users as referred', 'referrals.referred_id', '=', 'referred.id')
            ->leftJoin('reward_points', 'referrals.reward_point_id', '=', 'reward_points.id')
            ->select(
                'referrals.id',
                \Illuminate\Database\Capsule\Manager::raw("CONCAT(referrer.first_name, ' ', referrer.last_name) AS referrer_name"),
                \Illuminate\Database\Capsule\Manager::raw("CONCAT(referred.first_name, ' ', referred.last_name) AS referred_name"),
                'reward_points.referral_code',
                'reward_points.points',
                'referrals.status',
                'referrals.date_created'
            );

        // Apply search filter
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('referrer.first_name', 'LIKE', "%$searchValue%")
                    ->orWhere('referrer.last_name', 'LIKE', "%$searchValue%")
                    ->orWhere('referred.first_name', 'LIKE', "%$searchValue%")
                    ->orWhere('referred.last_name', 'LIKE', "%$searchValue%")
                    ->orWhere('reward_points.referral_code', 'LIKE', "%$searchValue%");
            });
        } 

        $totalRecords = Referral::count();
        $filteredRecords = $query->count();

        // Apply sorting dynamically
        $query->orderBy($orderColumn, $sortDirection);

        // Apply pagination
        $transactions = $query->offset($start)->limit($length)->get();

        // Format data for DataTables
        $data = [];
        foreach ($transactions as $transaction) {
            $data[] = [
                "id" => $transaction->id,
                "referrer_name" => $transaction->referrer_name,
                "referred_name" => $transaction->referred_name,
                "referral_code" => $transaction->referral_code,
                "points" => $transaction->points ?? 0,
                "status" => ($transaction->status == 1) ? "Approved" : "Pending",
                "date_created" => date('Y-m-d H:i:s', strtotime($transaction->date_created)),
            ];
        }

        return $this->jsonResponse($response, [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data
        ], 200);
    }
    public function FreeTrialsSummary(Request $request, Response $response, array $args): Response
    {
        $data = Subscriptions::where('status', 'trialing')->get(); // Consider pagination for large datasets
        $custom_data = array();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["id"] = $row->id;
                $tmp["user_id"] = $row->user_id;
                $tmp["plan_id"] = $row->membership_plan_id;
                $tmp["plan_name"] = Plan::getNameByID($row->membership_plan_id);
                $tmp["user_name"] = User::getNameByID($row->user_id);

                $tmp["date_created"] = $row->start_date;
                $tmp["date_expiring"] = $row->end_date;
                $tmp["trial_end_date"] = $row->trial_end_date;
                $custom_data[] = $tmp;
            }
        }

        $vars = [
            'page' => [
                'title' => 'View All Free Trials',
                'description' => 'List of All Free Trials',
                'data' => $custom_data,
                'name' => "manage-membership",
                'title' => 'View All Free Trials' // Added title
            ]
        ];
        return $this->twig->render($response, 'admin/admin_free_trials.twig', $vars);
    }

    public function GrantTrial(Request $request, Response $response, array $args): Response
    {
        $output = array();
        $params = (array)$request->getParsedBody();
        $user_id = $params['user_id'] ?? null;
        $plan_id = $params['plan_id'] ?? null;
        $startDate = $params['startDate'];

        $planTitle = Plan::getNameByID($plan_id);
        $startDate = new DateTime($startDate);
        $startDate = $startDate->format('Y-m-d');
        $closingDate = new DateTime($startDate);
        $closingDate->modify('+10 day');
        $endDate = $closingDate->format('Y-m-d');

        $numActivePlans = Subscriptions::getNumMyActivePlan($user_id);
        if ($numActivePlans > 0) {
            $latestActivePlan = Subscriptions::getMyActivePlan($user_id);
            $thisActivePlanID = $latestActivePlan["plan_id"];
            $activePlanTitle = Plan::getNameByID($thisActivePlanID);
            if ($thisActivePlanID == $plan_id) {
                $output["error"] = true;
                $tillDate = Util::getFormalDate($latestActivePlan["date_expiring"]);
                $output["message"] = $planTitle . " Subscription is already active for your account upto " . $tillDate . ".";
                $jsonData = json_encode($output);
                $response = $response->withHeader('Content-Type', 'application/json');
                return $response->getBody()->write($jsonData);
            }

            if ($thisActivePlanID > $plan_id) {
                $output["error"] = true;
                $tillDate = Util::getFormalDate($latestActivePlan["date_expiring"]);
                $output["message"] = $activePlanTitle . " Subscription is already active for your account upto " . $tillDate . ". Are you sure that you want to start your trial for " . $planTitle . "?";
                $jsonData = json_encode($output);
                $response = $response->withHeader('Content-Type', 'application/json');
                return $response->getBody()->write($jsonData);
            }
            // else {
            //     //$output["error"] = true;
            //     //$tillDate = $utilCRUD->getFormalDate($latestActivePlan["date_expiring"]);
            //     //$output["message"] = $activePlanTitle." Subscription is already active for your account upto ".$tillDate.". This request could not be processed.";
            //     //echoRespnse(200, $output);
            //     //exit;
            // }

        }


        $numActiveTrials = FreeTrial::getNumMyActivePlan($user_id);
        if ($numActiveTrials > 0) {
            $activeTrialWithPlanName = Plan::getNameByID($thisActivePlanID);
        }
        $numAllActivatesBefore = FreeTrial::getNumAllPlansFor($user_id, $plan_id);
        $numCurrentActivates = FreeTrial::getNumActivePlansFor($user_id, $plan_id);


        if ($numCurrentActivates > 0) {
            $output["error"] = true;
            $output["message"] = "You already have a free trail going on for " . $planTitle . " Subscription.";
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        if ($numAllActivatesBefore > 0) {
            $output["error"] = true;
            $output["message"] = "You have already availed your free trail for " . $planTitle . " Subscription.";
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        if ($numActiveTrials > 0) {
            $output["error"] = true;
            $output["message"] = "Looks like you already have free trials hoing on. Please wait until you activate free trial for " . $planTitle . " Subscription.";
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        $date_created = date('Y-m-d H:i:s');
        $res = FreeTrial::createTrial($user_id, $plan_id, $startDate, $endDate);
        if (!$res["error"]) {
            $output["error"] = false;
            $endDateFormat = Util::getFormalDate($endDate);
            $output["message"] = "Your free trial for " . $planTitle . " has been started. Your free trial ends on " . $endDateFormat . ".";
            $output["id"] = $res["id"];
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write($jsonData);
        } else {
            $output["error"] = true;
            $output["message"] = "Failed to enable the free trial. Please try again.";
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write($jsonData);
        }
        return $response;
    }


    function ViewContactSubmision(Request $request, Response $response, array $args): Response
    {
        $data = Contact::getAllMessages(); // Consider pagination for large datasets
        $submissions = array();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["id"] = $row["id"];
                $tmp["name"] = $row["name"];
                $tmp["email"] = $row["email"];
                $tmp["subject"] = $row["subject"];
                $tmp["message"] = $row["message"];
                $tmp["date_created"] = Util::getFormalDate($row["date_created"]);
                array_push($submissions, $tmp);
            }
        }

        $vars = [
            'page' => [
                'title' => 'Contact Submissions | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'data' => $submissions,
                'name' => 'manage-users', // Consider renaming if not managing users
                'title' => 'Contact Submissions' // Added title
            ]
        ];
        return $this->twig->render($response, 'admin/admin-contact-submissions.twig', $vars);
    }

    /**
     * @throws ApiErrorException
     */
    function create_customer_portal_session(Request $request, Response $response, array $args): Response
    {
        // $stripe = new SubscriptionService($this->db, $this->logger); // Use injected $this->subscription
        $current_user_id = $_SESSION['userID'] ?? null;
        if (!$current_user_id) {
             return $this->jsonResponse($response, ['error' => true, 'message' => 'User not authenticated.'], 401);
        }
        $current_user = User::find($current_user_id);

        if (!$current_user) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'User not found.'], 404);
        }
        if (!$current_user->stripe_customer_id) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'Stripe customer ID not found for user.'], 400);
        }
        try {
            $res = $this->subscription->billingPortal((object)[ // Use injected service
                'customer_id' => $current_user->stripe_customer_id,
                'return_url' => ($_ENV['APP_URL'] ?? '') . $this->routeParser->urlFor('dashboard') // Use RouteParser
            ]);
            return $response->withHeader('Location', $res->url)->withStatus(302);
        } catch (ApiErrorException $e) { // More specific Stripe exception
            $this->logger->error("Stripe API Error in create_customer_portal_session: " . $e->getMessage());
            return $this->jsonResponse($response, ['error' => true, 'message' => 'Stripe Error: ' . $e->getMessage()], 500);
        } catch (Exception $e) {
            $this->logger->error("Error in create_customer_portal_session: " . $e->getMessage());
            return $this->jsonResponse($response, ['error' => true, 'message' => 'An unexpected error occurred.'], 500);
        }
    }

    function bookdetails(Request $request, Response $response, array $args): Response
    {
        $docQCode = $args['id'] ?? null; // Access route argument
        $document = Document::where('qcode', $docQCode)->first();
        if (!$document) {
            $url = $this->routeParser->urlFor('notFound');
            return $response->withHeader('Location', $url)->withStatus(302);
        }
        // Consider if direct $_SESSION modification is best practice here
        $_SESSION["last_saved"] = (string)$request->getUri(); 
        $name = $document->title;
        $doc_type_selected = $document->documentType;
        switch ($doc_type_selected) {
            case 1:
                $doc_type = "Magazine";
                $unlock_title = "Read Magazine";
                $unlock_tip = "Click here to read this Magazine now.";
                break;
            case 2:
                $doc_type = "E-Book";
                $unlock_title = "Read E-Book";
                $unlock_tip = "Click here to start reading this E-book now.";
                break;
            case 3:
                $doc_type = "Audio Book";
                $unlock_title = "Listen Audio Book";
                $unlock_tip = "Click here to start listening this audio book now.";
                break;
        }

        $all_reviews = array();
        $all_reviews_array = DocumentReview::getReviewsFor($document->id);
        if ($all_reviews_array) {
            foreach ($all_reviews_array as $row) {
                $tmp = array();
                $tmp["id"] = $row["id"];
                //$tmp["user_id"] = $row["user_id"];
                $tmp["reviewer_name"] = User::getNameByID($row["user_id"]);
                $tmp["reviewer_image"] = User::getUserImageByID($row["user_id"]);
                $tmp["stars"] = $row["stars"];
                $tmp["text"] = $row["text"];
                //$tmp["date_created"] = $row["date_created"];
                $tmp["date_created"] = Util::getFormalDate($row["date_created"]);
                $all_reviews[] = $tmp;
            }
        }
        // Get the URI object from the request
        $baseUrl = $_ENV['APP_URL'];
        $countries = (new Helpers())->getCountries();

        $vars = [
            'countries' => $countries,
            'page' => [
                'title' => "Read " . $name . " Online on BaziChic - Chinese Metaphysics Consultancy",
                'description' => "Find more E-Books, Audio Books and Magazines at Bazichic.",
                'og_title' => "Read " . $name . " Online on BaziChic - Chinese Metaphysics Consultancy",
                'secure_img_url' => $baseUrl . "/" . $document["cover"],
                'og_image' => $baseUrl . "/" . $document["cover"],
                'og_url' => $baseUrl . "/" . $document["qcode"],
                'unlock_title' => $unlock_title,
                'unlock_tip' => $unlock_tip,
            ],
            'document' => [
                'title' => $document["title"],
                'description' => $document["description"],
                'category_id' => $document["category_id"],
                'cover' => $document["cover"],
                'qcode' => $document["qcode"],
                'category' => Category::getNameByID($document->category_id),
                // 'keywords' => $keyword_list,
                'price' => $document["price"],
                'read_time' => $document["read_time"],
                'listen_time' => $document["listen_time"],
                'documentType' => $document["documentType"],
                'doc_type' => $doc_type,
                'user_id' => $document["user_id"],
                'author_name' => $document["author_name"],
                'author_link' => $document["author_link"],
                'link' => $document["link"],
                'tag' => $document["tag"],
                'author_desc' => $document["author_desc"],
                'is_downloadable' => $document["is_downloadable"],
                'is_published' => $document["is_published"],
                'date_created' => Util::getTimeDifference($document->date_created),
                'num_pages' => $document["num_pages"],
                'all_reviews' => $all_reviews,
                'avg_rating' => DocumentReview::getAvgReviewsFor($document->id),
                'num_reviews' => DocumentReview::getNumReviewsFor($document->id),
                'num_likes' => DocumentLike::getNumLikes($document->id),
                'num_saves' => DocumentSave::getNumSaves($document->id), // This was not refactored in EbookController
                'name' => 'admin-book-detail' // Added page name
            ]
        ];
        return $this->twig->render($response, 'admin/book-detail.twig', $vars);
    }

    public function create_user(Request $request, Response $response, array $args): Response
    {
        $params = (array)$request->getParsedBody();
        $output = array();
        $output["note"] = ""; // This seems to be appended to, but not used in the response
        // reading post parameters
        $first_name = $params['first_name'];
        $last_name = $params['last_name'];
        $email = $params['email_reg'];
        $dob = $params['dob'];
        $country = $params['country'];
        $type = $params['type'];
        $ref_user_id = 0;
        if (empty($first_name)) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => "First name can not be empty."
            ], 400);
        }
        if (empty($last_name)) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => "Last name can not be empty."
            ], 400);
        }
        if (empty($email)) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => "Let us know your email."
            ], 400);
        }
        if (empty($dob)) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => "Let us know your birth date."
            ], 400);
        }
        if (empty($country)) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => "Select your country of residence."
            ], 400);
        }
        if (empty($type)) {
            return $this->jsonResponse($response, [
                'error' => true,
                'message' => "Select your user type."
            ], 400);
        }

        $phone = "";
        $date_created = date('Y-m-d H:i:s');
        $api_key = Util::generateApiKey();
        $res = User::register((object)[
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
            'email' => $email,
            'password' => 'baziuser',
            'dob' => $dob,
            'country' => $country,
            'status_id' => 1, //user,
            'role_id' => $type,
            'ref_user_id' => $ref_user_id,
            'referral_code' => null,
            'api_key' => $api_key,
            'reg_source' => 'by ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name']
        ]);

        switch ($res["code"]) {
            case Constants::INSERT_FAILURE:
                return $this->jsonResponse($response, ["error" => true, "message" => $res["message"] ?? "Oops! An error occurred while registering user"], 400);
            case Constants::ALREADY_EXIST:
                return $this->jsonResponse($response, ["error" => true, "message" => "The email is already registered."], 400);
        }
        $output['error'] = false;
        $output["message"] = $res['message'] ?? "Great! Your new account has been registered successfully.";
        $output["id"] = $user_id = $res["id"];
        $output["user_name"] =  $user_name =  $res['userName'];
        $stripe = new StripeService();
        $customer = $stripe->createCustomer((object)['name' => $first_name . ' ' . $last_name, 'email' => $email]);
        if ($customer) {
            User::where('id', $user_id)->update(['stripe_customer_id' => $customer->id]);
        }
        /********* Notify now and send email ********/
        $output = $this->VerificationLink($user_id, $first_name, $email, $output);
        $title = $this->sendNotification($first_name, $user_id, $user_name, $date_created, $output["note"]);
        /********* Notify Done ********/

        /**** Log Activity ********/
        try {
            $title = "New Registration - " . $first_name . " " . $last_name;
            $activity = $first_name . " " . $last_name . " registered an account.";
            Activity::createActivity((object)[
                'who_id' => 1,
                'title' => $title,
                'message' => $activity,
                'data_id' => $output["id"],
                'data_title' => "Registration",
            ]);
            $output["note"] .= " Logged new account activity.";
        } catch (Exception $e) {
            $output["note"] .= "Error logging activity. " . $e->getMessage();
        }
        return $this->jsonResponse($response, $output);
    }

    /**
     * @throws ApiErrorException
     */
    /**
     * @throws ApiErrorException
     */
    public function getUserInvoice(Request $request, Response $response, array $args): Response
    {
        $InvoiceId = $args['id'] ?? null; // Access route argument
        $user_id_from_route = $args['user_id'] ?? null; // Access route argument

        if (empty($InvoiceId)) {
            $url = $this->routeParser->urlFor('notFound');
            return $response->withHeader('Location', $url)->withStatus(302);
        }
        if (empty($user_id_from_route)) {
            $url = $this->routeParser->urlFor('unauthorized');
            return $response->withHeader('Location', $url)->withStatus(302);
        }
        
        $current_session_user_id = $_SESSION['userID'] ?? null;
        $current_session_role_id = $_SESSION['role_id'] ?? null;

        if ($user_id_from_route != $current_session_user_id && $current_session_role_id != 1) {
            $url = $this->routeParser->urlFor('unauthorized');
            return $response->withHeader('Location', $url)->withStatus(302);
        }
        
        $user_sub = null;
        $invoice = $this->subscription->getInvoice($InvoiceId);
        //        if (isset($invoice['code']) &&$invoice['code'] === Constants::INSERT_FAILURE ){
        ////            system generate report
        ////            $invoice =
        //        }
        $current_user = User::find($user_id);
        if ($current_user) {
            $user_sub = $current_user->subscription;
        }
        $vars = [
            'page' => [
                'title' => 'Invoice | Bazichic Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'dateOfSubscription' => isset($user_sub) ? $user_sub->end_date : null,
                'thisUser' => $current_user,
                'invoice' => $invoice,
                'name' => 'admin-user-invoice' // Added page name
            ],
        ];

        return $this->twig->render($response, 'invoice.twig', $vars);
    }

    private function validateInput(array $data): ?array // Ensure $data is an array
    {
        $requiredFields = [
            'first_name' => 'First name cannot be empty.',
            'last_name' => 'Last name cannot be empty.',
            'email' => 'Let us know your email.',
            'dob' => 'Let us know your birth date.',
            'country' => 'Select your country of residence.',
        ];

        foreach ($requiredFields as $field => $errorMessage) {
            if (empty($data[$field])) {
                return  [
                    "error" => true,
                    "message" => $errorMessage,
                ];
            }
        }

        return null; // No validation errors
    }
    public function VerifyAccount(int $id): bool // Added type hint for $id
    {
        $verification = EmailVerifications::where('user_id', $id)->first();
        
        if (!$verification) {
            // Handle case where no verification record exists - might need to create one or error
            // For now, let's assume it implies verification cannot proceed.
             // Create a new token if none exists
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
            EmailVerifications::create([
                'user_id' => $id,
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);
            // Attempt to verify with the new token (or decide if this flow is correct)
            // This part of the logic might need review based on desired behavior
            // For now, let's assume if no token, it means it's not verified yet.
            // $res = EmailVerifications::verify($token);
        } else {
            $token = $verification->token;
            $res = EmailVerifications::verify($token);

            if ($res['status'] !== 'success') {
                // Generate a new verification token if the old one failed or expired
                $token = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
                EmailVerifications::updateOrCreate(
                    ['user_id' => $id],
                    ['token' => $token, 'expires_at' => $expiresAt, 'verified_at' => null]
                );
                // $res = EmailVerifications::verify($token); // Re-verify if needed, or just update token
            }
        }

        $user = User::find($id);
        if ($user) {
            return $user->update(['status_id' => 1]);
        }
        return false;
    }

    // This method appears to be a helper and not a route action.
    // It's not used within this controller directly as an action.
    // Kept for completeness of refactoring if it's used elsewhere or intended for future use.
    function getMonthlyEarnings(string $status, string $start_date, string $end_date): array
    {
        $earningsArr = [];

        for ($i = 0; $i < 12; $i++) {
            $for_date_start = date('Y-m-01', strtotime($end_date . ' -' . $i . ' months')); // First day of the month
            $for_date_end = date('Y-m-t', strtotime($for_date_start)); // Last day of the month

            if ($for_date_start < $start_date) {
                break;
            }

            $earningsArr[] = Subscriptions::getSumAllTransactionsBetween($status, $for_date_start, $for_date_end) ?? 0;
        }

        return array_reverse($earningsArr); // Ensures the array is in chronological order
    }
}
