<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use App\Helpers\Constants;

class ReferralTransaction extends Model
{
    protected $table = 'referrals_txns'; // Define the table if different from the model name
    protected $fillable = [
        'user_id', 
        'item_name', 
        'total_price', 
        'currency_code', 
        'date_created', 
        'txn_id', 
        'sender', 
        'gateway_status', 
        'status'
    ];

    public function createTransaction($user_id, $item_name, $total_price, $currency_code, $date_created, $txn_id, $sender, $gateway_status, $status)
    {
        $response = ["error" => true];

        try {
            // Create a new transaction
            $transaction = self::create([
                'user_id' => $user_id,
                'item_name' => $item_name,
                'total_price' => $total_price,
                'currency_code' => $currency_code,
                'date_created' => $date_created,
                'txn_id' => $txn_id,
                'sender' => $sender,
                'gateway_status' => $gateway_status,
                'status' => $status
            ]);

            $response['error'] = false;
            $response['id'] = $transaction->id;
            $response['code'] = Constants::INSERT_SUCCESS;

        } catch (Exception $e) {
            $response['code'] = Constants::INSERT_FAILURE;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }
}
