<?php

namespace App\Controllers;

use App\Models\Payment;
use App\Models\Subscriptions;
use App\Models\User;
use App\Service\stripe\StripeService;
use App\Service\stripe\SubscriptionService;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\NotFoundException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class PaymentController extends BaseController
{
    protected SubscriptionService $subscriptionService;
    public function __construct(ContainerInterface $container,SubscriptionService $subscriptionService)
    {
        parent::__construct($container);
        $this->subscriptionService = $subscriptionService;
    }
    /**
     * @throws ApiErrorException
     */
    public function Success(Request $request, Response $response, $args)
    {
        $session_id = $request->getQueryParams()["session_id"];
        $Session = new StripeService($this->container);
        $session = $this->subscriptionService->getSessions($session_id);
        $customer = $Session->getCustomer($session->customer);
        $display_message = "Your TXN ID is " . $session->invoice . ". The payment status is " . $session->payment_status . ".";
        try {
            $subscriptionEntry = Payment::where('sender_id', $session->metadata->customer_id)->first();
            if (!$subscriptionEntry) {
                $this->logger->info('Example route was accessed.');
                throw new NotFoundException($request, $response);
            }
            if ($subscriptionEntry->status == 'unpaid') {
                $subscriptionEntry['status'] = $session->payment_status;
                $subscriptionEntry->save();
            }
            $user = User::find($session->metadata->customer_id);
            if($user->id !== $_SESSION['userID']){
               $this->logger->info('Example route was accessed.');
                return $response->withRedirect((string)$this->router->pathFor('unauthorized'));
            }
            if (!$user->stripe_customer_id) {
                $user->stripe_customer_id = $customer->id;
                $user->save();
            }
            if (!Subscriptions::where('stripe_subscription_id', $session->subscription)->exists()) {
                $subs_details = $this->subscriptionService->getSubscription($session->subscription);
                $sub = Subscriptions::createMembership((object)[
                    'user_id' => $_SESSION['userID'],
                    'membership_plan_id' => $subscriptionEntry->item_code,
                    'stripe_subscription_id' => $session->subscription,
                    'price_id' => $subs_details->plan->amount / 100,
                    'status' => $subs_details->status,
                    'trial_end_date' => date('Y-m-d H:i:s', $subs_details->trial_end),
                    'start_date' => date('Y-m-d H:i:s', $subs_details->current_period_start),
                    'end_date' => date('Y-m-d H:i:s', $subs_details->current_period_end)]);
                $this->subscriptionService->SendSubscriptionEmail($user->id, $session_id);
            }
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }

        $vars = [
            'page' => [
                'title' => 'Payment Successful | Bazichic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'display_message' => $display_message,
                'account' => $this->router->pathFor('my-profile'),
                'invoice' => $this->router->pathFor('invoice', ['user_id'=>$session->metadata->customer_id,'id' => $session->invoice])
            ],
        ];

        return $this->view->render($response, 'payment-confirmation.twig', $vars);
    }


    public function Cancel(Request $request, Response $response, $args)
    {
        $display_message = "";
        $vars = [
            'page' => [
                'title' => 'Payment Failed | Bazichic - Chinese Metaphysics Consultancy',
                'description' => 'Access Unlimited E-Books, Audio Books and Magazines on Chinese Metaphysics',
                'display_message' => $display_message
            ],
        ];

        return $this->view->render($response, 'payment-cancel.twig', $vars);

    }

    /**
     * @throws ApiErrorException
     */
    public function Invoice(Request $request, Response $response, $args)
    {
        $InvoiceId = $request->getAttribute('id');
        $user_id = $request->getAttribute('user_id');
        $router = $this->router;
        if (empty($InvoiceId)) {
            return $response->withRedirect((string)$router->pathFor('notFound'));
        }
        if (empty($user_id)) {
            return $response->withRedirect((string)$router->pathFor('unauthorized'));
        }
        if($user_id != $_SESSION['userID'] && $_SESSION['role_id'] !=1){
            return $response->withRedirect((string)$router->pathFor('unauthorized'));
        }
        $user_sub = null;
//        $stripe = new StripeService();
        $invoice = $this->subscriptionService->getInvoice($InvoiceId);
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
                'invoice' => $invoice
            ],
        ];

        return $this->view->render($response, 'invoice.twig', $vars);
    }

    public function DeleteTransactions(Request $request, Response $response, $args)
    {
        $output = array();
        $output["error"] = true;
        $id = $request->getParam('id');

        if ($_SESSION["role_id"] != 1) {
            $output["error"] = true;
            $output["message"] = "You are not authorized to perform this action. ";
            $output["id"] = $id;
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }
        // $membershipEntryID =  $transactionCRUD->getMembershipEntryID($id);
        // $res = $transactionCRUD->delete($id);
        if ($id) {
            $output["error"] = false;
            $deleteAssignment = Subscriptions::deleteMembership($id);
            $output["message"] = "Transaction detail has been deleted successfully. ";
            if ($deleteAssignment) {
                $output["message"] .= " Associated plan assignment has also been removed. ";
            }
            $output["id"] = $id;
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        } else {
            $output["error"] = true;
            $output["message"] = "Failed to delete transaction. Please try again.";
            $jsonData = json_encode($output);
            $response = $response->withHeader('Content-Type', 'application/json');
            return $response->getBody()->write($jsonData);
        }
    }

    function webhook(Request $request, Response $response, $args)
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
            switch ($event->type) {
                case 'customer.created':
                case'customer.updated':
                    $customer = $event->data->object;
                    $user = User::where('email', $customer->email)->first();
                    if ($user) {
                        $user->stripe_customer_id = $customer->id;
                        $user->save();
                    }
                    break;
                case 'customer.subscription.trial_will_end':
                    $subscription = $event->data->object; // contains a \Stripe\Subscription
                    //3days before trial ends
                    $sub = Subscriptions::where('stripe_subscription_id', $subscription->id)->first();
                    // Then define and call a method to handle the trial ending.
                     $this->subscriptionService->handleTrialWillEnd($subscription);
                    break;
                case 'customer.subscription.created':
                case 'customer.subscription.updated':
                    $subscription = $event->data->object;
                $this->subscriptionService->SubscriptionUpsert($subscription);

                break;
                case 'customer.subscription.deleted':
                    $subscription = $event->data->object; // contains a \Stripe\Subscription
                    $this->subscriptionService->canceledSubscription($subscription);
                    // Then define and call a method to handle the subscription being deleted.
                    // handleSubscriptionDeleted($subscription);
                    break;
                case 'invoice.payment_failed':
                case 'invoice.payment_succeeded':
                    $invoice = $event->data->object; // contains a \Stripe\Invoice
                    // check for transaction_details
                $this->subscriptionService->InvoicePayment($invoice);
                break;
                case 'invoice.created':
                    $invoice = $event->data->object; // contains a \Stripe\Invoice
                    $this->subscriptionService->createInvoice($invoice);
                    break;
                default:
                    // Unexpected event type
                    error_log("Unhandled event type: " . $event->type);
                    break;
            }
            return $response->withStatus(200);
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            return $response->withStatus(400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            return $response->withStatus(400);
        }

    }


}