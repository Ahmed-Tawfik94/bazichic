<?php
namespace App\Providers;
use App\Controllers\AdminController;
use App\Controllers\LoginController;
use App\Controllers\MembershipController;
use App\Controllers\PaymentController;
use App\Controllers\SubscriptionController;
use App\Service\Service;
use App\Service\stripe\ProductService;
use App\Service\stripe\StripeService;
use App\Service\stripe\SubscriptionService;
use Psr\Container\ContainerInterface;


class ServiceProvider
{
    public static function register(ContainerInterface $container)
    {
        $container[Service::class]= function ($container) {
            return new Service(
                $container->get('db'),
                $container->get('logger')
            );
        };
        $container[StripeService::class]= function ($container) {
            return new StripeService(
                $container
            );
        };

        $container[SubscriptionService::class]= function ($container) {
            return new SubscriptionService(
                $container->get('db'),
                $container->get('logger')
            );
        };$container[ProductService::class]= function ($container) {
            return new ProductService(
                $container->get('db'),
                $container->get('logger')
            );
        };

        $container[PaymentController::class] = function ($container) {
            return new PaymentController(
                $container,
                $container->get(SubscriptionService::class) // Inject the service
            );
        };
        $container[LoginController::class] = function ($container) {
            return new LoginController(
                $container,
                $container->get(SubscriptionService::class) // Inject the service
            );
        };
        $container[MembershipController::class] = function ($container) {
            return new MembershipController(
                $container,
                $container->get(SubscriptionService::class),
                $container->get(ProductService::class)
                // Inject the service
            );
        };
        $container[SubscriptionController::class] = function ($container) {
            return new SubscriptionController(
                $container,
                $container->get(SubscriptionService::class),
                $container->get(ProductService::class)
                // Inject the service
            );
        };
        $container[AdminController::class] = function ($container) {
            return new AdminController(
                $container,
                $container->get(SubscriptionService::class),
                // Inject the service
            );
        };
        // Register other services here...
    }
}
