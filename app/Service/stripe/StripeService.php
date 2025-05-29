<?php 


namespace App\Service\stripe;
use App\Service\Service;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class StripeService  {
//    protected  $stripe;
    public function __construct(){
            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
    }

    /**
     * @throws ApiErrorException
     */
    public function getCustomer($customer): Customer
    {
        return Customer::retrieve($customer);

    }

    /**
     * @throws ApiErrorException
     */


    public function createCustomer(object $data): ?Customer{
        try {

        return  Customer::create([
            'name' => $data->name,
            'email' => $data->email,
        ]);
        }catch (\Exception  $e){
            error_log($e->getMessage());
            return null;
        }
    }
}
