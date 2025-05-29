<?php

namespace App\Controllers;
use App\Helpers\Constants;
use App\Helpers\Helpers;
use App\Models\Invoice;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscriptions;
use App\Models\User;
use App\Models\Util;
use App\Service\stripe\ProductService;
use App\Service\stripe\StripeService;
use App\Service\stripe\SubscriptionService;
use DateTime;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Stripe\Exception\ApiErrorException;

class SubscriptionController extends BaseController
{

    protected SubscriptionService $subscription;
    protected ProductService $product;
    public function __construct(ContainerInterface $container, SubscriptionService $subscriptionService,ProductService $productService)
    {
        parent::__construct($container);
        $this->subscription=$subscriptionService;
        $this->product=$productService;

    }
    public function get(Request $request, Response $response, $args)
    {
        // get user subscriptions
            $title = "MY SUBSCRIPTIONS ";
        $statusColors = [
            'active' => [
                'background' => '#d4edda',    // Lighter green for background
                'text_color' => '#28a745',     // Green for text
                'icon_color' => '#1e7e34'      // Darker green for icon
            ],
            'trialing' => [
                'background' => '#fff3cd',    // Lighter yellow for background
                'text_color' => '#ffc107',    // Yellow for text
                'icon_color' => '#e65c00'     // Darker yellow for icon
            ],
            'canceled' => [
                'background' => '#f8d7da',    // Lighter red for background
                'text_color' => '#dc3545',    // Red for text
                'icon_color' => '#bd2130'     // Darker red for icon
            ],
            'past_due' => [
                'background' => '#ffeeba',    // Lighter orange for background
                'text_color' => '#ff6f00',    // Orange for text
                'icon_color' => '#e65c00'     // Darker orange for icon
            ],
            'unpaid' => [
                'background' => '#e2e3e5',    // Lighter gray for background
                'text_color' => '#6c757d',    // Gray for text
                'icon_color' => '#495057'     // Darker gray for icon
            ],
            'incomplete_expired' => [
                'background' => '#e2e3e5',    // Lighter gray for background
                'text_color' => '#6c757d',    // Gray for text
                'icon_color' => '#495057'     // Darker gray for icon
            ]
        ];
            /*******************************/
            $current_user=User::find($_SESSION['userID']);
            $active_subscription= $current_user->subscription ?? null;
            $active_plan = null;
            $pending_plan = null;
            $invoice_url = null;
//            $data = Plan::where('is_available',1)->get();
//            foreach ($data as $p){
//                $p['url']= $this->router->pathFor('get-membership', ['type' => $p->id]);
//            }
            $data = Plan::where('is_available', 1)->get()->groupBy('interval');

            foreach ($data as $interval => $plans) {
                foreach ($plans as $plan) {
                    $plan->url = $this->router->pathFor('get-membership', ['type' => $plan->id]);
                    $plan->is_selected = $active_subscription && $active_subscription->membership_plan_id == $plan->id;
                }
            }
            if ($active_subscription){
            $active_plan= Plan::where('id',$current_user->subscription->membership_plan_id)->first();
            // active user subscription
                if ($active_subscription->status == "active") {
                $active_plan = Plan::where('id', '=', $active_subscription->membership_plan_id)->first();
                }else{
                    $pending_plan = Plan::where('id', '=', $active_subscription->membership_plan_id)->first();

                }
                $invoice = Invoice::where('subscription_id', '=', $active_subscription->id)->first();
                $invoice &&($invoice_url= $this->router->pathFor('invoice', ['id' => $invoice->invoice_id]));
                $active_subscription->days_left = Util::dateDiffInDays($active_subscription->end_date, $active_subscription->start_date);
                $statusColor = $statusColors[$active_subscription->status];
            }

//
            /*******************************/
            $vars = [
                'page' => [
                    'name' => 'my-membership',
                    'title' => $title,
                    'description' => 'View active subscriptions on Bazichic',
                    'membership_plans' => $data,
                    'user'=>$current_user,
                    'active_plan' => $active_plan,
                    'active_subscription' => $active_subscription,
                    'pending_plan' => $pending_plan,
                    'invoice_url' => $invoice_url,
                    'statusColor'=>$statusColor ?? null
                ]
            ];
            return $this->view->render($response, 'manage-memberships.twig', $vars);

    }
    public function getAll(Request $request, Response $response, $args)
    {
            $page_content = "ALL SUBSCRIPTIONS ";
            /*******************************/
            $all_plans = array();
            $all_plans_arr = Subscriptions::all();
            //$all_plans_arr = $membershipCRUD->getAllMyPlansExcept($_SESSION["userID"], 1);
            if (count($all_plans_arr) > 0) {
                foreach ($all_plans_arr as $thisrow) {

                    $tmp = array();
                    if($thisrow->user){
                        $tmp["id"] = $thisrow->id;
                        $tmp["plan_id"] = $thisrow->membership_plan_id;
                        $tmp["title"] = Plan::getNameByID($thisrow->membership_plan_id);
                        $tmp["amount"] = Plan::where('id',$thisrow->membership_plan_id)->first()->price ?? 0;
                        $tmp["user_name"] = $thisrow->user->user_name;
                        $tmp["status"] = $thisrow->status;
                        $tmp["remark"] = $thisrow->remark;
                        $tmp["txn_id"] = Payment::where('sender_id',$thisrow->user->id)->first()? Payment::where('sender_id',$thisrow->user->id)->first()->txn_id : 'N/A';
                        $invoice = Invoice::where('user_id',$thisrow->user->id)->where('subscription_id',$thisrow->stripe_subscription_id)->first() ?? 'unknown';
                        if(isset($invoice->invoice_id)){
                            $tmp["invoice"] = $_ENV['APP_URL'].'/admin/payments/invoice/'.$thisrow->user->id.'/'.$invoice->invoice_id;
                        }else{
                            $tmp["invoice"] = isset($tmp["txn_id"]) ?$_ENV['APP_URL'].'/admin/payments/invoice/'.$thisrow->user->id.'/'.$tmp["txn_id"]: '#';
                        }
                        $tmp["full_name"] = $thisrow->user->first_name .' '.$thisrow->user->last_name;
                        $tmp["email"] = $thisrow->user->email;
                        $tmp["user_image"] = $thisrow->user->user_image;
                        $tmp["days_to_expire"] = Util::dateDiffInDays(date('Y-m-d H:i:s'), $thisrow->status =='active'?$thisrow->end_date :$thisrow->trial_end_date);

                        try {
                            $tmp["date_expiring"] = Util::getFormalDate($thisrow->status =='active'?$thisrow->end_date :$thisrow->trial_end_date);
                        } catch (Exception $e) {
                            $tmp["date_expiring"] = $thisrow->status =='active'?$thisrow->end_date :$thisrow->trial_end_date;
                        }

                        if (!empty($thisrow->updated_at)) {
                            try {
                                $tmp["date_updated"] = Util::getFormalDate($thisrow->updated_at);
                            } catch (Exception $e) {
                                $tmp["date_updated"] = $thisrow->updated_at;
                            }
                        } else {
                            if (!empty($thisrow->created_at)) {
                                try {
                                    $tmp["date_updated"] = Util::getFormalDate($thisrow->created_at);
                                } catch (Exception $e) {
                                    $tmp["date_updated"] = $thisrow->created_at;
                                }
                            }
                        }

                        $all_plans[] = $tmp;
                    }

                }
            }
    
            $vars = [
                'page' => [
                    'name' => "manage-membership",
                    'title' => 'My Membership Subscriptions',
                    'description' => 'Manage subscriptions on Bazichic',
                    'page_content' => $page_content,
                    'membership_plans' => $all_plans
                ]
            ];
            return $this->view->render($response, 'admin/admin-manage-memberships.twig', $vars);
        
    }
    public function updateSubscription(Request $request, Response $response, $args){
        try {

        $id = $request->getParam('id');
        if (empty($id)) {
            throw new \Exception('Please choose a valid subscription plan');
//            return $this->jsonResponse($response,['message'=> 'Ops something wrong'],400);
        }
        $selectedMembership = Plan::find($id);
        if (!$selectedMembership) {
            throw new \Exception("Invalid subscription id");
//            return $this->jsonResponse($response,['message'=> 'Ops something wrong'],400);
        }

            $user= User::find($_SESSION["userID"]);
            if($user->subscription) {
                $session = $this->subscription->updateSubscription($user->subscription->stripe_subscription_id, $selectedMembership->stripe_price_id);
                $user->subscription->membership_plan_id = Plan::where('stripe_price_id', $selectedMembership->stripe_price_id)->first()->id;
                $user->subscription->status = $session->status;
                $user->subscription->end_date = date('Y-m-d H:i:s', $session->current_period_end);
                $user->subscription->save();
            }
            return $this->jsonResponse($response,['message'=> 'Your Plan updated successfully']);
        }catch (Exception $e){
            return $this->jsonResponse($response,['message'=> isset($e) ? $e->getMessage()
                :'We Could not Switch Your Plan'],400);
        }
    }

    /**
     * @throws ApiErrorException
     */
    public function assign(Request $request, Response $response, $args)
    {
        try{
            $params = $request->getParsedBody();
            $user_id = $params['user_id'];
            $plan_id = $params['plan_id'];
            $remark = $params['remark'];
            if (empty($plan_id) || $plan_id < 0) {
                throw new \Exception("Please select a valid subscription plan.");
//                return $this->jsonResponse($response,['error'=>true,'message'=>"Please select a valid subscription plan."],400);
            }
            if (empty($remark) || $remark > 20 ) {
                throw new \Exception("Please enter a valid remark.");
//                return $this->jsonResponse($response,['error'=>true,'message'=>"Please select a valid subscription plan."],400);
            }
            if(empty($user_id)){
                throw new \Exception("Please select a valid user.");
//                return $this->jsonResponse($response,['error'=>true,'message'=>"Please select a Target Customer."],400);
            }
//            $stripe = new StripeService();
            $selectedUser = User::find($user_id);
            $SelectedPlan= Plan::where('stripe_price_id',$plan_id)->first();
            // check if user has subscription and attempt to reactivate
            if($selectedUser->subscription && !in_array($selectedUser->subscription->status,['active','trialing'])){
                $current_sub=$this->subscription->getSubscription($selectedUser->subscription->stripe_subscription_id);
                if(isset($current_sub['error'])){
                    throw new \Exception($current_sub);
//                    return $this->jsonResponse($response,['error'=>true,'message'=>$current_sub],400);
                }
                $update_sub= $this->subscription->updateSubscription($selectedUser->subscription->stripe_subscription_id, $SelectedPlan->stripe_price_id);
                if(isset($update_sub['error'])){
                   $new_sub=$this->GiftSubscription((object)[
                       'id'=>$selectedUser->id,
                       'plan_name'=>$SelectedPlan->name,
                       'stripe_price_id'=>$SelectedPlan->stripe_price_id
                   ]);
                   if(isset($new_sub['error'])){
                       $this->jsonResponse($response,$new_sub,400);
                   }else{
                       return $this->jsonResponse($response,$new_sub);
                   }
                }
                return $this->jsonResponse($response,['error'=>false,'message'=>'Customer Subscription reactivated Successfully']);
            }
        $new_sub=$this->GiftSubscription((object)[
            'id'=>$selectedUser->id,
            'plan_name'=>$SelectedPlan->name,
            'stripe_price_id'=>$SelectedPlan->stripe_price_id,
            'remark'=>$remark
        ]);

        if($new_sub['code'] === Constants::INSERT_FAILURE){
            throw new \Exception($new_sub['message']);
        }
//            $sub = Subscriptions::createMembership((object)[
//                'user_id' => $selectedUser->id,
//                'membership_plan_id' => $SelectedPlan->id,
//                'stripe_subscription_id' => $subscription->id,
//                'price_id' => $subscription->items->data[0]->plan->id,
//                'status' => $subscription->status,
//                'trial_end_date' => date('Y-m-d H:i:s', $subscription->trial_end),
//                'start_date' => date('Y-m-d H:i:s', $subscription->current_period_start),
//                'end_date' => date('Y-m-d H:i:s', $subscription->current_period_end),
//            ]);
        return $this->jsonResponse($response,$new_sub);
        }catch (Exception $e){
            return $this->jsonResponse($response,['error'=>true,'message'=>$e->getMessage()],400);
        }
    }

    public function create_new_subs(object $data): array
    {
        try{
            $user = User::find($data->id);
            $stripe = new StripeService();
            if (!$user->stripe_customer_id){
                $stipe_customer =$stripe->createCustomer((object)[
                    'email'=>$user->email,
                    'name'=>$user->first_name.' '.$user->last_name
                ]);
                $user->stripe_customer_id =$stipe_customer->id;
                $user->save();
            }
            $siteURL= $_ENV['APP_URL'];
            $session = $stripe->createSession($data->stripe_price_id,[
                'trial_period_days'=>10,
                'success_url'=>$siteURL . "/payments/success".'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'=>$siteURL . "/payments/cancel",
                'customer_email' => $user->email,
                'customer_id'=>$user->id
            ]);

            return $this->EmailInvoice($user,(object)[
//                'payment_link'=>$session->url,
                'plan_name'=>$data->plan_name
            ]);
        }catch (Exception $e){
            return ['error'=>true,'message'=>$e->getMessage()];
        }

    }
    public function GiftSubscription(object $data): array
    {
        try{
            $helper = new Helpers();
            $user = User::find($data->id);
            $stripe = new StripeService();
            if (!$user->stripe_customer_id){
                $stipe_customer =$stripe->createCustomer((object)[
                    'email'=>$user->email,
                    'name'=>$user->first_name.' '.$user->last_name
                ]);
                $user->stripe_customer_id =$stipe_customer->id;
                $user->save();
            }
            $siteURL= $_ENV['APP_URL'];
            $new_sub= $this->subscription->GiftSubscription(
                (object)[
                    'stripe_customer_id'=>$user->stripe_customer_id,
                    'stripe_price_id'=>$data->stripe_price_id,
                    'coupon'=>'gift',
                    'remark'=>$data->remark
                ]
            );
            if($new_sub['code'] === Constants::INSERT_FAILURE){
                throw new \Exception($new_sub['message']);
            }
            $new_sub['customer']= $user->stripe_customer_id;
             $helper->sendEmail($user->email, 'Gifted Subscription', 'Bazichic Admin has Gifted you a'.$data->plan_name.'Plan Subscription');
            return ['code'=>Constants::INSERT_SUCCESS,'message'=>'Your Gifted Subscription has been created','data'=>$new_sub];
        }catch (Exception $e){
            return ['error'=>true,'message'=>$e->getMessage()];
        }

    }
    public function plans (Request $request, Response $response, $args) {
            $data  = Plan::where('is_available', 1)->get()->groupBy('interval')->map(function ($plans) {
                return $plans->map(function ($plan) {
                    $plan->url = $this->router->pathFor('get-membership', ['type' => $plan->id]);
                    return $plan;
                });
            });

            if(isset($_SESSION['userID'])){
            $user = User::find($_SESSION["userID"]);
            $user_subscription = $user->subscription;
            $anchorUrl=null;
            if ($user_subscription){
                $anchorUrl= $this->router->pathFor('my-subscriptions');
            }
            }
            $vars = [
                'page' => [
                'title' => 'Subscription Plans | BaziChic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'membership_plans' => $data,
                    'user_subscription' => $user_subscription??null,
//                'numActiveTrials'=> $numActiveTrials,
//                'trialMessage' => $trialMessage,
                'anchorUrl' => $anchorUrl??null
                ]
            ];
            return $this->view->render($response, 'subscription-plans.twig', $vars);
    }

    public function confirmSubscription (Request $request, Response $response, $args){
            $router= $this->router;
            $baseUrl = $_ENV['APP_URL'];
            $ref_code = $request->getAttribute('ref_code');
            if (empty($ref_code)) {
                return $response->withRedirect((string) $router->pathFor('membership'));
            }
            if (!Subscriptions::isRefQCodeExists($ref_code)) {
                return $response->withRedirect((string) $router->pathFor('membership'));
            }
            $selectedItem = Subscriptions::getByQCode($ref_code);
            if ($selectedItem !== null) {
                $plan_id = $selectedItem["plan_id"];
                $user_id = $selectedItem["user_id"];
                $date_expiring = $selectedItem["date_expiring"];
                $date_created = $selectedItem["date_created"];
                $amount = $selectedItem["amount"];
                $status = $selectedItem["status"];
        
                if ($status !== "Pending") {
                    if (Payment::isRefQCodeExists($ref_code)) {
                        return $response->withRedirect('/invoice/' . $ref_code);
                    } else {
                        $uri = $request->getUri()->withPath( $router->pathFor('notFound'));
                        return $response->withRedirect((string) $uri);
                    }
                }
                $selectedMembership = Plan::getID($plan_id);
            } else {
                $uri = $request->getUri()->withPath( $router->pathFor('membership'));
                return $response->withRedirect((string) $uri);
            }
            $sandboxMode = false;
            $paypalURL = "https://www.sandbox.paypal.com/cgi-bin/webscr";
            // if (!$sandboxMode) {
            //     $paypalURL = "https://www.paypal.com/cgi-bin/webscr";
            // }
            $siteURL = isset($uri) ? $baseUrl : "http://localhost:8000/";
            $UnEncsiteURL = isset($uri) ? $baseUrl : "http://localhost:8000/";
            $datenow = new DateTime();
            $vars = [
                'page' => [
                    'title' => 'Confirm Subscription | Bazichic - Chinese Metaphysics Consultancy',
                    'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                    'selectedMembership' => $selectedMembership,
                    'datenow' => $datenow,
                    'plan_id' => $plan_id,
                    'date_expiring' => $date_expiring,
                    'amount' => $amount,
                    'ref_code' => $ref_code,
                    'paypal' => [
                        'PAYPAL_ID' => "finance@bazichic.com",
                        'PAYPAL_URL' => $paypalURL,
                        'itemName' => "Subscription Plan",
                        'itemID' => "$plan_id",
                        'PAYPAL_CURRENCY' => "USD",
                        'PAYPAL_RETURN_URL' => $siteURL . "payments/success",
                        'PAYPAL_CANCEL_URL' => $siteURL . "payments/cancel",
                        'PAYPAL_NOTIFY_URL' => $UnEncsiteURL . "payments/notify"
                    ]
                ]
            ];
            return $this->view->render($response, 'subscription-payment-master.twig', $vars);
    
    }

    public function EmailInvoice($user, object $data): array
    {
        $helper = new Helpers();
        $first_name = $user->first_name;
        $userEmail = $user->email;
        //$userEmail = "javacheartofmine@gmail.com";
        $subject = "Bazichic Subscription Confirmation";
        $twig = $this->TwigTemplate();
        $template = $twig->render('Invoice.twig', ['first_name' => $first_name,
            'payment_link' => $data->payment_link,
            'plan_name'=>$data->plan_name,
            'app_url'=> $_ENV["APP_URL"]
        ]);
       return $helper->sendEmail($userEmail, $subject, $template);
    }
}
