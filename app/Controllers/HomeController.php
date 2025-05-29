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

class HomeController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        $site= new SiteSetting();
//        if (isset($_SESSION['userID'])) {
//            $url= $this->container->get('router')->pathFor('dashboard');
//            return $response->withHeader('Location', $url);
//        }
        $data = Document::getAllDocuments(0);
        $helper=new Helpers();
        // $testimonials = $docCRUD->getAllTestimonials();
        // $membership_plans = Plan::getAllActivePlans();
        $membership_plans = Plan::where('is_available', 1)->get()->groupBy('interval')->map(function ($plans) {
            return $plans->map(function ($plan) {
                $plan->url = $this->router->pathFor('get-membership', ['type' => $plan->id]);
                return $plan;
            });
        });

//        foreach ($membership_plans as $interval => $plans) {
//            var_dump($interval);
//            foreach ($plans as $plan) {
//                $plan->url = $this->router->pathFor('get-membership', ['type' => $plan->stripe_product_id]);
//            }
//        }
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
        return $this->view->render($response, 'home.twig', $vars);

    }
    function about(Request $request, Response $response, $args)
    {
        $vars = [
            'page' => [
                'title' => 'About Us | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics'
            ],
        ];

        return $this->view->render($response, 'about.twig', $vars);
    }
    function testimonials(Request $request, Response $response, $args)
    {
        // $testimonials = $docCRUD->getAllTestimonials();
        $vars = [
            'page' => [
                'title' => 'What People Say',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                // 'testimonials' => $testimonials
            ],
        ];
        return $this->view->render($response, 'testimonials.twig', $vars);
    }
    function contact(Request $request, Response $response, $args)
    {
        // require_once('recaptcha/recaptchalib.php');
         $siteKey = $_ENV['RECAPTCHA_PUBLIC_KEY']; // you got this from the signup page
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
        return $this->view->render($response, 'contact.twig', $vars);
    }
    function contactSubmit(Request $request, Response $response, $args)
    {
        $params = $request->getParsedBody();
        $recaptchaResponse = $params['g-recaptcha-response'];
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
    public function ComingSoon(Request $request, Response $response, array $args)
    {
        $helper = new Helpers();
        $router = $this->container->get('router');
        if ($helper->isMaintenanceModeOn($this->db)) {
            $vars = [
                'page' => [
                    'title' => 'Coming Soon | Bazichic - Chinese Metaphysics Consultancy',
                    'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics'
                ],
            ];
            return $this->view->render($response, 'coming-soon.html', $vars);
        } else {
            
            $uri = $request->getUri()->withPath($this->container->get('router')->pathFor('404'));
            return $router->withRedirect((string) $uri);
        }

    }
    public function CustomerReport(object $data): array
    {
        $helper = new Helpers();
        $userEmail = $data->email;
        $subject = "Bazichic Customer Report";
        $twig = $this->TwigTemplate();
        $template = $twig->render('customer_report.twig', [
            "customerName"=>$data->name,
            "customerEmail"=>$data->email,
            "ReportTopic"=>$data->subject,
            "issueMessage"=>$data->message,
            "reportDate"=>date('Y-m-d H:i:s'),
        ]);
        return $helper->sendContact($userEmail, $subject, $template);
    }
}
