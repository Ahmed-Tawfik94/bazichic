<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Constants;

class Invoice extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
    protected $table = 'invoice';
    protected $fillable = ['user_id', 'invoice_id', 'subscription_id', 'amount_paid', 'status', 'created_at', 'paid_at'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subscriptions::class);
    }
    public static function createInvoice(object $invoice){
        try{
            return self::create(
                [
                    'user_id' => $invoice->user_id,
                    'invoice_id' => $invoice->invoice_id,
                    'status' => $invoice->status,
                    'subscription_id' => $invoice->subscription_id,
                    'amount_paid' => $invoice->amount_paid / 100,
                ]
            );
        }catch(\Exception $e){
            error_log($e->getMessage());

            return ['code'=>Constants::INSERT_FAILURE,'message'=>$e->getMessage()];
        }

}public static function createOrUpdateInvoice(object $invoice){
        try{
            return self::updateOrCreate(
                [
                    'user_id' => $invoice->user_id,
                    'invoice_id' => $invoice->invoice_id,
                ],
                [
                    'status' => $invoice->status,

                    'subscription_id' => $invoice->subscription_id,
                    'amount_paid' => $invoice->amount_paid / 100,
                    'paid_at'=>$invoice->paid_at,
                    'created_at' => $invoice->created_at
                ]
            );
        }catch(\Exception $e){
            error_log($e->getMessage());
            return ['code'=>Constants::INSERT_FAILURE,'message'=>$e->getMessage()];
        }

}
}
