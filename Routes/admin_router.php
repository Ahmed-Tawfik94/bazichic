<?php

use App\Controllers\AdminController;
use App\Controllers\CategoryController;
use App\Controllers\DashboardController;
use App\Controllers\DocumentController;
use App\Controllers\FaqAPIControllers;
use App\Controllers\FAQCategoryController;
use App\Controllers\FAQController;
use App\Controllers\MembershipController;
use App\Controllers\NotificationController;
use App\Controllers\PaymentController;
use App\Controllers\SubscriptionController;
use App\Controllers\SystemSettings;
use App\Controllers\UserController;
use App\Middleware\AuthenticateMiddleware;
global $app, $container;
$app->group('/admin', function () use ($app) {
    $app->get('/admin-panel', AdminController::class . ':panel')->setName('admin-panel');
    $app->get('/account', AdminController::class . ':account')->setName('admin-profile');
    $app->group("/documents", function () use ($app) {
        $app->get('/select-document-type', DocumentController::class . ':index')->setName('select-document-type');
        $app->get('/book-detail/{id}', AdminController::class . ':bookdetails')->setName('book-detail-view');
        $app->post('/delete', DocumentController::class . ':delete');
        $app->get('/manage-documents', DocumentController::class . ':documents')->setName('manage-documents');
        $app->get('/add-document/{doc_type}', DocumentController::class . ':add')->setName('add-document');
        $app->get('/edit-document/{qcode}', DocumentController::class . ':get')->setName('edit-document');
        $app->post('/documents/upload', DocumentController::class . ':Upload')->setName('upload-document');
        $app->post('/documents/update', DocumentController::class . ':Update')->setName('update-document');
        $app->post('/documents_media/upload', DocumentController::class . ':upload_media')->setName('upload-media');
        $app->post('/documents_audio/upload/{qcode}', DocumentController::class . ':upload_audio')->setName('upload-audio');
        $app->post('/document_files/upload', DocumentController::class . ':upload_files')->setName('upload-files');
    });
    $app->group('/faq', function () use ($app) {
        // FAQ Routes
        $app->get('/manage-faqs', FAQController::class .':index')->setName('manage-faqs');
        $app->get('/add-faq', FAQController::class .':add')->setName('add-faq');
        $app->get('/edit-faq/{id}', FAQController::class . ':edit')->setName('edit-faq');
        // FAQ Category Route
        $app->get('/faqs-category-manager', FAQCategoryController::class .':index' )->setName('faqs-category-manager');
        $app->get('/faq/{id}',FAQController::class . ':get_one')->setName('faq');
        $app->get('/add-faq-category',FAQCategoryController::class .':addCat')->setName('add-faq-category');
        $app->get('/add-faq-subcategory', FAQCategoryController::class . ':addSubCat')->setName('add-faq-subcategory');
        $app->get('/edit-faq-category/{id}', FAQCategoryController::class . ':editCat')->setName('edit-faq-category');
        $app->get('/edit-faq-subcategory/{id}',FAQCategoryController::class .':editSubCat' )->setName('edit-faq-subcategory');
    });

// APIs Routes
    $app->group('/apis', function () use($app) {
        $app->post('/faqCategories/create', FaqAPIControllers::class .':createFAQCat')->setName('create-fqa-category');
        $app->post('/faqCategories/update',FaqAPIControllers::class .':UpdateFAQCat')->setName('update-faq-category');
        $app->post('/faqCategories/delete',FaqAPIControllers::class .':DeleteFAQCat')->setName('delete-faq-category');
        $app->post('/faqSubCategories/create', FaqAPIControllers::class .':CreateFAQSubCat')->setName('create-faq-sub-category');
        $app->post('/faqSubCategories/update', FaqAPIControllers::class .':UpdateFAQSubCat')->setName('update-faq-sub-category');
        $app->post('/faqSubCategories/delete', FaqAPIControllers::class .':DeleteFAQSubCat')->setName('delete-faq-sub-category');
        $app->post('/faqs/create', FaqAPIControllers::class .':createFAQ')->setName('create-faq');
        $app->post('/faqs/update', FaqAPIControllers::class .':UpdateFAQ')->setName('update-faq');
        $app->post('/faqs/delete',FaqAPIControllers::class .':DeleteFAQ')->setName('delete-faq');
        $app->get('/faqsubcategories/list/{category}',FaqAPIControllers::class .':listFAQCat' )->setName('list-fqa-category');
        $app->post('/free_trials/grant', AdminController::class .':GrantTrial')->setName('grant-free-trial');
    });

//    documents category
    $app->group('/categories', function () use ($app) {
        $app->get('/manage-categories', CategoryController::class . ':index')->setName('manage-categories');
        $app->get('/add', CategoryController::class . ':add')->setName('add-category');
        $app->post('/create', CategoryController::class . ":create")->setName('create-category');
        $app->get('/edit/{id}', CategoryController::class . ':edit')->setName('edit-category');
        $app->post('/update', CategoryController::class . ':update')->setName('update-category');
        $app->post('/delete', CategoryController::class . ':delete')->setName('delete-category');
    });
    $app->group('/membership', function () use ($app) {
        $app->post('/update/{id}', MembershipController::class . ':update')->setName('update-membership-plan');
//        $app->post('/delete', MembershipController::class . ':delete')->setName('deleted-membership-plan');
        $app->get('/manage-membership-plans', MembershipController::class . ':index')->setName('manage-membership-plans');
        $app->get('/edit/{id}', MembershipController::class . ':edit')->setName('edit-membership-plan');
//        $app->get('/add', MembershipController::class . ':add')->setName('add-membership-plan');
//        $app->post('/create-subscription-product', MembershipController::class . ':createSubscription')->setName('create-membership-plan');
    });
    $app->group('/subscription',function () use ($app) {
        $app->get('/manage',SubscriptionController::class . ':getAll')->setName('manage-all-subscriptions');
        $app->post('/assign',SubscriptionController::class .':assign')->setName('assign-subscription');
    });
    $app->group('/rewards',function ()use ($app){
        $container = $this->getContainer();
        $app->post('/referrals/create', DashboardController::class .':create')->setName('create-referral-code');
        $app->get('/reward-points',DashboardController::class .':reward_points' )->setName('reward-points');
        $app->get('/assign-reward-points',DashboardController::class .':assign_reward_points')->setName('assign-reward-points');
    });
    $app->group('/users', function () use($app) {
        $app->post('/reset_password', UserController::class .':resetPassword');
        $app->post('/creds/update', UserController::class .':credUpdate')->setName('admin-pass-update');
        $app->post('/delete', UserController::class .':delete')->setName('delete-user');
        $app->get('/create-new-account', UserController::class .':create')->setName('create-new-account');
        $app->get('/preview/{id}', UserController::class .':PreviewUser')->setName('preview-user');
        $app->post('/update', UserController::class .':update')->setName('admin-profile-update');
        $app->post('/profile/update/{id}', AdminController::class .':updateUserProfile')->setName('user-account-update');
        $app->get('/profile/{username}', AdminController::class . ':EditUserProfile')->setName('update-user-profile');
        $app->get('/view-profile/{username}', AdminController::class . ":viewProfile")->setName('view-user-profile');

    });
    $app->group('/users', function () use($app){
        $app->post('/create-account',AdminController::class.':create_user')->setName('create-user-account');
        $app->get('/manage-users', AdminController::class . ':manageUsers')->setName('manage-users');
        $app->get('/retrieve-users', AdminController::class . ':retrieveUsers')->setName('retrieve-users');
    });
    $app->group('/referrals', function () use ($app) {
        $app->get('/manage', AdminController::class . ':manageReferrals')->setName('manage-referrals');
        $app->get('/retrieve', AdminController::class . ':retrieveReferrals')->setName('retrieve-referrals');
        // ✅ New Referral Transactions Route
        $app->get('/transactions', AdminController::class . ':manageReferralTransactions')->setName('manage-referral-transactions');
        $app->get('/retrieve-transactions', AdminController::class . ':retrieveReferralTransactions')->setName('retrieve-referral-transactions');
    });
    $app->group('/redeem-transactions', function () use ($app) {
        $app->get('/manage', AdminController::class . ':manageRedeemTransactions')->setName('manage-redeem-transactions');
        $app->get('/retrieve', AdminController::class . ':retrieveRedeemTransactions')->setName('retrieve-redeem-transactions');
    });
    $app->group('/system', function () use($app) {
        $app->get('/configuration', SystemSettings::class . ':index')->setName('configuration');
        $app->post('/update', SystemSettings::class . ':Update')->setName('settings-update');
        $app->post('/upload-banners', SystemSettings::class . ':UploadBanner')->setName('UploadBanner');
        $app->get('/get-banners', SystemSettings::class . ':GetBanner')->setName('UploadBanner');
    });

    $app->get('/manage-reviews', AdminController::class . ':ManageReviews')->setName('manage-reviews');
    $app->group('/payments', function () use($app) {
        $app->get('/invoice/{user_id}/{id}',AdminController::class . ':getUserInvoice' )->setName('invoice');
    });


    $app->get('/free-trials-summary', AdminController::class .':FreeTrialsSummary' )->setName('free-trials-summary');
//    $app->get('/timeline',AdminController::class .':Timeline' )->setName('timeline');
    $app->get('/contact-form-submissions', AdminController::class .':ViewContactSubmision')->setName('contact-form-submissions');
    $app->post('/transactions/delete',PaymentController::class .':DeleteTransactions')->setName('');
    $app->group('/notifications', function () use($app) {
        $app->get('', NotificationController::class .':index')->setName('notifications');
        $app->post('/blast-notification', NotificationController::class .':Blast')->setName('blast-notification');
        $app->delete('/{noti_id}',NotificationController::class .':delete')->setName('notifications-delete');
    });
})->add(new AuthenticateMiddleware( ['admin']));
$app->post('/stripe/webhook',PaymentController::class .':webhook');