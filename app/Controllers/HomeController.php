<?php

namespace App\Controllers;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\Helpers;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Document;
use App\Models\PaymentStripe;
use App\Models\Plan;
use App\Models\SiteSetting;
use App\Models\User;
use function PHPSTORM_META\map;

// Added for Slim 4
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use Slim\Interfaces\RouteParserInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

class HomeController extends BaseController
{
    // Constructor matching the new BaseController signature
    public function __construct(
        ContainerInterface $container,
        Twig $twig,
        Capsule $db,
        RouteParserInterface $routeParser,
        LoggerInterface $logger
    ) {
        parent::__construct($container, $twig, $db, $routeParser, $logger);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $site= new SiteSetting();
        $data = Document::getAllDocuments(0); // Assuming is_published = 0 means all for this context
        $helper=new Helpers();
        
        $membership_plans = Plan::where('is_available', 1)->get()->groupBy('interval')->map(function ($plans) {
            return $plans->map(function ($plan) {
                // Use $this->routeParser from BaseController
                $plan->url = $this->routeParser->urlFor('get-membership', ['type' => $plan->id]);
                return $plan;
            });
        });

        $categories = Category::getAllCategories(1);
        //Get All E-Books
        $ebooks = Document::getAllDocumentsByDocType(1);
        $custom_data = array();
        if (count($ebooks) > 0) {
            foreach ($ebooks as $row) {
                $tmp =$helper-> getDocDetails($row["id"] ,$this->db);
                array_push($custom_data, $tmp);
            }
        }

        //Get All Magazines
        $allMagazinesArr = Document::getAllDocumentsByDocType(3);
        $allMagazines = array();
        if (count($allMagazinesArr) > 0) {
            foreach ($allMagazinesArr as $row) {
                $tmp =$helper->  getDocDetails($row["id"],$this->db);
                array_push($allMagazines, $tmp);
            }
        }

        //Get All Latest Stuffs
        $latest_docs_arr = Document::getAllLatestLiveDocuments();
        $latest_docs = array();
        if (count($latest_docs_arr) > 0) {
            foreach ($latest_docs_arr as $row) {
                $tmp =$helper->  getDocDetails($row["id"],$this->db);
                array_push($latest_docs, $tmp);
            }
        }
//        foreach($membership_plans as $plan){
//
//            $plan['price'] = number_format($plan['price'], 2);
//        }
        $vars = [
            'page' => [
                'title' => 'BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'allMagazines' => $allMagazines,
                'latest_docs' => $latest_docs,
                'ebooks' => $custom_data,
                'membership_plans' => $membership_plans,
                // 'testimonials' => $testimonials,
                'categories' => $categories,
                'banner_link' => $site->getFrontBannerLink()
            ],
        ];
        // Use $this->twig from BaseController (which is Slim\Views\Twig instance)
        return $this->twig->render($response, 'home.twig', $vars);
    }

    function about(Request $request, Response $response, array $args): Response
    {
        $vars = [
            'page' => [
                'title' => 'About Us | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics'
            ],
        ];
        return $this->twig->render($response, 'about.twig', $vars);
    }

    function testimonials(Request $request, Response $response, array $args): Response
    {
        // $testimonials = $docCRUD->getAllTestimonials();
        $vars = [
            'page' => [
                'title' => 'What People Say',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                // 'testimonials' => $testimonials
            ],
        ];
        return $this->twig->render($response, 'testimonials.twig', $vars);
    }

    function contact(Request $request, Response $response, array $args): Response
    {
         $siteKey = $_ENV['RECAPTCHA_PUBLIC_KEY']; 
        // $capcha = recaptcha_get_html($publickey);
        $help_topics=["I need technical help",
        "I need help on refunds",
        "I need help with payments",
        "I have a suggestion",
        "Request for a Bazi and QMDJ consultation quotation",
        "Request for a Feng Shui audit quotation",
        "Request for a Date Selection quotation",
        "I have a suggestion"];
        $secret = "6Le4KJsqAAAAALV3i95zZq7fPmlbegEs7mrfFuo0";
        $settings =[
            'phone'=>'+6011 6326 1781',
            'email'=>'customer_support@bazichic.com',
            'address'=>'BaziChic Chinese Metaphysics Consultany Taman Sri Rampai, 53300',
            'linkedin'=>'#',
        ];
        $vars = [
            'page' => [
                'title' => 'Contact Us | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                 'siteKey' => $siteKey,
                'help_topics' => $help_topics,
            ],
                'settings' => $settings
        ];
        return $this->twig->render($response, 'contact.twig', $vars);
    }

    function contactSubmit(Request $request, Response $response, array $args): Response
    {
        $params = (array)$request->getParsedBody(); // Ensure it's an array
        $recaptchaResponse = $params['g-recaptcha-response'] ?? null;
        $name = $params['name'];
        $email = $params['email'];
        $message = $params['comments'];
        $subject = $params['subject'];
        $date_created = date('Y-m-d H:i:s');

        if (empty($name)) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'Please enter your full name.'], 400);
        }
        if (empty($email)) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'Please enter a valid email address.'], 400);
        }
        if (empty($message)) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'You must enter your message.'], 400);
        }
        if (empty($subject)) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'You must enter a subject.'], 400);
        }

        // Verify the response with Google's API
        $secretKey =$_ENV['RECAPTCHA_SECRET_KEY']; // Replace with your actual secret key
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ]
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $verification = json_decode($result, true);


        if ($verification['success']) {
            $res = Contact::createContact((object)[
                "name" => $name,
                "email" => $email,
                "user_id"=>User::where("role_id",1)->first()->id,
                "subject" => $subject,
                "message" => $message
            ]);
             $res = $this->CustomerReport((object)[
                 "name" => $name,
                 "email" => $email,
                 "subject" => $subject,
                 "message" => $message
             ]);
            if ($res['status'] == 'success') {
                return $this->jsonResponse($response, ['error' => false, 'message' => 'Form submitted successfully.'], 200);

            } else {
                return $this->jsonResponse($response,['error'=>true,'message'=>"Failed to send message. Please try again."],400);
            }
//            return $this->jsonResponse($response, ['error' => false, 'message' => 'Form submitted successfully.'], 200);
        } else {
            // Validation failed
            return $this->jsonResponse($response, ['error' => true, 'message' => 'reCAPTCHA validation failed. Please try again.'], 400);
        }


    }
    public function ComingSoon(Request $request, Response $response, array $args): Response
    {
        $helper = new Helpers();
        // $router = $this->container->get('router'); // Use $this->routeParser
        if ($helper->isMaintenanceModeOn($this->db)) { // Assuming isMaintenanceModeOn is static or $helper has $db
            $vars = [
                'page' => [
                    'title' => 'Coming Soon | Bazichic - Chinese Metaphysics Consultancy',
                    'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics'
                ],
            ];
            return $this->twig->render($response, 'coming-soon.html', $vars);
        } else {
            $url = $this->routeParser->urlFor('notFound'); // Use 'notFound' as defined in web.php
            return $response->withHeader('Location', $url)->withStatus(302);
        }
    }

    public function CustomerReport(object $data): array
    {
        $helper = new Helpers();
        $userEmail = $data->email;
        $subject = "Bazichic Customer Report";
        // Use $this->twig from BaseController
        $template = $this->twig->getEnvironment()->render('email/customer_report.twig', [
            "customerName"=>$data->name,
            "customerEmail"=>$data->email,
            "ReportTopic"=>$data->subject,
            "issueMessage"=>$data->message,
            "reportDate"=>date('Y-m-d H:i:s'),
        ]);
        return $helper->sendContact($userEmail, $subject, $template);
    }
}
