<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Constants;

class PaymentStripe extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'payment_stripe';
    protected $fillable = ['stripe_product_id','stripe_price_id','name','description','price','currency','interval','date_created','date_updated'];
    public static function  createPayment($data){
        return self::create($data);
    }
    public static function  updatePayment($id,object $data){

        $record = self::find($id);
        if(!$record){
            return ['code'=>Constants::INSERT_FAILURE,'message'=>'Record not found'];
        }
        $record->name =$data->name;
        $record->description = $data->description;
        $record->price = $data->price;
        $record->currency = $data->currency;
        $record->interval = $data->interval;
        return ['code'=>Constants::INSERT_SUCCESS,'message'=>'Record updated successfully'];
    }
}
