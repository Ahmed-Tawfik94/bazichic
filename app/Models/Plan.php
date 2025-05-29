<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Stripe\Subscription;

class Plan extends  Model
{
    public const CREATED_AT = 'date_created';
    public  const UPDATED_AT = 'date_updated';
  protected $table ='membership_plans';
  protected $fillable = ['name', 'stripe_product_id', 'stripe_price_id', 'description', 'price', 'is_available', 'currency', 'interval',
];

    public static function createPlan(object $data): array
    {
        $output = ["error" => true];
        
        try {
            $plan = self::create([
                'name' => $data->name,
                'stripe_product_id' => $data->stripe_product_id,
                'stripe_price_id' => $data->stripe_price_id,
                'description' => $data->description,
                'price' => $data->price,
                'is_available'=>$data->is_available,
                'currency' => $data->currency,
                'interval' => $data->interval
            ]);
            $output["error"] = false;
            $output["id"] = $plan->id;
            $output["code"] = Constants::INSERT_SUCCESS;
        } catch (\Exception $e) {
            $output["msg"] = $e->getMessage();
            $output["code"] = Constants::INSERT_FAILURE;
        }

        return $output;
    }

    public function subscription(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscriptions::class);
}
    public static function updatePlan($id,object $data): array
    {
        $response = ["error" => true];
        
        try {
            $plan = self::findOrFail($id);
            $plan->update([
                'name' => $data->name,
                'stripe_product_id' => $data->stripe_product_id,
                'stripe_price_id' => $data->stripe_price_id,
                'description' => $data->description,
                'price' => $data->price,
                'is_available'=>$data->is_available,
                'currency' => $data->currency,
                'interval' => $data->interval
            ]);
            $response["error"] = false;
            $response["code"] = Constants::INSERT_SUCCESS;
        } catch (ModelNotFoundException $e) {
            $response["msg"] = "Plan not found.";
            $response["error"] = true;
            $response["code"] = Constants::INSERT_FAILURE;
        } catch (\Exception $e) {
            $response["msg"] = $e->getMessage();
            $response["error"] = true;
            $response["code"] = Constants::INSERT_FAILURE;
        }

        return $response;
    }
//    public static function  updatePayment($id,object $data){
//
//        $record = self::find($id);
//        if(!$record){
//            return ['code'=>Constants::INSERT_FAILURE,'message'=>'Record not found'];
//        }
//        $record->name =$data->name;
//        $record->description = $data->description;
//        $record->price = $data->price;
//        $record->currency = $data->currency;
//        $record->interval = $data->interval;
//        return ['code'=>Constants::INSERT_SUCCESS,'message'=>'Record updated successfully'];
//    }

    public static function getID($id)
    {
        return self::find($id);
    }

//    public static function getByQCode($qcode)
//    {
//        return self::where('qcode', $qcode)->first();
//    }

    public static function getPriceByID($id)
    {
        return self::where('id', $id)->value('price');
    }

    public static function getTenureByID($id)
    {
        return self::where('id', $id)->value('interval');
    }

    public static function getAllPlans()
    {
        return self::orderBy('price')->get();
    }

//    public static function getAllActivePlans()
//    {
//        return self::where('is_available', 1)->orderBy('sort_order')->get();
//    }

    public static function getNumAllPlans($status)
    {
        return self::all()->count();
    }

    public  static function getNameByID($id)
    {
        return self::where('id', $id)->value('name');
    }

//    public static function getQCodeByID($id)
//    {
//        return self::where('id', $id)->value('qcode');
//    }

//    public static function isMembershipQcodeExists($qcode)
//    {
//        return self::where('qcode', $qcode)->exists();
//    }

    public static function deletePlan($id)
    {
        try {
            $plan = self::findOrFail($id);
            $plan->delete();
            return true;
        } catch (ModelNotFoundException $e) {
            return false; // Plan not found
        } catch (\Exception $e) {
            return false; // Handle other exceptions if necessary
        }
    }
}
