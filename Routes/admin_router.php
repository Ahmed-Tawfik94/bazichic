<?php

use Slim\Routing\RouteCollectorProxy; // Added for Slim 4 group syntax

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

// global $app, $container; // Removed

return function (RouteCollectorProxy $group) { // Changed to accept $group
    $group->get('/admin-panel', AdminController::class . ':panel')->setName('admin-panel');
    $group->get('/account', AdminController::class . ':account')->setName('admin-profile');
    
    $group->group("/documents", function (RouteCollectorProxy $docGroup) { // Changed to $docGroup
        $docGroup->get('/select-document-type', DocumentController::class . ':index')->setName('select-document-type');
        $docGroup->get('/book-detail/{id}', AdminController::class . ':bookdetails')->setName('book-detail-view');
        $docGroup->post('/delete', DocumentController::class . ':delete');
        $docGroup->get('/manage-documents', DocumentController::class . ':documents')->setName('manage-documents');
        $docGroup->get('/add-document/{doc_type}', DocumentController::class . ':add')->setName('add-document');
        $docGroup->get('/edit-document/{qcode}', DocumentController::class . ':get')->setName('edit-document');
        $docGroup->post('/documents/upload', DocumentController::class . ':Upload')->setName('upload-document');
        $docGroup->post('/documents/update', DocumentController::class . ':Update')->setName('update-document');
        $docGroup->post('/documents_media/upload', DocumentController::class . ':upload_media')->setName('upload-media');
        $docGroup->post('/documents_audio/upload/{qcode}', DocumentController::class . ':upload_audio')->setName('upload-audio');
        $docGroup->post('/document_files/upload', DocumentController::class . ':upload_files')->setName('upload-files');
    });
    
    $group->group('/faq', function (RouteCollectorProxy $faqGroup) { // Changed to $faqGroup
        // FAQ Routes
        $faqGroup->get('/manage-faqs', FAQController::class .':index')->setName('manage-faqs');
        $faqGroup->get('/add-faq', FAQController::class .':add')->setName('add-faq');
        $faqGroup->get('/edit-faq/{id}', FAQController::class . ':edit')->setName('edit-faq');
        // FAQ Category Route
        $faqGroup->get('/faqs-category-manager', FAQCategoryController::class .':index' )->setName('faqs-category-manager');
        $faqGroup->get('/faq/{id}',FAQController::class . ':get_one')->setName('faq');
        $faqGroup->get('/add-faq-category',FAQCategoryController::class .':addCat')->setName('add-faq-category');
        $faqGroup->get('/add-faq-subcategory', FAQCategoryController::class . ':addSubCat')->setName('add-faq-subcategory');
        $faqGroup->get('/edit-faq-category/{id}', FAQCategoryController::class . ':editCat')->setName('edit-faq-category');
        $faqGroup->get('/edit-faq-subcategory/{id}',FAQCategoryController::class .':editSubCat' )->setName('edit-faq-subcategory');
    });

    // APIs Routes
    $group->group('/apis', function (RouteCollectorProxy $apiGroup) { // Changed to $apiGroup
        $apiGroup->post('/faqCategories/create', FaqAPIControllers::class .':createFAQCat')->setName('create-fqa-category');
        $apiGroup->post('/faqCategories/update',FaqAPIControllers::class .':UpdateFAQCat')->setName('update-faq-category');
        $apiGroup->post('/faqCategories/delete',FaqAPIControllers::class .':DeleteFAQCat')->setName('delete-faq-category');
        $apiGroup->post('/faqSubCategories/create', FaqAPIControllers::class .':CreateFAQSubCat')->setName('create-faq-sub-category');
        $apiGroup->post('/faqSubCategories/update', FaqAPIControllers::class .':UpdateFAQSubCat')->setName('update-faq-sub-category');
        $apiGroup->post('/faqSubCategories/delete', FaqAPIControllers::class .':DeleteFAQSubCat')->setName('delete-faq-sub-category');
        $apiGroup->post('/faqs/create', FaqAPIControllers::class .':createFAQ')->setName('create-faq');
        $apiGroup->post('/faqs/update', FaqAPIControllers::class .':UpdateFAQ')->setName('update-faq');
        $apiGroup->post('/faqs/delete',FaqAPIControllers::class .':DeleteFAQ')->setName('delete-faq');
        $apiGroup->get('/faqsubcategories/list/{category}',FaqAPIControllers::class .':listFAQCat' )->setName('list-fqa-category');
        $apiGroup->post('/free_trials/grant', AdminController::class .':GrantTrial')->setName('grant-free-trial');
    });

    // documents category
    $group->group('/categories', function (RouteCollectorProxy $catGroup) { // Changed to $catGroup
        $catGroup->get('/manage-categories', CategoryController::class . ':index')->setName('manage-categories');
        $catGroup->get('/add', CategoryController::class . ':add')->setName('add-category');
        $catGroup->post('/create', CategoryController::class . ":create")->setName('create-category');
        $catGroup->get('/edit/{id}', CategoryController::class . ':edit')->setName('edit-category');
        $catGroup->post('/update', CategoryController::class . ':update')->setName('update-category');
        $catGroup->post('/delete', CategoryController::class . ':delete')->setName('delete-category');
    });
    
    $group->group('/membership', function (RouteCollectorProxy $memGroup) { // Changed to $memGroup
        $memGroup->post('/update/{id}', MembershipController::class . ':update')->setName('update-membership-plan');
        $memGroup->get('/manage-membership-plans', MembershipController::class . ':index')->setName('manage-membership-plans');
        $memGroup->get('/edit/{id}', MembershipController::class . ':edit')->setName('edit-membership-plan');
    });
    
    $group->group('/subscription',function (RouteCollectorProxy $subGroup) { // Changed to $subGroup
        $subGroup->get('/manage',SubscriptionController::class . ':getAll')->setName('manage-all-subscriptions');
        $subGroup->post('/assign',SubscriptionController::class .':assign')->setName('assign-subscription');
    });
    
    $group->group('/rewards',function (RouteCollectorProxy $rewGroup){ // Changed to $rewGroup
        // $container = $this->getContainer(); // Removed
        $rewGroup->post('/referrals/create', DashboardController::class .':create')->setName('create-referral-code');
        $rewGroup->get('/reward-points',DashboardController::class .':reward_points' )->setName('reward-points');
        $rewGroup->get('/assign-reward-points',DashboardController::class .':assign_reward_points')->setName('assign-reward-points');
    });
    
    $group->group('/users', function (RouteCollectorProxy $userGroup) { // Changed to $userGroup (first user group)
        $userGroup->post('/reset_password', UserController::class .':resetPassword');
        $userGroup->post('/creds/update', UserController::class .':credUpdate')->setName('admin-pass-update');
        $userGroup->post('/delete', UserController::class .':delete')->setName('delete-user');
        $userGroup->get('/create-new-account', UserController::class .':create')->setName('create-new-account');
        $userGroup->get('/preview/{id}', UserController::class .':PreviewUser')->setName('preview-user');
        $userGroup->post('/update', UserController::class .':update')->setName('admin-profile-update');
        $userGroup->post('/profile/update/{id}', AdminController::class .':updateUserProfile')->setName('user-account-update');
        $userGroup->get('/profile/{username}', AdminController::class . ':EditUserProfile')->setName('update-user-profile');
        $userGroup->get('/view-profile/{username}', AdminController::class . ":viewProfile")->setName('view-user-profile');
    });
    
    $group->group('/users', function (RouteCollectorProxy $userGroup2){ // Changed to $userGroup2 (second user group)
        $userGroup2->post('/create-account',AdminController::class.':create_user')->setName('create-user-account');
        $userGroup2->get('/manage-users', AdminController::class . ':manageUsers')->setName('manage-users');
        $userGroup2->get('/retrieve-users', AdminController::class . ':retrieveUsers')->setName('retrieve-users');
    });
    
    $group->group('/referrals', function (RouteCollectorProxy $refGroup) { // Changed to $refGroup
        $refGroup->get('/manage', AdminController::class . ':manageReferrals')->setName('manage-referrals');
        $refGroup->get('/retrieve', AdminController::class . ':retrieveReferrals')->setName('retrieve-referrals');
        $refGroup->get('/transactions', AdminController::class . ':manageReferralTransactions')->setName('manage-referral-transactions');
        $refGroup->get('/retrieve-transactions', AdminController::class . ':retrieveReferralTransactions')->setName('retrieve-referral-transactions');
    });
    
    $group->group('/redeem-transactions', function (RouteCollectorProxy $redeemGroup) { // Changed to $redeemGroup
        $redeemGroup->get('/manage', AdminController::class . ':manageRedeemTransactions')->setName('manage-redeem-transactions');
        $redeemGroup->get('/retrieve', AdminController::class . ':retrieveRedeemTransactions')->setName('retrieve-redeem-transactions');
    });
    
    $group->group('/system', function (RouteCollectorProxy $sysGroup) { // Changed to $sysGroup
        $sysGroup->get('/configuration', SystemSettings::class . ':index')->setName('configuration');
        $sysGroup->post('/update', SystemSettings::class . ':Update')->setName('settings-update');
        $sysGroup->post('/upload-banners', SystemSettings::class . ':UploadBanner')->setName('UploadBanner'); // Name was duplicate, kept for now
        $sysGroup->get('/get-banners', SystemSettings::class . ':GetBanner')->setName('UploadBanner'); // Name was duplicate, kept for now
    });

    $group->get('/manage-reviews', AdminController::class . ':ManageReviews')->setName('manage-reviews');
    
    $group->group('/payments', function (RouteCollectorProxy $payGroup) { // Changed to $payGroup
        $payGroup->get('/invoice/{user_id}/{id}',AdminController::class . ':getUserInvoice' )->setName('invoice');
    });

    $group->get('/free-trials-summary', AdminController::class .':FreeTrialsSummary' )->setName('free-trials-summary');
    $group->get('/contact-form-submissions', AdminController::class .':ViewContactSubmision')->setName('contact-form-submissions');
    $group->post('/transactions/delete',PaymentController::class .':DeleteTransactions')->setName('');
    
    $group->group('/notifications', function (RouteCollectorProxy $notifGroup) { // Changed to $notifGroup
        $notifGroup->get('', NotificationController::class .':index')->setName('notifications');
        $notifGroup->post('/blast-notification', NotificationController::class .':Blast')->setName('blast-notification');
        $notifGroup->delete('/{noti_id}',NotificationController::class .':delete')->setName('notifications-delete');
    });
    // Middleware for the entire /admin group is now applied in public/index.php
}; // End of the main closure

// The '/stripe/webhook' route has been moved to Routes/web.php