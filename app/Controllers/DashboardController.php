<?php

namespace App\Controllers;
use Carbon\Carbon;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\Constants;
use App\Helpers\Helpers;
use App\Models\Activity;
use App\Models\DocumentLike;
use App\Models\Document;
use App\Models\DocumentReview;
use App\Models\DocumentSave;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\Referral;
use App\Models\RewardPoint;
use App\Models\User;
use App\Models\Util;
use Exception;
use DateTime;

class DashboardController extends BaseController
{

    public function index(Request $request, Response $response, $args)
    {
        $thisUser = User::find($_SESSION["userID"]);
        if (!$thisUser) {
            return $response->withRedirect((string) $this->router->pathFor('notFound'));
        }
        $reward_points = RewardPoint::getCurrentRewardPointFor($thisUser["id"]);
        $recent_activities = array();
        $dash_notis = array();
        $saved_docs = array();
        $membership_info = array();
        //$membership_info= checkMembership($thisUser["id"]);
        $data = Document::getAllDocuments(1);
        if ($thisUser["role_id"] == 1) {
            $num_reviews = DocumentReview::getNumAllReviews();
            //$num_likes = $likeCRUD->getNumAllLikes();
            $num_likes = DocumentLike::getTotalLikesDone($_SESSION["userID"]);
            $num_saves = DocumentSave::getNumAllSaves();
            //Recent Activities
            $recent_activities_arr = Activity::getFewTopActivities();

            if (count($recent_activities_arr) > 0) {
                foreach ($recent_activities_arr as $row) {
                    $tmp = array();
                    $tmp["id"] = $row["id"];
                    $tmp["title"] = $row["title"];
                    $tmp["message"] = $row["message"];
                    $tmp["status"] = $row["status"];
                    // $tmp["user_image"] = isset($row["sender_id"])? $userCRUD->getUserImageByID($row["sender_id"]): '';
                    $tmp["date_created"] = Util::getTimeDifference($row["date_created"]);
                    $tmp["action_link"] = Activity::getActionLink($row["data_id"], $row["data_title"]);
                    $recent_activities[] = $tmp;
                }
            }
        } else {
            $num_reviews = DocumentReview::getTotalReviewsDone($_SESSION["userID"]);
            $num_likes = DocumentLike::getTotalLikesDone($_SESSION["userID"]);
            $num_saves = DocumentSave::getNumAllMySaves($_SESSION["userID"]);
        }

        /********** SAVED DOCS  ***********/
        $data = DocumentSave::getAllMySaves($_SESSION["userID"]);
        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["read_status"] = "Just Started";
                $tmp["id"] = $row["id"];
                $tmp["title"] = $row["title"];
                $tmp["qcode"] = $row["qcode"];
                $tmp["cover"] = $row["cover"];
                $tmp["page"] = $row["page"];
                $tmp["progress"] = $row["progress"];
                $tmp["doc_type"] = "Document";
                $tmp["access_verb"] = "Read";
                if (isset($row["documentType"])) {
                    switch ($row["documentType"]) {
                        case 1:
                            $tmp["doc_type"] = "E-Book";
                            $tmp["access_verb"] = "Read";
                            break;

                        case 2:
                            $tmp["doc_type"] = "Audio Book";
                            $tmp["access_verb"] = "Listen";
                            break;

                        case 3:
                            $tmp["doc_type"] = "Magazine";
                            $tmp["access_verb"] = "Read";
                            break;
                    }
                }

                if ($row["progress"] >= 0 && $row["progress"] < 80) {
                    $tmp["read_status"] = $row["progress"] . "% Complete";
                } else {
                    $tmp["read_status"] = (100 - $row["progress"]) . "% Left";
                }
                $tmp["is_downloadable"] = $row["is_downloadable"];
                $tmp["is_reviewed"] = DocumentReview::isReviewedBy($_SESSION["userID"], $row["id"]);
                $tmp["is_liked"] = DocumentLike::isLikedBy($_SESSION["userID"], $row["id"]);
                if (!empty($row["date_updated"])) {
                    $tmp["date_updated"] = Util::getTimeDifference($row["date_updated"]);
                }
                $tmp["date_created"] = Util::getTimeDifference($row["date_created"]);
                $tmp["link"] = $row["link"];
                array_push($saved_docs, $tmp);
            }
        }

        $my_connections = array();
        $my_referral_codes = array();
        $dataMyCon = Referral::getMyConnections($thisUser["id"]);
        if (count($dataMyCon) > 0) {
            foreach ($dataMyCon as $conrow) {
                $con_tmp = array();
                $con_tmp["id"] = $conrow["id"];
                $con_tmp["first_name"] = $conrow["first_name"];
                $con_tmp["last_name"] = $conrow["last_name"];
                $con_tmp["referral_code"] = $conrow["referral_code"];
                $con_tmp["date_created"] = $conrow["date_created"];
                try {
                    $con_tmp["date_created"] = Util::getFormalDate($conrow["date_created"]);
                } catch (Exception $e) {
                }
                $my_connections[] = $con_tmp;
            }
        }


        $referralCodesArr = Referral::getAllMyReferrals($_SESSION["userID"]);
        if (count($referralCodesArr) > 0) {
            foreach ($referralCodesArr as $coderow) {
                $codetmp = array();
                $codetmp["id"] = $coderow["id"];
                $codetmp["code"] = $coderow["code"];
                $codetmp["status"] = $coderow["status"];
                $codetmp["date_created"] = $coderow["date_created"];
                $codetmp["date_updated"] = $coderow["date_updated"];
                try {
                    if (!empty($coderow["date_created"])) {
                        $codetmp["date_created"] = Util::getFormalDate($coderow["date_created"]);
                    }
                } catch (Exception $e) {
                }
                $codetmp["total_redeems"] = Referral::getNumRedeems($coderow["code"]);
                $my_referral_codes[] = $codetmp;
            }
        }
        /******** GET LAST 10 NOTIFICATIONS *******/
        $dash_notis_arr =Notification::where('seen',0)->orderBy('date_created', 'desc')->take(10)->get();
            foreach ($dash_notis_arr as $row) {
                $tmp = [];
                $tmp['id']= $row['id'];
                $tmp["title"] = $row["title"];
                $tmp["message"] = $row["message"];
                $tmp["seen"] = $row["seen"];
                $tmp["sender_image"] = User::getUserImageByID($row["sender_id"]);
                $tmp["date_created"] = Util::getTimeDifference($row["date_created"]);
                $dash_notis[] = $tmp;
            }
        $trialMessage = "";
        /*********** Then Check Active Paid Subscriptions ***********/
        $warning = "";
        $baseUrl=$_ENV['APP_URL'];
        $user_subscription = $thisUser->subscription;
        if ($user_subscription) {
            if ($user_subscription->status == 'active') {
                $activePlan= Plan::find($user_subscription->membership_plan_id);
                $activeExpiry = Util::getFormalDate($user_subscription->end_date);
                // $warning = "".$activePlanName." Subscription is active up to ".$activeExpiry.".";
                try {
                    $numDays = Util::dateDiffInDays($user_subscription->start_date, $user_subscription->end_date);
                    if ($numDays < 20) {
                        $anchorUrl = $baseUrl."/get-membership/" . $activePlan->id;
                        $warning = "The " . $activePlan->name . " Subscription is active up to " . $activeExpiry . ".";
                        $warning .= 'Renew within ' . $numDays . ' days to enjoy uninterrupted access. <a href="' . $anchorUrl . '"> Renew Subscription.</a>';
                    }
                } catch (Exception $e) {
                    $this->logger->info($e->getMessage());
                }
            }else if($user_subscription->status == 'trialing'){
                $activeTrialPlan= Plan::find($user_subscription->membership_plan_id);
                $activeTrialExpiry = Util::getFormalDate($user_subscription->trial_end_date);
                $anchorUrl = $this->router->pathFor('get-membership', ['type' => $activeTrialPlan->id]);
                $targetDate = Carbon::parse($activeTrialExpiry); // Example future date
                $today = Carbon::today();

                $daysRemaining = $today->diffInDays($targetDate);
                $trialMessage = 'You have '.$daysRemaining.' Days Free Trial for ' . $activeTrialPlan->name . ' Subscription. Your trial ends on ' . $activeTrialExpiry ;


            }
        }
        $_SESSION['num_saves']=$num_saves;
        /************************************/
        $countries = (new Helpers())->getCountries();
        $vars = [
            'countries' => $countries,
            'page' => [
                'name' => 'dashboard',
                'title' => 'My Dashboard | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'warning' => $warning,
                'recent_activities' => $recent_activities,
                'dash_notis' => $dash_notis,
                'trialMessage' => $trialMessage,
                'saved_docs' => $saved_docs,
                'membership_info' => $membership_info,
                'num_reviews' => $num_reviews,
                'num_likes' => $num_likes,
                'num_saves' => $num_saves,
                'reward_points' => $reward_points,
                'my_connections' => $my_connections,
                'my_referral_codes' => $my_referral_codes,
                'thisUser' => $thisUser,
                'role_id'=>$_SESSION['role_id']
            ],
        ];
        return $this->view->render($response, 'dashboard.twig', $vars);
    }
    function bookmark(Request $request, Response $response, $args)
    {
        $s_no = $request->getAttribute('s_no');
        if (empty($s_no)) {
            $s_no = 0;
        }
        $data = DocumentLike::getMyBookmarks($_SESSION["userID"]);
        //$data = $docCRUD->getAllFreeEBooks();
        $numAllLikes = DocumentLike::getTotalLikesDone($_SESSION["userID"]);
        //Do proper session management in helper
        $custom_data =$data->toArray();
//        if (count($data) > 0) {
//            foreach ($data as $row) {
//                $tmp = array();
//                $tmp["id"] = $row["id"];
//                $tmp["title"] = $row["title"];
//                $tmp["qcode"] = $row["qcode"];
//                $tmp["cover"] = $row["cover"];
//                $tmp["tag"] = $row["tag"];
//                //$tmp["user_image"] = $userCRUD->getUserImageByID($row["user_id"]);
//                $tmp["date_created"] = Util::getTimeDifference($row["date_created"]);
//                //$tmp["action_link"] = $notificationCRUD->getActionLink($row["data_id"], $row["data_title"]);
//                array_push($custom_data, $tmp);
//            }
//        }

        $vars = [
            'page' => [
                'name' => 'favourites',
                'title' => 'My Bookmarks',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'data' => $custom_data,
                'numAllLikes' => $numAllLikes
            ],
        ];
        return $this->view->render($response, 'my_bookmarks.twig', $vars);
    }
    function saved_reads(Request $request, Response $response, $args)
    {

        $data = DocumentSave::getAllMySaves($_SESSION["userID"]);
        $numAllSaves = DocumentSave::getNumAllMySaves($_SESSION["userID"]);
        //Do proper session management in helper
        $custom_data = array();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["id"] = $row->document->id;
                $tmp["title"] = $row->document->title;
                $tmp["qcode"] = $row->document->qcode;
                $tmp["cover"] = $row->document->cover;
                $tmp["page"] = $row->page;
                $tmp["progress"] = $row->progress;
                switch ($row->document->document_type) {
                    case 1:
                        $tmp["doc_type"] = "E-Book";
                        $tmp["access_verb"] = "Read";
                        break;

                    case 2:
                        $tmp["doc_type"] = "Audio Book";
                        $tmp["access_verb"] = "Listen";
                        break;

                    case 3:
                        $tmp["doc_type"] = "Magazine";
                        $tmp["access_verb"] = "Read";
                        break;
                }
                if ($row->progress > 1 && $row->progress < 80) {
                    $tmp["read_status"] = $row->progress . "% Complete";
                } else {
                    $tmp["read_status"] = (100 - $row["progress"]) . "% Left";
                }
                $tmp["is_downloadable"] = $row->document->is_downloadable;
                $tmp["is_reviewed"] = DocumentReview::isReviewedBy($_SESSION["userID"], $row->document->id);
                $tmp["is_liked"] = DocumentLike::isLikedBy($_SESSION["userID"], $row->document->id);
                if (!empty($row["date_updated"])) {
                    $tmp["date_updated"] = Util::getTimeDifference($row->document->date_updated);
                }
                $tmp["date_created"] = Util::getTimeDifference($row->document->date_created);
                $tmp["link"] = $row->document->link;
                $custom_data[] = $tmp;
            }
        }
        $status = [
            [
                "id" => 1,
                "value"=>"All Saves"
            ],
            [
                "id"=>2,
                "value"=>"Completed"
            ],
            [
                "id"=>3,
                "value"=>"Ongoing"
            ]
        ];
        $vars = [
            'page' => [
                'name' => 'savedreads',
                'title' => 'Saved Reads',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'data' => $custom_data,
                'numAllSaves' => $numAllSaves,
                'status' => $status
            ],
        ];

        return $this->view->render($response, 'saved-reads.twig', $vars);
    }
    function saved_readsbyStatus(Request $request, Response $response, $args)
    {
        $status = $request->getParsedBody()['status'];
        switch ($status) {
            case 1:
                $data = DocumentSave::getAllMySaves($_SESSION["userID"]);
                break;
            case 2:
                $data = DocumentSave::where('user_id', $_SESSION["userID"])->where('progress', '==', 100)->get();
                break;
            case 3:
                $data = DocumentSave::where('user_id', $_SESSION["userID"])->where('progress', '<', 100)->get();
                break;
        }

        //Do proper session management in helper
        $custom_data = array();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["id"] = $row->document->id;
                $tmp["title"] = $row->document->title;
                $tmp["qcode"] = $row->document->qcode;
                $tmp["cover"] = $row->document->cover;
                $tmp["page"] = $row->page;
                $tmp["progress"] = $row->progress;
                switch ($row->document->document_type) {
                    case 1:
                        $tmp["doc_type"] = "E-Book";
                        $tmp["access_verb"] = "Read";
                        break;

                    case 2:
                        $tmp["doc_type"] = "Audio Book";
                        $tmp["access_verb"] = "Listen";
                        break;

                    case 3:
                        $tmp["doc_type"] = "Magazine";
                        $tmp["access_verb"] = "Read";
                        break;
                }
                if ($row->progress > 1 && $row->progress < 80) {
                    $tmp["read_status"] = $row->progress . "% Complete";
                } else {
                    $tmp["read_status"] = (100 - $row["progress"]) . "% Left";
                }
                $tmp["is_downloadable"] = $row->document->is_downloadable;
                $tmp["is_reviewed"] = DocumentReview::isReviewedBy($_SESSION["userID"], $row->document->id);
                $tmp["is_liked"] = DocumentLike::isLikedBy($_SESSION["userID"], $row->document->id);
                if (!empty($row["date_updated"])) {
                    $tmp["date_updated"] = Util::getTimeDifference($row->document->date_updated);
                }
                $tmp["date_created"] = Util::getTimeDifference($row->document->date_created);
                $tmp["link"] = $row->document->link;
                $custom_data[] = $tmp;
            }
        }

        return $this->jsonResponse($response,[ 'document' => $custom_data],200);
    }
    function referral_codes(Request $request, Response $response, $args)
    {
        $data = Referral::getAllMyReferrals($_SESSION["userID"]);
        $custom_data = array();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["id"] = $row["id"];
                $tmp["code"] = $row["referral_code"];
                $tmp["status"] = $row["status"];
                $tmp["date_created"] = $row["date_created"];
                $tmp["date_updated"] = $row["date_updated"];
                try {
                    if (!empty($row["date_created"])) {
                        $tmp["date_created"] = Util::getFormalDate($row["date_created"]);
                    }
                } catch (Exception $e) {
                }
                $tmp["total_redeems"] = Referral::getNumRedeems($row["referral_code"]);
                $custom_data[] = $tmp;
            }
        } 
        $num_codes = Referral::getNumMyRefeerals($_SESSION["userID"]);
        $num_connections = Referral::getNumMyConnections($_SESSION["userID"]);
        $reward_point = RewardPoint::getCurrentRewardPointFor($_SESSION["userID"]);

        $vars = [
            'page' => [
                'name'=>'referrals',
                'title' => 'My Referral Codes',
                'description' => 'List of Referral Codes',
                'data' => $custom_data,
                'num_codes' => $num_codes,
                'reward_points' => $reward_point,
                'num_connections' => $num_connections,
            ],
        ];
        return $this->view->render($response, 'referral-codes.twig', $vars);
    }

    function create(Request $request, Response $response, $args)
    {
        $output = array();
        $output["error"] = false;
        $user_id = $request->getParam('user_id');
        $date_created = date('Y-m-d H:i:s');

        if (empty($user_id) || $user_id <= 0) {
            $output['error'] = true;
            $output['message'] = 'You are authorized to generate referral code.';
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }
        $code = Util::generateReferralCode();
        $operationDone = Referral::createReferral($user_id, $code, 'Active', $date_created);
        if ($operationDone["code"] == Constants::INSERT_SUCCESS) {
            $output['error'] = false;
            $output['message'] = 'Your referral code has been generated. Please share this code to someone you know.';
        } else {
            $output['error'] = true;
            $output['message'] = 'There was an error generating referral code. Please try again.';
        }
        $jsonData = json_encode($output);
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->getBody()->write($jsonData);
    }
    function my_connections(Request $request, Response $response, $args)
    {
        $data = Referral::getMyConnections($_SESSION["userID"]);

        $thisUser = User::find($_SESSION["userID"]);
        $month = date("F", strtotime($thisUser["date_created"]));
        $this_date = new DateTime($thisUser["date_created"]);
        $day = $this_date->format('d');
        $year = $this_date->format('Y');
        $date_joined = $day . " " . $month . " " . $year;
        $ref_user_id = User::whoReferedThis($_SESSION["userID"]);
        $referal_name = "";
        if ($ref_user_id > 0) {
            $referal_name = User::getNameByID($ref_user_id);
        }
        //$referal_cons = $referCRUD->getNumMyConnections($_SESSION["userID"]);

        //$data = $jobCRUD->getAlljobsForAdmin();
        $custom_data = array();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["id"] = $row["id"];
                $tmp["first_name"] = $row["first_name"];
                $tmp["last_name"] = $row["last_name"];
                $tmp["referral_code"] = $row["referral_code"];
                //$tmp["date_created"] = $row["date_created"];
                //$tmp["user_name"] = $row["user_name"];
                $tmp["date_created"] = Util::getFormalDate($row["date_created"]);
                $custom_data[] = $tmp;
            }
        }

        $vars = [
            'page' => [
                'title' => 'My Connections',
                'description' => 'List of Connections made via Referral Codes',
                'data' => $custom_data,
                'referal_name' => $referal_name,
                'date_joined' => $date_joined
            ],
        ];
        return $this->view->render($response, 'my-referrals.twig', $vars);
    }
    function reward_points(Request $request, Response $response, $args)
    {

        $adminMode = false;
        $data = RewardPoint::getAllMyRewardPoints($_SESSION["userID"]);
        $custom_data = array();
        $points_summary = array();
        $router = $this->container->get("router");

        /********** SERVER SESSION CHECK  ***********/
        if (isset($_SESSION["userID"]) && isset($_SESSION["email"]) && isset($_SESSION["api_key"])) {
            $thisUser = User::getUserByAPIKey($_SESSION["api_key"]);
            if ($thisUser != null && $thisUser["id"] == 1 && $thisUser["role_id"] == 1) {
                $data = RewardPoint::getAllRewardPoints();
                $points_summary = RewardPoint::getRewardPointsSummary();
                $adminMode = true;
            }
        } else {
            $uri = $request->getUri()->withPath($router->pathFor('login'));
            return $response->withRedirect((string) $uri);
        }
        /********** SERVER SESSION CHECK  ***********/


        if (count($data) > 0) {
            foreach ($data as $row) {
                $tmp = array();
                $tmp["note"] = $row["note"];
                $tmp["points"] = $row["points"];
                $tmp["username"] = User::getNameByID($row["user_id"]);
                $tmp["date_created"] = Util::getFormalDate($row["date_created"]);
                //$tmp["transaction_type"] = $row["transaction_type"];
                //$tmp["total_redeems"] = $referCRUD->getNumRedeems($row["code"]);
                array_push($custom_data, $tmp);
            }
        }

        $vars = [
            'page' => [
                'title' => 'My Reward Points',
                'description' => 'List of Bonus Points',
                'data' => $custom_data,
                'adminMode' => $adminMode
            ],
        ];
        return $this->view->render($response, 'reward_points.twig', $vars);
    }

    function assign_reward_points(Request $request, Response $response, $args)
    {
        $listUsers = User::all();
        $title = 'Grant Reward Points';
        $vars = [
            'page' => [
                'title' => $title,
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'listUsers' => $listUsers
            ]
        ];
        return $this->view->render($response, 'assign-reward-point.twig', $vars);
    }
    function grant_reward(Request $request, Response $response, $args)
    {

        $output = array();
        $output["error"] = false;
        $user_id = $request->getParam('user_id');
        $points = $request->getParam('points');
        $note = $request->getParam('note');
        $transaction_type = "Manual";
        $date_created = date('Y-m-d H:i:s');

        $status = "Completed";



        /********** SERVER SESSION CHECK  ***********/
        if (isset($_SESSION["userID"]) && isset($_SESSION["email"]) && isset($_SESSION["api_key"])) {
            $thisUser = User::getUserByAPIKey($_SESSION["api_key"]);
            if ($thisUser != null && $thisUser["id"] == 1 && $thisUser["role_id"] == 1) {
            } else {
                $output['error'] = true;
                $output['message'] = 'You are authorized to perform this action.';
                $jsonData = json_encode($output);
                $response = $response->withHeader('Content-Type', 'application/json');
                return $response->getBody()->write($jsonData);
            }
        } else {
            $output['error'] = true;
            $output['message'] = 'You are authorized to perform this action.';
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }
        /********** SERVER SESSION CHECK  ***********/


        if (empty($user_id) || $user_id <= 0) {
            $output['error'] = true;
            $output['message'] = 'You must select a user account.';
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        if (empty($points) || $points <= 0) {
            $output['error'] = true;
            $output['message'] = 'Reward point must be greater than zero.';
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }

        //$userName = $thisUser["first_name"]."". $thisUser["last_name"];
        $userName = User::getNameByID($user_id);
        $operationDone = RewardPoint::createRewardPoint([$user_id, $points, $transaction_type, $note, $status, $date_created]);
        if ($operationDone["code"] == Constants::INSERT_SUCCESS) {
            $output['error'] = false;
            $output['message'] = 'Reward points have been credited to ' . $userName . ' successfully.';
        } else {
            $output['error'] = true;
            $output['message'] = 'There was an error granting reward points. Please try again.';
        }
        $jsonData = json_encode($output);
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->getBody()->write($jsonData);
    }
}
