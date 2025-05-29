<?php

namespace App\Controllers;
use App\Helpers\Constants;
use App\Helpers\Helpers;
use App\Models\Plan;
use App\Models\RewardPoint;
use App\Models\User;
use App\Models\Util;
use App\Service\stripe\ProductService;
use App\Service\stripe\StripeService;
use App\Service\stripe\SubscriptionService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Stripe\Exception\ApiErrorException;

class MembershipController extends BaseController
{
    protected SubscriptionService $subscription;
    protected ProductService $product;
    public function __construct(ContainerInterface $container, SubscriptionService $subscriptionService,ProductService $productService)
    {
        parent::__construct($container);
        $this->subscription=$subscriptionService;
        $this->product=$productService;

    }

    public function index(Request $request, Response $response, $args)
    {
        $monthly_plans= Plan::where('interval','month');
        $yearly_plans = Plan::where('interval','year');
        $data=[
            'month'=>$monthly_plans->get()->toArray(),
            'year'=>$yearly_plans->get()->toArray()
        ];
        $plans = Plan::all()
            ->groupBy('stripe_product_id')
            ->map(function ($group) {
                return [
                    'name'=>$group->pluck('name')->first(),
                    'description'=>$group->pluck('description')->first(),
                    'stripe_product_id' => $group->first()->stripe_product_id,
                    'items' => $group->map(function ($plan) {
                        return [
                            'interval' => $plan->interval,
                            'price' => $plan->price,
                            'is_available' => $plan->is_available
                        ];
                    })->toArray()
                ];
            });
        $vars = [
            'page' => [
                "name"=>"manage-membership",
                'title' => 'Manage Membership Plans | Bazichic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'membership_plans' => $plans,
            ],
        ];
        return $this->view->render($response, 'admin/manage-membership-plans.twig', $vars);
    }
    public function add(Request $request, Response $response, $args)
    {
        $vars = [
            'page' => [
                'title' => 'Manage Membership Plans | Bazichic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
            ],
        ];
        return $this->view->render($response, 'admin/create_product_subscription.twig', $vars);
    }
    public function createSubscription(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        $productName = $data['name'] ?? 'Default Product Name';
        $productDescription = $data['description'] ?? 'Default Description';
        $productPrice = $data['price'] * 100 ?? 1000;  // Price in cents
        $currency = $data['currency'] ?? 'usd';
        $interval = $data['interval'] ?? 'month';
        $is_available = $data['is_available'] ;
        $res = $this->product->createProduct([
            'name'=> $productName,
            'description'=> $productDescription,
            'price'=> $productPrice,
            'currency'=> $currency,
            'interval'=> $interval,
            'is_available'=>$is_available
        ]);
        $res['redirection_url'] = $this->router->pathFor('manage-membership-plans');
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
       
    }

    /**
     * @throws ApiErrorException
     */
    public function getbytype(Request $request, Response $response, $args)
    {
        $router = $this->router;
        $uri= $request->getUri();
        $member_type = $request->getAttribute('type');
        if (empty($member_type)) {
            $uri = $this->router->pathFor('membership');
            return $response->withHeader('Location', $uri)->withStatus(302);
        }
        if (!Plan::find($member_type)) {
            $uri = $this->router->pathFor('membership');
            return $response->withHeader('Location', $uri)->withStatus(302);
        }
        $_SESSION["last_saved"] = $_SERVER['REQUEST_URI'];
        // $selectedMembership = Plan::getID($member_type);
        $selectedMembership = Plan::find($member_type);
        if ($selectedMembership == null) {
            $uri = $this->router->pathFor('membership');
            return $response->withHeader('Location', $uri)->withStatus(302);
        }
        $plan_id = $selectedMembership["id"];
        $datenow = date('Y-m-d H:i:s');
        $date = strtotime($datenow);
        $duration = $selectedMembership['interval']==='month'? 30 : 365;
        $expiryDate = date('Y-m-d H:i:s', strtotime('+' . $duration. ' days'));
        $datenowDisplay = Util::getFormalDate($datenow);
        $expiryDateDisplay = Util::getFormalDate($expiryDate);
        $selectedMembership['price'] = number_format($selectedMembership['price'], 2);
        $priceWithRedeem = $selectedMembership["price"];
        $canRedeem = false;
        $current_points = RewardPoint::getCurrentRewardPointFor($_SESSION["userID"]);
        $remaining_points = RewardPoint::getCurrentRewardPointFor($_SESSION["userID"]);
        $redeemStatus = "You are not eligible to use your loyalty points to complete this transaction.";


        $baseUrl = $uri->getScheme() . '://' . $uri->getHost();

        // Include the port if it's not the default (80 for HTTP, 443 for HTTPS)
        if ($uri->getPort() && !in_array($uri->getPort(), [80, 443])) {
            $baseUrl .= ':' . $uri->getPort();
        }
        $siteURL =$_ENV['APP_URL'];
            $user= User::find($_SESSION["userID"]);
                $session = $this->subscription->createSession($selectedMembership->stripe_price_id,[
                    'trial_period_days'=>10,
                    'success_url'=>$siteURL . "/payments/success".'?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url'=>$siteURL . "/payments/cancel",
                    'customer_email' => $_SESSION['email'],
                    'customer_id'=>$_SESSION["userID"]
                ]);
        $countries = (new Helpers())->getCountries();
        $vars = [
            'countries' => $countries,
            'page' => [
                'title' => 'Confirm Membership | Bazichic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'selectedMembership' => $selectedMembership,
                'datenow' => $datenow,
                'canSubscribe' => true,
                'expiryDate' => $expiryDate,
                'datenowDisplay' => $datenowDisplay,
                'expiryDateDisplay' => $expiryDateDisplay,
                'current_points' => $current_points,
                'remaining_points' => $remaining_points,
                'canRedeem' => $canRedeem,
                'priceWithRedeem' => $priceWithRedeem,
                'redeemStatus' => $redeemStatus,
                'user_email'=>$user->email,
                'paypal' => [
                    //'PAYPAL_ID' => "sb-vbunn778158@business.example.com",
                    'PAYPAL_ID' => "finance@bazichic.com",
                    'PAYPAL_URL' =>$session->url ,//$paypalURL,
                    'itemName' => "Subscription Plan",
                    'itemID' => $selectedMembership->name ."Plan",
                    //'priceToPay' => 10,
                    'PAYPAL_CURRENCY' => "USD",
                    'priceToPay' => 1,
                    'PAYPAL_RETURN_URL' => $session->success_url,
                    'PAYPAL_CANCEL_URL' => $session->cancel_url,
                    'PAYPAL_NOTIFY_URL' => $_ENV['APP_URL'] . "payments/notify"
                ]
            ]
        ];
        return $this->view->render($response, 'purchase-membership-form.twig', $vars);
    }
    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $product_id = $args['id'];
        $prices = $data['prices'];
        $output=[];

        if (empty($prices)) {
            return $this->jsonResponse($response, ['error' => true,
                'message' => 'Please enter a Valid price.'],400);
        }
        try {
            $existingPlans = Plan::where('stripe_product_id', $product_id)
                ->get()
                ->keyBy(fn($plan) => $plan->interval); // Key by interval for easy lookup

            $toInsert = [];
            $toUpdate = [];

            foreach ($prices as $price) {
                $interval = $price['interval'];
                $amount = $price['amount'];
                $isAvailable = $price['active'];

                if (isset($existingPlans[$interval])) {
                    // Plan exists, check if price needs updating
                    $plan = $existingPlans[$interval];

                    if ($plan->price != $amount || $plan->is_available != $isAvailable) {
                        // Update Stripe price if needed
                        $res = $this->product->updatePrice(
                            $product_id,
                            $plan->stripe_price_id,
                            $amount ,
                            $interval
                        );

                        if ($res['status'] === 'error') {
                            return $this->jsonResponse($response, $res, 400);
                        }

                        // Collect plans to update
                        $toUpdate[] = [
                            'id' => $plan->id,
                            'stripe_price_id' => $res['price']->id,
                            'price' => $amount,
                            'is_available' => $isAvailable,
                            'old_price_id'=>$plan->stripe_price_id
                        ];
                    }
                } else {
                    // New plan, create it
                    $res = $this->product->createPrice(
                        $product_id,
                        $amount * 100,
                        'usd',
                        $interval
                    );

                    if ($res['status'] === 'error') {
                        return $this->jsonResponse($response, $res, 400);
                    }

                    $toInsert[] = [
                        'name' => $existingPlans->first()->name ?? 'Default Name',
                        'stripe_product_id' => $product_id,
                        'stripe_price_id' => $res['price']->id,
                        'description' => $existingPlans->first()->description,
                        'price' => $amount,
                        'interval' => $interval,
                        'currency' => 'usd',
                        'is_available' => $isAvailable,
                    ];
                }
            }

            // Bulk update existing plans
            if (!empty($toUpdate)) {
                foreach ($toUpdate as $update) {
                    Plan::where('id', $update['id'])->update([
                        'stripe_price_id' => $update['stripe_price_id'],
                        'price' => $update['price'],
                        'is_available' => $update['is_available'],
                    ]);
                $res = $this->subscription->updateSubscriber_price($update['old_price_id'],$update['stripe_price_id']);
                if($res['code'] === Constants::INSERT_FAILURE){
                    $output['message']= $res['message'];
                }
                if($res['code']=== Constants::INSERT_SUCCESS){
                    $output['message'] = $res['message'];
                }
                }
            }

            // Bulk insert new plans
            if (!empty($toInsert)) {
                foreach ($toInsert as $item){
                    Plan::createPlan((object)$item);
                }
            }

            return $this->jsonResponse($response, ['success' => true, 'message' => 'Plans updated successfully.\n'.$output['message']]);

        }catch (\Exception $e){
            return $this->jsonResponse($response, ['error'=>true,
                    'message'=>"Failed to update plan. Please try again." .$e->getMessage()]
                ,400);
        }
    }

    public function edit(Request $request, Response $response, $args)
    {
        $id = $request->getAttribute('id');
//        $plan = Plan::where('stripe_product_id',$id)->get()->toArray();
        $plans = Plan::where('stripe_product_id', $id)->get()
            ->groupBy('stripe_product_id')
            ->map(function ($group) {
                return [
                    'name'=>$group->pluck('name')->first(),
                    'description'=>$group->pluck('description')->first(),
                    'stripe_product_id' => $group->first()->stripe_product_id,
                    'intervals' => $group->pluck('interval')->toArray(),
                    'prices' => $group->pluck('price')->toArray(),
                    'is_available'=>$group->pluck('is_available')->toArray()
                ];
            });

        if ($plans == null) {
            $uri = $this->router->pathFor('manage-membership-plans');
            return $response->withHeader('Location', $uri)->withStatus(302);
        }
        $vars = [
            'page' => [
                'title' => 'Update Subscription Plan',
                'description' => 'Update Subscription Plan'
            ],
            'plan' => $plans[$id],
            'router' => $this->router
        ];

        return $this->view->render($response, 'admin/membership-edit.twig', $vars);
    }


    public function delete(Request $request, Response $response, $args)
    {
        $output = array();
        $output["error"] = true;
        $data = $request->getParsedBody();
        $id = $data['plan_id'];
        //$owner_id = $blogCRUD->getAuthorID($id);
        if ($_SESSION["role_id"] != 1) {
            //if ($owner_id != $_SESSION["userID"]) {
            $output["error"] = true;
            $output["message"] = "You are not authorized to perform this action. ";
            $output["id"] = $id;
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write($jsonData);
            exit;
            //}
        }
        $res = Plan::deletePlan($id);
        if ($res) {
            $output["error"] = false;
            $output["message"] = "Investment plan has been deleted successfully. ";
            $output["id"] = $id;
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        } else {
            $output["error"] = true;
            $output["message"] = "Failed to delete plan. Please try again.";
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }
    }

    public function info(Request $request, Response $response, $args)
    {
        $output = array();
        $response = $response->withHeader('Content-Type', 'application/json');
        if (null !== $request->getAttribute('id')) {
            $id = $request->getAttribute('id');
            $plan = Plan::getID($id);
            $output["error"] = false;
            //$output["plan"] = $plan;
            $output["price"] = $plan["price"];
            $output["duration"] = $plan["duration"];
            $jsonData = json_encode($output);
            return $response->getBody()->write($jsonData);
        } else {
            $output["error"] = true;
            $output["message"] = "Failed to fetch plan details.";
            $jsonData = json_encode($output);
            return $response->getBody()->write($jsonData);
        }
    }

    public function get(Request $request, Response $response, $args)
    {
        $data = Plan::where('is_available', 1)->get()->groupBy('interval');

        foreach ($data as $interval => $plans) {
            foreach ($plans as $plan) {
                $plan->url = $this->router->pathFor('get-membership', ['type' => $plan->id]);
            }
        }

        $user = User::find($_SESSION["userID"]);
        $user_subscription = $user->subscription;
        $anchorUrl=null;
        if ($user_subscription){
            $anchorUrl= $this->router->pathFor('my-subscriptions');
        }
        $vars = [
            'page' => [
                'title' => 'Manage Membership Plans | Bazichic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines',
                'membership_plans' => $data,
                'anchorUrl' => $anchorUrl,
                'user_subscription' => $user_subscription
            ],
            'router'=> $this->router
        ];
        return $this->view->render($response, 'membership.twig', $vars);
    }

}
