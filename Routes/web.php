<?php
// https://www.slimframework.com/docs/v4/objects/routing.html

// global $app, $container; // Removed for Slim 4

use Slim\App; // Added for Slim 4
use Slim\Routing\RouteCollectorProxy; // Added for Slim 4 group syntax

use App\Controllers\AdminController;
use App\Controllers\CalendarController;
use App\Controllers\DashboardController;
use App\Controllers\DocumentController;
use App\Controllers\EbookController;
use App\Controllers\ErrorHandlerController;
use App\Controllers\FAQController;
use App\Controllers\FileUploadController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\MembershipController;
use App\Controllers\NotificationController;
use App\Controllers\PaymentController;
use App\Controllers\ReferralController;
use App\Controllers\RegisterController;
use App\Controllers\SubscriptionController;
use App\Controllers\UserController;
use App\Middleware\AuthenticateMiddleware;
use App\Middleware\SubscriptionMiddleware;
use App\Models\Referral;

return function (App $app) { // Wrapped in a closure
    // Unauthenticated routes
    $app->get('/', HomeController::class . ':index')->setName('home');
    $app->get('/about', HomeController::class . ':about')->setName('about');
    $app->get('/verify', RegisterController::class . ':VerifyAccount')->setName('verify');
    $app->post('/resend-verification', RegisterController::class . ':ResendVerification')->setName('resend-verification');
    $app->get('/password-recovery', UserController::class .':passwordRecovery' )->setName('password-recovery');
    $app->post('/register', RegisterController::class . ':register')->setName('registration');
    $app->post('/contact', HomeController::class . ':contactSubmit')->setName('contact-submit');
    $app->get('/coming-soon',HomeController::class .':ComingSoon' )->setName('coming-soon');
    $app->get('/contact', HomeController::class . ':contact')->setName('contact');
    $app->post('/login', LoginController::class . ':login')->setName('login_controller');
    $app->get('/notFound',ErrorHandlerController::class .':notFound')->setName('notFound');
    $app->get('/unauthorized',ErrorHandlerController::class .':Unauthorized')->setName('unauthorized');
    $app->get('/list-faqs/{qcode}', FAQController::class .':listFAQ')->setName('list-faqs');
    $app->get('/frequently-asked-questions',FAQController::class .':get_all')->setName('frequently-asked-questions');
    
    $app->group('/users', function (RouteCollectorProxy $group) { // Changed to $group
        $group->post('/reset_password', UserController::class .':resetPassword');
    });

    $app->group('/e-book-store', function (RouteCollectorProxy $group) { // Changed to $group
        // $container = $this->getContainer(); // Removed for Slim 4
        $group->get('', EbookController::class .':index' )->setName('e-book-store');
        $group->get('/book-detail/{id}', EbookController::class . ':ebookById')->setName('book-detail');
        // Temporarily comment out middleware
        $group->post('/ebook-reader', EbookController::class .':ebookReader')->setName('ebook-reader')
              ->add($app->getContainer()->get(\App\Middleware\SubscriptionMiddleware::class));
    });

    $app->group("/subscription", function (RouteCollectorProxy $group) { // Changed to $group
        $group->get('/subscription-plans',SubscriptionController::class .':plans' )->setName('subscription-plans');
        $group->get('/confirm-subscription/{ref_code}',SubscriptionController::class .':confirmSubscription' )->setName('confirm-subscription');
    });
    $app->get('/testimonials', HomeController::class . ':testimonials')->setName('testimonials');

    $app->get('/login', LoginController::class . ':index')->setName('login');
    $app->get('/register', RegisterController::class . ':index')->setName('register');
    $app->get('/etongshu', CalendarController::class . ':index')->setName('calendar');
    $app->get('/check_sub', CalendarController::class . ':getSubscription')->setName('check_subscription');

    //protected routes
    $app->group('',function (RouteCollectorProxy $group) { // Changed to $group
        // Referral Routes
        $group->post('/generate-referral-code', ReferralController::class . ':generateCode')->setName('generate-referral-code');
        $group->post('/redeem-reward', ReferralController::class . ':redeemReward')->setName('redeem-reward');
        $group->post('/withdraw-money',
            ReferralController::class . ':requestWithdrawMoney'
        )->setName('withdraw-money');

        $group->get('/referrals', ReferralController::class . ':getReferrals')->setName('get-referrals');
        $group->post('/approve-referral', ReferralController::class . ':approveReferral')->setName('approve-referral');

        // Membership Routes
        $group->group('/membership', function (RouteCollectorProxy $subGroup) { // Changed to $subGroup
            $subGroup->get('', MembershipController::class . ':get')->setName('membership');
            $subGroup->get('/member/{type}', MembershipController::class . ':getbytype')->setName('get-membership');
        });
        $group->get('/profile', UserController::class . ':index')->setName('my-profile');
        $group->get('/bookmarks', DashboardController::class .':bookmark')->setName('bookmarks');
        $group->get('/saved-reads', DashboardController::class .':saved_reads')->setName('saved-reads');
        $group->post('/saved-reads', DashboardController::class .':saved_readsbyStatus')->setName('saved-readsbyStatus');
        $group->get('/referral-codes', DashboardController::class .':referral_codes')->setName('referral-codes');
        $group->get('/my-connections', DashboardController::class . ':my_connections')->setName('my-connections');
        $group->post('/apis/reward-points/grant', DashboardController::class .':grant_reward')->setName('');
        $group->get('/dashboard', DashboardController::class . ':index')->setName('dashboard');
        $group->post('/create-customer-portal-session',AdminController::class .':create_customer_portal_session')->setName('billing-portal');
        
        $group->group("/subscription", function (RouteCollectorProxy $subGroup) { // Changed to $subGroup
            $subGroup->get('/my-subscriptions', SubscriptionController::class .':get')->setName('my-subscriptions');
            $subGroup->post('/update-subscriptions', SubscriptionController::class .':updateSubscription')->setName('update-subscriptions');
        });
        
        $group->group('/payments', function (RouteCollectorProxy $subGroup) { // Changed to $subGroup
            $subGroup->post('/purchases/membership', PaymentController::class .':Membership')->setName('');
            $subGroup->get('/success', PaymentController::class .':Success')->setName('payment-success');//'/payment-success'
            $subGroup->get('/cancel', PaymentController::class .':Cancel')->setName('payment-cancel');//'/payment-cancel'
            $subGroup->post('/notify', PaymentController::class .':Notify')->setName('payment-notify');
            $subGroup->get('/invoice/{user_id}/{id}',PaymentController::class . ':Invoice' )->setName('invoice');
        });
        
        $group->group("/documents", function (RouteCollectorProxy $subGroup) { // Changed to $subGroup
            $subGroup->post('/save', DocumentController::class . ':save')->setName('save-document');
            $subGroup->post('/reviews', DocumentController::class . ':reviews')->setName('review-document');
            $subGroup->post('/delete_review', DocumentController::class . ':delete_reviews');
            $subGroup->post('/endorse', DocumentController::class . ':endorse')->setName('endorse-document');
        });

        $group->group('/users', function (RouteCollectorProxy $subGroup) { // Changed to $subGroup
            $subGroup->group('/notification',function (RouteCollectorProxy $notifGroup){ // Changed to $notifGroup
                $notifGroup->get('/',NotificationController::class . ':view_notification')->setName('my_notifications');
                $notifGroup->get('/read/{id}',NotificationController::class . ':read_notification')->setName('read_notification');
            });
            $subGroup->post('/update', UserController::class .':update')->setName('user-profile-update');
            $subGroup->post('/creds/update', UserController::class .':credUpdate')->setName('update-profile');
        });

    })->add($app->getContainer()->get(\App\Middleware\AuthenticateMiddleware::class)->setAllowedRoles(['user']));

    $app->get('/logout', LoginController::class . ':logout')->setName('logout')
          ->add($app->getContainer()->get(\App\Middleware\AuthenticateMiddleware::class)->setAllowedRoles(['user', 'admin']));

    // Stripe Webhook - moved from admin_router.php as it's typically unauthenticated or uses different auth
    $app->post('/stripe/webhook', \App\Controllers\PaymentController::class .':webhook');

}; // End of the main closure






