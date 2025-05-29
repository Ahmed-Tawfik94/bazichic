<?php

namespace App\Service\stripe;

use App\Helpers\Constants;
use App\Helpers\Helpers;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscriptions;
use App\Models\User;
use App\Service\Service;
use Carbon\Carbon;
use Exception;
use Stripe\BillingPortal;
use Stripe\Checkout\Session;
use Stripe\Invoice;
use Stripe\Exception\ApiErrorException;
use Stripe\Subscription;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class SubscriptionService extends StripeService
{
    protected $db;
    protected $logger;
public function __construct($db, $logger)
{
    parent::__construct();
    $this->db = $db;
    $this->logger = $logger;

}
    public function createSubscription(object $data)
    {
        try {
            // Create the subscription
            $subscription = Subscription::create([
                'customer' => $data->stripe_customer_id,
                'items' => [
                    ['price' => $data->stripe_price_id], // Price ID from Stripe
                ],
                'trial_end' => $data->timestamp,
                'payment_behavior' => 'default_incomplete', // Creates a subscription without automatic payment
                'billing_cycle_anchor' => $data->timestamp,
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            return ['code'=>Constants::INSERT_SUCCESS,'data'=>$subscription]; // Return the created subscription
        } catch (ApiErrorException $e) {
            // Handle Stripe specific exceptions
            return [
                'code' => Constants::INSERT_FAILURE,
                'message' => $e->getMessage(),
            ];
        } catch (Exception $e) {
            // Catch all other exceptions
            return [
                'code' => Constants::INSERT_FAILURE,
                'message' => $e->getMessage(),
            ];
        }

    }
    public function GiftSubscription(object $data)
    {
        try {
            // Create the subscription
            $coupon = \Stripe\Coupon::retrieve($data->coupon);
            $subscription = Subscription::create([
                'customer' => $data->stripe_customer_id,
                'items' => [
                    ['price' => $data->stripe_price_id], // Price ID from Stripe
                ],
                'coupon' => $coupon->id,
                'metadata'=>['remark'=>$data->remark],
            ]);

            return ['code'=>Constants::INSERT_SUCCESS,'data'=>$subscription]; // Return the created subscription
        } catch (ApiErrorException $e) {
            // Handle Stripe specific exceptions
            return [
                'code' => Constants::INSERT_FAILURE,
                'message' => $e->getMessage(),
            ];
        } catch (Exception $e) {
            // Catch all other exceptions
            return [
                'code' => Constants::INSERT_FAILURE,
                'message' => $e->getMessage(),
            ];
        }

    }


    /**
     * @throws ApiErrorException
     */
    public function getSubscription($id)
    {
        try {
            return Subscription::retrieve($id);
        } catch (ApiErrorException $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function updateSubscription($currentPlan, $target_price_id)
    {
        try {
            // Retrieve the current subscription
            $subscription = Subscription::retrieve($currentPlan);
                // Get the first subscription item (assuming single plan subscriptions)
                $subscriptionItemId = $subscription->items->data[0]->id;

                // Update the subscription to the new price
                $updatedSubscription = Subscription::update($currentPlan, [
                    'items' => [
                        [
                            'id' => $subscriptionItemId, // Keep the existing item ID
                            'price' => $target_price_id, // Assign the new plan
                        ],
                    ],
                    'proration_behavior' => 'create_prorations', // Handle proration
                ]);
            return $updatedSubscription;
        } catch (Exception  $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function getPaymentLink($latest_invoice)
    {
        try {
            $invoice = \Stripe\Invoice::retrieve($latest_invoice);
            return $invoice->payment_intent;
        } catch (Exception $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function updateSubscriber_price($old_price_id,$new_price_id){
        try {
            // Step 1: Retrieve all active subscriptions
            $subscriptions = \Stripe\Subscription::all([
                'status' => 'active',
//                'limit' => 100 // Adjust if needed
            ]);

            foreach ($subscriptions->autoPagingIterator() as $subscription) {
                foreach ($subscription->items->data as $item) {
                    // Step 2: Check if subscription uses the old price
                    if ($item->price->id === $old_price_id) {
//                        echo "Updating subscription: " . $subscription->id . "\n";

                        // Step 3: Modify the subscription to use the new price
                        Subscription::update($subscription->id, [
                            'items' => [
                                [
                                    'id' => $item->id,
                                    'price' => $new_price_id
                                ]
                            ],
                            'proration_behavior' => 'none' // New price applies on next renewal
                        ]);
                    }
                }
            }
            return ['code'=>Constants::INSERT_SUCCESS,'message'=>"All subscriptions updated successfully!\n"] ;
        } catch (Exception $e) {
            return ['code'=>Constants::INSERT_FAILURE,'message'=>"Error: " . $e->getMessage() . "\n"];
        }
    }

    /**
     * @throws ApiErrorException
     */
    public function getInvoice($id)
    {
        try {

        return Invoice::retrieve($id);
        }
        catch (Exception  $e){
            return ['code' => Constants::INSERT_FAILURE, 'message' => $e->getMessage()];
        }
    }

    public function billingPortal(object $data): BillingPortal\Session
    {
        return BillingPortal\Session::create(
            [
                'customer' => $data->customer_id,
                'return_url' => $data->return_url,
            ]
        );
//        $this->stripe->billingPortal->sessions->create();
    }

    /**
     * @throws ApiErrorException
     */
    public function createSession($price_id, $data): ?Session
    {
        //  Create a Stripe Checkout Session
        try {

            return Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'subscription_data' => [
                    "trial_settings" => ["end_behavior" => ["missing_payment_method" => "create_invoice"]],
                    "trial_period_days" => $data['trial_period_days'],
                ],
                'success_url' => $data['success_url'],
                'cancel_url' => $data['cancel_url'],
//                'payment_method_collection'=>"if_required",
                // 'success_url' => 'https://yourdomain.com/success',
                // 'cancel_url' => 'https://yourdomain.com/cancel',
                'customer_email' => $data['customer_email'],
                'metadata' => ['customer_id' => $data['customer_id']]
            ]);
        } catch (ApiErrorException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * @throws ApiErrorException
     */
    public function getSessions($session_id): Session
    {
        return Session::retrieve($session_id);
    }

    /**
     * @param $subscription
     * @return void
     */
    public function canceledSubscription($subscription): void
    {
        try {

            $sub = Subscriptions::where('stripe_subscription_id', $subscription->id)->first();
            if ($sub) {
                $sub->status = $subscription->status;
                $sub->end_date = $subscription->status == 'canceled' ? date('Y-m-d H:i:s', $subscription->canceled_at) : date('Y-m-d H:i:s', $subscription->current_period_end);
                $sub->save();
            }
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }

    /**
     * @param $subscription
     * @return void
     */
    public function SubscriptionUpsert($subscription): void
    {
        try {
            $user = User::where('stripe_customer_id', $subscription->customer)->first();
            if ($user) {
                $payment = Payment::where('sender_id', $user->id)->first();
                $plan = Plan::where('stripe_product_id', $subscription->items->data[0]->plan->product)->first();
                Payment::createPayment((object)
                ['sender_id' => $user->id,
                    'item_code' => $plan->id,
                    'amount' => $subscription->plan->amount / 100,
                    'item' => $plan->name,
                    'status' => 'unpaid',
                    'currency' => $subscription->currency,
                    'txn_id' => $subscription->latest_invoice
                ]);
                $sub = Subscriptions::createMembership((object)[
                    'user_id' => $user->id,
                    'membership_plan_id' => $plan->id,
                    'stripe_subscription_id' => $subscription->id,
                    'price_id' => $subscription->items->data[0]->plan->id,
                    'status' => $subscription->status,
                    'trial_end_date' => date('Y-m-d H:i:s', $subscription->trial_end),
                    'start_date' => date('Y-m-d H:i:s', $subscription->current_period_start),
                    'end_date' => date('Y-m-d H:i:s', $subscription->current_period_end),
                    'remark'=> $subscription->metadata->remark ?? null,
                ]);
                if ($sub['code'] == Constants::INSERT_SUCCESS) {
                    $this->SendSubscriptionEmail($user->id, $subscription->id);
                }
            }
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }

    /**
     * @param $userID
     * @param $session_id
     * @param Helpers $helper
     * @return void
     */
    public function SendSubscriptionEmail($userID, $session_id): void
    {
        $helper = new Helpers();
        $user = User::find($userID);
        $first_name = $user->first_name;
        $userEmail = $user->email;
        //$userEmail = "javacheartofmine@gmail.com";
        $subject = "Bazichic Subscription Confirmation";
        $twig = $this->TwigTemplate();
        $template = $twig->render('subscription-confirmation.twig', ['first_name' => $first_name,
            'session_id' => $session_id,
            'plan_name' => Plan::where('id', $user->subscription->membership_plan_id)->first()->name,
            'app_url' => $_ENV["APP_URL"]
        ]);
        $helper->sendEmail($userEmail, $subject, $template);
    }
    function TwigTemplate(){
        $loader = new FilesystemLoader(__DIR__.'/../../resources/Views/email');
        $twig = new Environment($loader);
        return $twig;
    }
    public function handleTrialWillEnd($subscription)
    {
    }

    /**
     * @param $invoice
     * @return void
     */
    public function createInvoice($invoice): void
    {
        try {

            $user = User::where('stripe_customer_id', $invoice->customer)->first();
            $in = \App\Models\Invoice::createInvoice(
                (object)[
                    'user_id' => $user->id,
                    'invoice_id' => $invoice->id,
                    'status' => $invoice->status,
                    'subscription_id' => $invoice->subscription,
                    'amount_paid' => $invoice->amount_paid,
                    'created_at' => date('Y-m-d H:i:s', $invoice->created)
                ]
            );
            if (isset($in['code']) && $in['code'] == Constants::INSERT_FAILURE) {
                $this->logger->info('create Invoice:' . $in['message']);
            }
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());

        }
    }

    /**
     * @param $invoice
     * @return void
     */
    public function InvoicePayment($invoice): void
    {
        try {

            $user = User::where('stripe_customer_id', $invoice->customer)->first();
            $payment = Payment::where('txn_id', $invoice->id)->first();
            if ($payment && $payment->status != 'paid') {
                $payment->status = $invoice->status;
                $payment->save();
            }
            $in = \App\Models\Invoice::createOrUpdateInvoice((object)[
                'user_id' => $user->id,
                'invoice_id' => $invoice->id,
                'status' => $invoice->status,
                'subscription_id' => $invoice->subscription,
                'amount_paid' => $invoice->amount_paid,
                'paid_at' =>isset($invoice->status_transitions->paid_at)? Carbon::parse($invoice->status_transitions->paid_at)->format('Y-m-d H:i:s') :  Carbon::now()->format('Y-m-d H:i:s'),
                'created_at' => Carbon::parse($invoice->created)->format('Y-m-d H:i:s'),
            ]);
            if (isset($in['code']) && $in['code'] == Constants::INSERT_FAILURE) {
                $this->logger->info('Invoice Payment:' . $in['message']);
            }
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }

    /**
     * @throws ApiErrorException
     */
    protected function billing_portal(): BillingPortal\Configuration
    {
        return BillingPortal\Configuration::create(
            [
                'features' => ['invoice_history' => ['enabled' => true]],
            ]
        );
//        $this->stripe->billingPortal->configurations->create();
    }


}