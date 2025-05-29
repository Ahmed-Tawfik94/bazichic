<?php

namespace App\Controllers;

use App\Helpers\Constants;
use App\Helpers\Helpers;
use App\Models\Plan;
use App\Models\RewardPoint;
use App\Models\User;
use App\Service\stripe\StripeService;
use App\Service\stripe\SubscriptionService;
use Carbon\Carbon;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Stripe\Exception\ApiErrorException;

class LoginController extends BaseController
{
    protected SubscriptionService $subscription;
    public function __construct($container,SubscriptionService $subscriptionService)
    {
        parent::__construct($container);
        $this->subscription=$subscriptionService;

    }
    public function index(Request $request, Response $response, $args)
    {
        $helper = new Helpers();
        if ($helper->validateSession()) {
            $uri = $request->getUri()->withPath($this->container->get('router')->pathFor('dashboard'));
            return $response->withRedirect((string)$uri);
        } else {
            // session_destroy();
            // session_unset();
            $vars = [
                'page' => [
                    'title' => 'Login | BaziChic - Chinese Metaphysics Consultancy',
                    'description' => 'Login using your email and password.'
                ],
            ];

            return $this->view->render($response, 'login.twig', $vars);
        }
    }

    public function logout(Request $request, Response $response, $args)
    {
        session_destroy();
        session_unset();
        $vars = [
            'page' => [
                'title' => 'Login | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Login  to Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics.'
            ],
        ];

        $uri = $request->getUri()->withPath($this->container->get('router')->pathFor('home'));
        return $response->withRedirect((string)$uri);

    }

    /**
     * @throws ApiErrorException
     */
    public function login(Request $request, Response $response, $args)
    {
        $email = $request->getParam('email');
        $password = $request->getParam('password');
        $output = array();
        $current_user = User::getByEmail($email);
        if (!$current_user) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'User not found! please check your email and password'], 404);
        }
        if ((int)$current_user->status_id !== 1) {
            return $this->jsonResponse($response, ['error' => true, 'message' => 'Your account is not verified yet.'], 400);
        }
        $date_created = date('Y-m-d H:i:s');
        // check for correct email and password
        if (!User::checkLogin($email, $password)) {
            // user credentials are wrong
            return $this->jsonResponse($response, ['error' => true, 'message' => 'Login failed. Looks like either email or password you entered is incorrect.'], 400);
        } else {
            $current_user_id = $current_user->id;
            User::updateLastActive($current_user_id, $date_created);
            /*********************************/
            //session_start();
            $stripe = new StripeService();
            if (!$current_user->stripe_customer_id){
                $stipe_customer =$stripe->createCustomer((object)[
                    'email'=>$current_user->email,
                    'name'=>$current_user->first_name.' '.$current_user->last_name
                ]);
                $current_user->stripe_customer_id = $stipe_customer->id;
                $current_user->save();
            }
            if($current_user->subscription){
                if(!$current_user->subscription->stripe_subscription_id && Carbon::parse($current_user->subscription->end_date)->isFuture()){

                    $subscribed_plan = Plan::find($current_user->subscription->membership_plan_id)->first();
                    $subscription_data = $this->subscription->createSubscription((object)[
                       'stripe_customer_id'=>$current_user->stripe_customer_id,
                       'stripe_price_id'=>$subscribed_plan->stripe_price_id,
                        'timestamp'=>Carbon::parse($current_user->subscription->end_date)->timestamp
                    ]);
                    if( $subscription_data['code'] === Constants::INSERT_SUCCESS){
                        $current_user->subscription->stripe_subscription_id=$subscription_data['data']->id;
                        $current_user->subscription->price_id = $subscribed_plan->stripe_price_id;
                        $current_user->subscription->save();
                    }
                }

            }
            $_SESSION['app'] = "bazichic";
            $_SESSION['userID'] = $current_user->id;
            $_SESSION['first_name'] = $current_user->first_name;
            $_SESSION['last_name'] = $current_user->last_name;
            $_SESSION['role_id'] = $current_user->role_id;
            $_SESSION['email'] = $current_user->email;
            $_SESSION['status_id'] = $current_user->status_id;
            $_SESSION['api_key'] = $current_user->api_key;
            $_SESSION['user_name'] = $current_user->user_name;
            $_SESSION['user_image'] = $current_user->user_image;
            // Fetch referral code from reward_points table
            $rewardPoint = RewardPoint::where('user_id', $current_user->id)
            ->where('transaction_type', 'Referral')
            ->first();

            $_SESSION['referral_code'] = $rewardPoint ? $rewardPoint->referral_code : null;

            $output['info'] = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
            $output['error'] = false;
            $output['id'] = $current_user->id;
            $output['username'] = $current_user->user_name;
            $output['message'] = 'Welcome back ' . $_SESSION['first_name'] . '! Please wait while we are taking you to your dashboard.';
            $output['redirection'] =  null;
        }

        $currentActivePlans = $current_user->subscription()->exists();
        $output['active'] = $currentActivePlans;
        if (!$currentActivePlans) {
                $output['redirection'] =User::isAdmin($current_user->id) ? $this->router->pathFor('admin-panel'): $this->router->pathFor('subscription-plans');
        }
        if (isset($_SESSION['last_visited'])) {
            $output['redirection'] = $_SESSION["last_visited"]->getPath();
        }


       return $this->jsonResponse($response, $output, 200);

        // return $this->view->render($response, 'partials-dashboard.twig',$vars);
    }
}
