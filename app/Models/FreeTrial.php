<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Constants;

class FreeTrial extends Model
{
    protected $table = 'free_trials';
    
    protected $fillable = [
        'user_id',
        'plan_id',
        'date_created',
        'date_expiring',
        'status',
        'date_updated'
    ];

    public $timestamps = false; // Disable timestamps if you are not using them

    public static function getByQCode($qcode)
    {
        return self::where('qcode', $qcode)->first();
    }

    public static function updateExpiryDate($qcode, $date_expiring, $date_updated)
    {
        $freeTrial = self::where('qcode', $qcode)->first();

        if ($freeTrial) {
            $freeTrial->date_expiring = $date_expiring;
            $freeTrial->date_updated = $date_updated;

            if ($freeTrial->save()) {
                return [
                    "error" => false,
                    "message" => "Membership request updated successfully."
                ];
            }
        }
        
        return [
            "error" => true,
            "message" => "An error occurred while processing your request. Try again."
        ];
    }

    public static function createTrial($user_id, $plan_id, $date_created, $date_expiring)
    {
        try {
            $freeTrial = self::create([
                'user_id' => $user_id,
                'plan_id' => $plan_id,
                'date_created' => $date_created,
                'date_expiring' => $date_expiring
            ]);

            return [
                "error" => false,
                "id" => $freeTrial->id,
                "code" => Constants::INSERT_SUCCESS,
            ];
        } catch (\Exception $e) {
            return [
                "error" => true,
                "code" => Constants::INSERT_FAILURE,
                "msg" => $e->getMessage()
            ];
        }
    }

    public static function getById($id)
    {
        return self::find($id);
    }

    public static function getByUserId($id)
    {
        return self::where('user_id', $id)->first();
    }

    public static function isIDExists($id)
    {
        return self::where('id', $id)->exists();
    }

    public static function getMyActivePlan($user_id)
    {
        return self::where('user_id', $user_id)
            ->whereRaw('NOW() BETWEEN date_created AND date_expiring')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public static function getMySubscriptionHistory($user_id)
    {
        return self::where('user_id', $user_id)->get();
    }

    public static function getAllPlans()
    {
        return self::orderBy('id', 'DESC')->get();
    }

    public static function deleteTrial($id)
    {
        return self::destroy($id);
    }

    public static function updateStatus($qcode, $status, $date_updated)
    {
        $freeTrial = self::where('qcode', $qcode)->first();

        if ($freeTrial) {
            $freeTrial->status = $status;
            $freeTrial->date_updated = $date_updated;

            if ($freeTrial->save()) {
                return [
                    "error" => false,
                    "message" => "Membership request updated successfully."
                ];
            }
        }

        return [
            "error" => true,
            "message" => "An error occurred while processing your request. Try again."
        ];
    }

    public static function getNumMyPlans($user_id)
    {
        return self::where('user_id', $user_id)->count();
    }

    public static function getNumMyActivePlan($user_id)
    {
        return self::where('user_id', $user_id)
            ->whereRaw('NOW() BETWEEN date_created AND date_expiring')
            ->count();
    }

    public static function getAllMyPlans($user_id)
    {
        return self::where('user_id', $user_id)->orderBy('id', 'DESC')->get();
    }

    public static function getNumTotalActivePlans($plan_id)
    {
        return self::where('plan_id', $plan_id)->count();
    }

    public static function getNumActivePlansFor($user_id, $plan_id)
    {
        return self::where('plan_id', $plan_id)
            ->where('user_id', $user_id)
            ->whereRaw('NOW() BETWEEN date_created AND date_expiring')
            ->count();
    }

    public static function getNumAllPlansFor($user_id, $plan_id)
    {
        return self::where('plan_id', $plan_id)
            ->where('user_id', $user_id)
            ->count();
    }

    public static function getAllMyPlansExcept($user_id, $id)
    {
        return self::where('user_id', $user_id)
            ->where('status', 'Active')
            ->where('id', '!=', $id)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public static function getNumAllActiveTrials()
    {
        return self::whereRaw('NOW() BETWEEN date_created AND date_expiring')->count();
    }

    public static function getNumAllFreeTrials()
    {
        return self::count();
    }
}
