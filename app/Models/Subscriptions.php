<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\Constants;
use App\Helpers\CouponGenerator;
use App\Models\Plan;
use Carbon\Carbon;
class Subscriptions extends Model
{
    use HasFactory;

    protected $table = 'subscriptions'; // Define the table name
    protected $fillable = ['user_id', 'membership_plan_id', 'stripe_subscription_id', 'price_id', 'status', 'trial_end_date', 'start_date', 'end_date',
    'remark'];

//    public static function getByQCode($qcode)
//    {
//        return self::where('qcode', $qcode)->first();
//    }

//    public static function isRefQCodeExists($qcode)
//    {
//        return self::where('qcode', $qcode)->exists();
//    }

    public static function generateCode()
    {
        $generator = new CouponGenerator();
        $tokenLength = 16;
        $voucherNum = $generator->generate($tokenLength);

        if (self::isCodeValid($voucherNum)) {
            return self::generateCode(); // Recursive call to generate a new code
        }

        return $voucherNum;
    }

//    public static function isCodeValid($qcode)
//    {
//        return self::where('qcode', $qcode)->exists();
//    }

    public static function updateExpiryDate($membership_plan_id, $date_expiring, $date_updated)
    {
        $membership = self::where('membership_plan_id', $membership_plan_id)->first();

        if ($membership) {
            $membership->date_expiring = $date_expiring;
            $membership->date_updated = $date_updated;
            $membership->save();

            return [
                'error' => false,
                'message' => 'Membership request updated successfully.'
            ];
        }

        return [
            'error' => true,
            'message' => 'Membership not found.'
        ];
    }

    public static function createMembership(object $data): array
    {
        $response = [
            'error' => true
        ];

        try {
            $membership = self::updateOrCreate(
                [
                    'user_id' => $data->user_id,
                    'stripe_subscription_id' => $data->stripe_subscription_id
                ],
                [
                    'membership_plan_id' => $data->membership_plan_id,
                    'price_id' => $data->price_id,
                    'status' => $data->status,
                    'trial_end_date' => $data->trial_end_date,
                    'start_date' => $data->start_date,
                    'end_date' => $data->end_date,
                    'remark' => $data->remark

                ]);
            $response['error'] = false;
            $response['id'] = $membership->id;
            $response['code'] = Constants::INSERT_SUCCESS;
        } catch (\Exception $e) {
            $response['code'] = Constants::INSERT_FAILURE;
            $response['msg'] = $e->getMessage();
        }

        return $response;
    }

    public static function addNewSubscriptionLog($log, $date_created, $date_expiring)
    {
        // Assume SubscriptionLog is another model
        return SubscriptionLog::create([
            'log' => $log,
            'date_created' => $date_created,
            'date_expiring' => $date_expiring
        ]);
    }

    public static function getID($id)
    {
        return self::find($id);
    }

    public static function isIDExists($id)
    {
        return self::where('id', $id)->exists();
    }

    public static function getMyActivePlan($user_id)
    {
//        ['active', 'trialing', 'canceled', 'past_due', 'unpaid']
        return self::where('user_id', $user_id)
            ->where('status', 'active')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public static function getMyPendingPlan($user_id)
    {
        return self::where('user_id', $user_id)
            ->where('status', 'canceled|past_due|unpaid')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public static function getMySubscriptionHistory($id)
    {
        return self::where('user_id', $id)
            ->where('status', 'active')
            ->orderBy('id', 'DESC')
            ->get();
    }

    public static function getDetailByID($id)
    {
        return self::with('membershipPlans') // Assuming a relationship exists
        ->where('id', $id)
            ->where('status', 'Active')
            ->first();
    }

    public static function getAllPlans()
    {
        return self::orderBy('id', 'DESC')->get();
    }

    public static function getPurchasedPlansList()
    {
        return self::where('status', 'Active')->orderBy('id', 'DESC')->get();
    }

    public static function getPosterID($id)
    {
        return self::where('id', $id)->value('user_id');
    }

    public static function getNameByID($id)
    {
        return self::where('id', $id)->value('title');
    }

    public static function deleteMembership($id)
    {
        return self::destroy($id);
    }

    public static function updateStatus($qcode, $status, $date_updated)
    {
        $membership = self::where('qcode', $qcode)->first();

        if ($membership) {
            $membership->status = $status;
            $membership->date_updated = $date_updated;
            $membership->save();

            return [
                'error' => false,
                'message' => 'Membership request updated successfully.'
            ];
        }

        return [
            'error' => true,
            'message' => 'Membership not found.'
        ];
    }

    public static function getNumMyPlans($user_id)
    {
        return self::where('user_id', $user_id)->count();
    }

    public static function getNumPlanSales($plan_id, $startDate = null, $endDate = null)
    {
        $query = self::where('membership_plan_id', $plan_id);
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        return $query->count();
    }

    public static function getNumMyActivePlan($user_id)
    {
        return self::where('user_id', $user_id)
            ->where('status', 'active')
            ->count();
    }

    public static function getAllMyPlans($user_id, $status)
    {
        return self::where('user_id', $user_id)
            ->where('status', $status)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public static function getNumTotalActivePlans($plan_id, $startDate = null, $endDate = null)
    {
        return self::where('membership_plan_id', $plan_id)
            ->where('status', 'active')
            ->count();
    }

    public static function getSumAllTransactions($mode)
    {
        return Payment::where('mode', $mode)
            ->where('status', 'Completed')
            ->sum('amount');
    }

    public static function getSumAllTransactionsBetween($status, $date_start, $date_end)
    {
        return Payment::where('status', $status)
            ->whereBetween('created_at', [$date_start, $date_end])
            ->sum('amount');
    }

    public static function getAllMyPlansExcept($user_id, $id)
    {
        return self::where('user_id', $user_id)
            ->where('status', 'active|trailing')
            ->where('id', '!=', $id)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public static function getAllUserPlansList()
    {
        return self::where('status', 'active')->orderBy('id', 'DESC')->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}