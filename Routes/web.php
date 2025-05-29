<?php
// https://www.slimframework.com/docs/v4/objects/routing.html

global $app, $container;

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
$app->group('/users', function () use($app) {
    $app->post('/reset_password', UserController::class .':resetPassword');
});

$app->group('/e-book-store', function () use($app) {
    $container = $this->getContainer();
    $app->get('', EbookController::class .':index' )->setName('e-book-store');
    $app->get('/book-detail/{id}', EbookController::class . ':ebookById')->setName('book-detail');
    $app->post('/ebook-reader', EbookController::class .':ebookReader')->setName('ebook-reader')->add(new SubscriptionMiddleware($container));;
});

$app->group("/subscription", function () use ($app) {
    $app->get('/subscription-plans',SubscriptionController::class .':plans' )->setName('subscription-plans');
    $app->get('/confirm-subscription/{ref_code}',SubscriptionController::class .':confirmSubscription' )->setName('confirm-subscription');
});
$app->get('/testimonials', HomeController::class . ':testimonials')->setName('testimonials');

$app->get('/login', LoginController::class . ':index')->setName('login');
$app->get('/register', RegisterController::class . ':index')->setName('register');
$app->get('/etongshu', CalendarController::class . ':index')->setName('calendar');
$app->get('/check_sub', CalendarController::class . ':getSubscription')->setName('check_subscription');
//protected routes
$app->group('',function () use($app) {
    // Referral Routes
    $app->post('/generate-referral-code', ReferralController::class . ':generateCode')->setName('generate-referral-code');
    $app->post('/redeem-reward', ReferralController::class . ':redeemReward')->setName('redeem-reward');
    $app->post('/withdraw-money',
        ReferralController::class . ':requestWithdrawMoney'
    )->setName('withdraw-money');

    $app->get('/referrals', ReferralController::class . ':getReferrals')->setName('get-referrals');
    $app->post('/approve-referral', ReferralController::class . ':approveReferral')->setName('approve-referral');

    // Membership Routes
    $app->group('/membership', function () use ($app) {
        $app->get('', MembershipController::class . ':get')->setName('membership');
        $app->get('/member/{type}', MembershipController::class . ':getbytype')->setName('get-membership');
    });
    $app->get('/profile', UserController::class . ':index')->setName('my-profile');
    $app->get('/bookmarks', DashboardController::class .':bookmark')->setName('bookmarks');
    $app->get('/saved-reads', DashboardController::class .':saved_reads')->setName('saved-reads');
    $app->post('/saved-reads', DashboardController::class .':saved_readsbyStatus')->setName('saved-readsbyStatus');
    $app->get('/referral-codes', DashboardController::class .':referral_codes')->setName('referral-codes');
    $app->get('/my-connections', DashboardController::class . ':my_connections')->setName('my-connections');
    $app->post('/apis/reward-points/grant', DashboardController::class .':grant_reward')->setName('');
    $app->get('/dashboard', DashboardController::class . ':index')->setName('dashboard');
    $app->post('/create-customer-portal-session',AdminController::class .':create_customer_portal_session')->setName('billing-portal');
    $app->group("/subscription", function () use ($app) {
        $app->get('/my-subscriptions', SubscriptionController::class .':get')->setName('my-subscriptions');
        $app->post('/update-subscriptions', SubscriptionController::class .':updateSubscription')->setName('update-subscriptions');
    });
    $app->group('/payments', function () use($app) {
        $app->post('/purchases/membership', PaymentController::class .':Membership')->setName('');
        $app->get('/success', PaymentController::class .':Success')->setName('payment-success');//'/payment-success'
        $app->get('/cancel', PaymentController::class .':Cancel')->setName('payment-cancel');//'/payment-cancel'
        $app->post('/notify', PaymentController::class .':Notify')->setName('payment-notify');
        $app->get('/invoice/{user_id}/{id}',PaymentController::class . ':Invoice' )->setName('invoice');
    });
    $app->group("/documents", function () use ($app) {
        $app->post('/save', DocumentController::class . ':save')->setName('save-document');
        $app->post('/reviews', DocumentController::class . ':reviews')->setName('review-document');
        $app->post('/delete_review', DocumentController::class . ':delete_reviews');
        $app->post('/endorse', DocumentController::class . ':endorse')->setName('endorse-document');
    });

    $app->group('/users', function () use($app) {
        $app->group('/notification',function () use($app){
            $app->get('/',NotificationController::class . ':view_notification')->setName('my_notifications');
            $app->get('/read/{id}',NotificationController::class . ':read_notification')->setName('read_notification');
        });
        $app->post('/update', UserController::class .':update')->setName('user-profile-update');
        $app->post('/creds/update', UserController::class .':credUpdate')->setName('update-profile');
    });

})->add(new AuthenticateMiddleware( ['user']));


$app->get('/logout', LoginController::class . ':logout')->setName('logout')->add(new AuthenticateMiddleware(['user','admin']));






