<?php

namespace App\Controllers;
use App\Models\DocumentType;
use Carbon\Carbon;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\Helpers;
use App\Models\Category;
use App\Models\DocumentLike;
use App\Models\Document;
use App\Models\DocumentReview;
use App\Models\DocumentSave;
use App\Models\DocumentViews;
use App\Models\Plan;
use App\Models\User;
use App\Models\Util;
use Exception;
use DateTime;

class EbookController extends BaseController
{
    protected  $MAGAZINE;
    protected $EBOOK;
    protected $AUDIOBOOK;
    protected $STARTER;
    protected $PREMIUM;
    protected $STUDENT;
    function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->STUDENT = Plan::where('name', 'Students')->first();
        $this->STARTER = Plan::where('name', 'Starter')->first();
        $this->PREMIUM = Plan::where('name', 'Premium')->first();
        $this->EBOOK = DocumentType::where('url_name', 'e-books')->first();
        $this->MAGAZINE = DocumentType::where('url_name', 'magazines')->first();
        $this->AUDIOBOOK = DocumentType::where('url_name', 'audio-books')->first();

        $this->STUDENT = $this->STUDENT ? $this->STUDENT->id : null;
        $this->STARTER = $this->STARTER ? $this->STARTER->id : null;
        $this->PREMIUM = $this->PREMIUM ? $this->PREMIUM->id : null;
        $this->EBOOK = $this->EBOOK ? $this->EBOOK->id : null;
        $this->MAGAZINE = $this->MAGAZINE ? $this->MAGAZINE->id : null;
        $this->AUDIOBOOK = $this->AUDIOBOOK ? $this->AUDIOBOOK->id : null;
    }

    public function index(Request $request, Response $response, $args)
    {
        $status = 1;
        $filters = $request->getQueryParams();
        $customInfo = [];
        $sqlErrors = [];
        $static_doctype = [];
        $static_cats = [];

        /****************** DETERMINE USER TYPE *****************/
        $user_id = $_SESSION['userID'] ?? 'guest';
        $current_user = $user_id ? User::find($user_id) : null;
        $user_subs = $current_user ? $current_user->subscription : null;
        $doc_types = DocumentType::all(); // Get all document types
        foreach ($doc_types as $doc_type) {// Loop through the document types
            $doc_type->allowed = true;

            if ($user_subs && $user_subs->membership_plan_id == $this->STARTER) {  // Check for the Starter plan
                if ($doc_type->id != $this->MAGAZINE) {  // Check if the current document type is MAGAZINE
                    $doc_type->allowed = false;  // Allow this document type for the user
                }
            }
        }

        $allowed_doc_types = ["Magazine", "E-Book", "Audio Book"]; // Default for Premium/Guest

        // Default display settings
        $doc_type_id = 0;
        $doc_type = $filters['document_type'] ?? 'all';
        $display_type = "Document";

        if ($user_subs) {
            if ($user_subs->membership_plan_id == $this->STARTER) {
                $doc_type_id = $this->MAGAZINE;
                $doc_type = "magazine";
                $display_type = "Magazine";
                $allowed_doc_types = ["Magazine"]; // Restrict Starter to Magazines
            }
        }

        /****************** PAGINATION LOGIC *****************/
        $page = (int)($filters['page'] ?? 1);  // Get the page, default to 1
        $limit = (int)($filters['limit'] ?? 10);  // Get the limit, default to 10
        $offset = ($page - 1) * $limit;  // Calculate the offset

        /****************** QUERY DOCUMENTS *****************/
        $data = Document::where('is_published', $status);

        try {
            // Apply search filter
            if (!empty($filters['search_item'])) {
                $searchItem = $filters['search_item'];
                $customInfo[] = "Search for: $searchItem";
                $data = $data->where('title', 'like', "%$searchItem%");
            }

            if (!empty($filters['document_type']) && is_string($filters['document_type'])) {
                if ($user_subs && $user_subs->membership_plan_id == $this->STARTER) {
                    // Starter users cannot filter by document type, only category
                    $customInfo[] = "Starter Plan: Ignoring document type filter";
                } else {
                    $documentType = $filters['document_type'];
                    if ($doc_types->where('id',$documentType)->first() || $documentType ==='all') {
                        $customInfo[] = "Doc Type: " . $documentType;
                        $static_doctype = $documentType;
                        $data = $data->where('documentType', $documentType);
                    }
                }
            }

            // Apply category filter
            if (!empty($filters['categories']) && is_string($filters['categories'])) {
                $category = $filters['categories'];
                $customInfo[] = "Category: " . $category;
                $static_cats = (int)$category;
                $data = $data->where('category_id', $category);
            }

            // Restrict Starter Users to Magazines
            if ($user_subs && $user_subs->membership_plan_id == $this->STARTER) {
                $data = $data->where('documentType', $this->MAGAZINE);
            }

            // Apply Pagination to the Query
            $totalCount = $data->count();  // Get the total number of records
            $data = $data->skip($offset)->take($limit)->orderBy('id', 'DESC')->get();

        } catch (Exception $e) {
            $sqlErrors[] = $e->getMessage();
            error_log("Filter Error: " . $e->getMessage());
        }

        // Debugging info (optional)
        if (!empty($customInfo)) {
            error_log("Filters Applied: " . implode(" | ", $customInfo));
        }

        /****************** CATEGORIES FILTER *****************/
        $categories = Category::where('is_published', 1);

        if ($user_subs && $user_subs->membership_plan_id == $this->STARTER) {
            $categories = $categories->where('magazine_only', 1);
        }

        $categories = $categories->orderBy('id', 'DESC')->get();

        /****************** FORMAT RESPONSE DATA *****************/
        $custom_data = [];
        if (count($data) > 0) {
            foreach ($data as $row) {
                $custom_data[] = [
                    "id" => $row->id,
                    "title" => $row->title,
                    "qcode" => $row->qcode,
                    "desc" => $row->description,
                    "avg_rating" => DocumentReview::getAvgReviewsFor($row->id),
                    "num_reviews" => DocumentReview::getNumReviewsFor($row->id),
                    "num_likes" => DocumentLike::getNumLikes($row->id),
                    "cover" => $row->cover,
                    "is_liked" => DocumentLike::isLikedBy($user_id ?? 'guest', $row->id) ?? false
                ];
            }
        }

        /****************** PAGINATION INFORMATION *****************/
        $totalPages = ceil($totalCount / $limit);  // Calculate total pages

        /****************** RENDER PAGE *****************/
        $vars = [
            'page' => [
                'title' => 'E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'description' => 'Find E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'data' => $custom_data,
                'categories' => $categories,
                'display_type' => $display_type,
                'documentType' => $static_doctype,
                'doc_type_id' => $doc_type_id,
                'selected_categories' => $static_cats,
                'carosell_view' => true,
                'doc_types' => $doc_types,
                'current_page' => $page,
                'total_pages' => $totalPages,
                'limit' => $limit,
            ],
        ];

        return $this->view->render($response, 'e-book-store.twig', $vars);
    }

    public function ebookById(Request $request, Response $response, $args)
    {


        $docQCode = $request->getAttribute('id');
        if (!Document::isQCodeExists($docQCode)) {
            $uri = $request->getUri()->withPath($this->container->get('router')->pathFor('notFound'));
            return $response->withRedirect((string) $uri);
        }
        $_SESSION["last_saved"] = $_SERVER['REQUEST_URI'];
        $docID = Document::getIDByQCode($docQCode);
        $document = Document::getID($docID);
        $document_audio =null;
        if($document->audio){
        $document_audio =$document->audio->file;
        }
        $doc_type = "";
        $unlock_tip = "Start reading anything. anywhere on Bazichic.";
        $unlock_title = "READ DOCUMENT";
        if ($document !== NULL) {
            $name = $document->title;
            $doc_type_selected = $document->documentType;
            $docs_types_names= DocumentType::find($document->documentType);
            switch (strtolower($docs_types_names->title)) {
                case 'magazine':
                    $doc_type = "Magazine";
                    $unlock_title = "Read Magazine";
                    $unlock_tip = "Click here to read this Magazine now.";
                    break;
                case 'e-book':
                    $doc_type = "E-Book";
                    $unlock_title = "Read E-Book";
                    $unlock_tip = "Click here to start reading this E-book now.";
                    break;
                case 'audio-book':
                    $doc_type = "Audio Book";
                    $unlock_title = "Listen Audio Book";
                    $unlock_tip = "Click here to start listening this audio book now.";
                    break;

            }
            $is_liked = false;
            $is_saved = false;
            $is_reviewed = false;
            $my_review = array();
            $membership_info = "";
            $user_image =null;
            if(isset($_SESSION['userID'])){
                $user_id = $_SESSION["userID"];
                $is_liked = DocumentLike::isLikedBy($user_id, $docID);
                $is_saved = DocumentSave::isSavedBy($user_id, $docID);
                $is_reviewed = DocumentReview::isReviewedBy($user_id, $docID);

                //Add viewer analytics
                DocumentViews::addDocumentView($docID, $user_id);

                if ($is_reviewed) {
                    $my_review_arr = DocumentReview::getMyReview($user_id, $docID);
                    $my_review["id"] = $my_review_arr["id"];
                    $my_review["date_created"] = $my_review_arr["date_created"];
                    $my_review["text"] = $my_review_arr["text"];
                    $my_review["stars"] = $my_review_arr["stars"];
                }

                $current_user= User::find($_SESSION['userID']);
                $user_subs = $current_user->subscription;
                    if(!$user_subs){
                        return $response->withRedirect($this->container->get('router')->pathFor('subscription-plans'));
                    }
                if (!User::isAdmin($current_user->id)){

                $user_image = User::getUserImageByID($current_user->user_id);
                if($user_subs->where('status','trialing')->count() > 0){
                $is_membership_active = false;
                $targetDate =  Carbon::parse($user_subs->trial_end_date)->format('Y-m-d H:i:s');
                $currentDate = Carbon::now()->format('Y-m-d H:i:s');
                $free_trial_period = ($targetDate > $currentDate);
                $date_expiring_display = Util::getFormalDate($user_subs->trial_end_date);
                $membership_info = "Your membership is in trial till " . $date_expiring_display . ". Enjoy full access to all magazines on Bazichic.";
                }
                /**************** GET MY ACTIVE MEMBERSHIP ******************/
                if ($user_subs->where('status','active')->count() > 0) {
                        $is_membership_active = true;
                        //$row = Subscriptions::getMyActivePlan($_SESSION["userID"]);
                        $active_plan = array();
                        $active_plan["id"] = $user_subs->id;
                        $activePlanID =  $user_subs->membership_plan_id;
                        $active_plan["title"] = Plan::getNameByID($user_subs->membership_plan_id);
                        $active_plan["date_expiring_display"] = Util::getFormalDate($user_subs->end_date);

                        switch ($activePlanID) {
                            case $this->STARTER:
                                $membership_info = "Your membership is active till " . $active_plan["date_expiring_display"] . ". Enjoy full access to all magazines on Bazichic.";
                                break;

                            case $this->PREMIUM:
                                $membership_info = "Your membership is active till " . $active_plan["date_expiring_display"] . ". Enjoy full access to all e-books and magazines on Bazichic.";
                                break;

                            case $this->STUDENT:
                                $membership_info = "Your membership is active till " . $active_plan["date_expiring_display"] . ". Enjoy full access to all e-books, magazines and courses on Bazichic.";
                                break;
                        }


                }
                /**************** GET MY ACTIVE MEMBERSHIP ******************/
                }

            }



            $all_reviews = array();
            $all_reviews_array = DocumentReview::getReviewsFor($document["id"]);
            if (count($all_reviews_array) > 0) {
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
                    'og_url' => $baseUrl . "/e-book-reader/book-detail/" . $document["qcode"],
                    'unlock_title' => $unlock_title,
                    'unlock_tip' => $unlock_tip,
                    'free_trial_period' => $free_trial_period ?? false,
                    'is_membership_active' => $is_membership_active ?? false,
                    'membership_info' => $membership_info,
                    'active_plan' => $active_plan ?? null,
                    'user_image'=>$user_image,
                ],
                'document' => [
                    'id' => $docID,
                    'title' => $document["title"],
                    'description' => $document["description"],
                    'category_id' => $document["category_id"],
                    'cover' => $document["cover"],
                    'qcode' => $document["qcode"],
                    'category' => Category::getNameByID($document["category_id"]),
                    // 'keywords' => $keyword_list,
                    'audio_file' => $document_audio ?? null,
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
                    'date_created' => Util::getTimeDifference($document["date_created"]),
                    'num_pages' => $document["num_pages"],
                    'all_reviews' => $all_reviews,
                    'avg_rating' => DocumentReview::getAvgReviewsFor($document["id"]),
                    'num_reviews' => DocumentReview::getNumReviewsFor($document["id"]),
                    'num_likes' => DocumentLike::getNumLikes($document["id"]),
                    'num_saves' => DocumentSave::getNumSaves($document["id"]),
                    'is_liked' => $is_liked,
                    'is_saved' => $is_saved,
                    'is_reviewed' => $is_reviewed,
                    'my_review' => $my_review
                ]
            ];
        }
        return $this->view->render($response, 'book-detail.twig', $vars);
    }
    public function ebookReader(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();
        $router = $this->router;
        $doc_id = $params['doc_id'];
        $doc_link = $params['doc_link'];
        $is_downloadable = $params['is_downloadable'];

        if (!Document::isIDExists($doc_id)) {
            $uri = $request->getUri()->withPath($router->pathFor('notFound'));
            return $response
                ->withHeader('Location', (string) $uri)
                ->withStatus(302);
        }


        //Get Doc Type too
        $target_doc = Document::find($doc_id);
        $docTypeID = $target_doc->documentType;
//        $docTypeName = DocumentType::find($docTypeID)->title;
        $allowReading = true;
        $isTrialPeriodOn = 0;
        $trialMessage = "";
        $userMessage = "Looks like you are not authorized to access this content. ";

        $thisUser = User::getUserByAPIKey($_SESSION["api_key"]);
        if (!$thisUser) {
            $uri = $request->getUri()->withPath($router->pathFor('login'));
            return $response
                ->withHeader('Location', (string) $uri)
                ->withStatus(302);
        }
        /*********************/

        switch($thisUser->role_id) {
            /**************************************/
            //        check if user has subscription
            /**** First Check Subscription  *****/
            case 1:
                $userMessage = "You are viewing this as Admin.";

                break;
            default:
            $subscription = $thisUser->subscription;
                if (!$subscription) {
                    $uri = $request->getUri()->withPath($router->pathFor('subscription-plans'));
                    return $response
                        ->withHeader('Location', (string) $uri)
                        ->withStatus(302);
                }
                if ($subscription->count() > 0) {
                    $PlanName = Plan::getNameByID($subscription->membership_plan_id);
                    switch ($subscription->status) {
                        case 'active':
                            $activeExpiry = Util::getFormalDate($subscription->end_date);
                            $userMessage = "Your " . $PlanName . " Subscription is activated till " . $activeExpiry . ".";
                            if ($docTypeID == 2 && $subscription->membership_plan_id !== 2) {
                                $allowReading = false;
                                $userMessage = "You need a Premium Plan to read E-Books.";
                            }
                            break;
                        case 'trialing':
                            $isTrialPeriodOn = 1;
                            $activeTrialExpiry = Util::getFormalDate($subscription->trail_end_date);
                            $userMessage = "You are on a 10 Days Free Trial for " . $PlanName . " Subscription. Your trial ends on " . $activeTrialExpiry . ".";
                            $trialMessage = $userMessage;
                            if ($docTypeID !== $this->MAGAZINE && $subscription->membership_plan_id === $this->STARTER) {
                                $allowReading = false;
                                $userMessage = "You need a Premium or Student Plan to read E-Books.";
                            }
                    }

                }

                break;
            /**** End of Subscription ***********************/
        }
        //If Doc is saved get the page number
        $openPage = DocumentSave::getLastSavedPage($thisUser->id, $doc_id);
        if (DocumentSave::getLastSavedPage($_SESSION["userID"], $doc_id) > 0) {
            $openPage = DocumentSave::getLastSavedPage($_SESSION["userID"], $doc_id);
        }

        $vars = [
            'page' => [
                'title' => 'E-Book Reader | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'doc_id' => $doc_id,
                'doc_link' => $doc_link,
                'is_downloadable' => $is_downloadable,
                'allowReading' => $allowReading,
                'userMessage' => $userMessage,
                'isTrialPeriodOn' => $isTrialPeriodOn,
                'trialMessage' => $trialMessage,
                'openPage' => $openPage
            ]
        ];
        return $this->view->render($response, 'ebook-reader.twig', $vars);
    }

    public function Filter(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();
        $document_type = $params['documentType'];
        $search_item = $params['search_item'];

        $vars = [
            'page' => [
                'title' => 'E-Book Reader | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                // 'doc_id' => $doc_id,
                // 'doc_link' => $doc_link,
                // 'is_downloadable' => $is_downloadable,
                // 'allowReading' => $allowReading,
                // 'userMessage' => $userMessage
            ],
        ];
        return $this->view->render($response, 'e-book-store.twig', $vars);
    }


    public function Read4Free(Request $request, Response $response, $args)
    {
        $data = Document::getAllFreeEBooks();
        $vars = [
            'page' => [
                'title' => 'Read E-Books Online for Free | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'data' => $data
            ],
        ];
        return $this->view->render($response, 'read-e-books-online-for-free.twig', $vars);

    }

    public function systemConfiguration(Request $request, Response $response, $args)
    {

    }

    public function Uploadsettings(Request $request, Response $response, $args)
    {

    }

    public function viewProfile(Request $request, Response $response, $args)
    {

    }
    public function FreeTrialsSummary(Request $request, Response $response, $args)
    {

    }
    public function GrantTrial(Request $request, Response $response, $args)
    {

    }
    function Timeline(Request $request, Response $response, $args)
    {

    }
    function ViewContactSubmision(Request $request, Response $response, $args)
    {

    }
}
