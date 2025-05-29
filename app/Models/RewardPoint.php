<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class RewardPoint extends Model
{
    use HasFactory; // Add soft deletes if needed

    protected $table = 'reward_points'; // Specify the table name if it's different from the default
    protected $fillable = ['user_id', 'points', 'transaction_type', 'status', 'referral_code', 'date_created']; // Specify the fields

    // Optional: If you want to use timestamps automatically
    public  $timestamps = false; // Set to true if you want Eloquent to manage created_at and updated_at fields

    public static function createRewardPoint(object $data)
    {
        return self::create([
            'user_id' => $data->user_id,
            'points'=>$data->points,
            'transaction_type'=>$data->transaction_type,
            'status'=>$data->status,

        ]);
    }

    public static function createOrUpdateRewardPoint(int $userId, object $data)
    {
        self::create([
            'user_id' => $userId,
            'points' => $data->points,
            'transaction_type' => $data->transaction_type,
            'status' => $data->status,
            'date_created' => date('Y-m-d H:i:s'),
        ]);
    }

//    public static function createTransaction(array $data)
//    {
//        return ReferralTransaction::create($data); // Assuming you have a Transaction model
//    }

    public static function getAllMyRewardPoints($userId)
    {
        return self::where('user_id', $userId)->orderBy('id', 'DESC')->get();
    }

    public static function getAllRewardPoints()
    {
        return self::orderBy('id', 'DESC')->get();
    }

    public static function getRewardPointsSummary()
    {
        return self::select('user_id')
            ->groupBy('user_id')->sum('points');
    }

    public static function getCurrentRewardPointFor($userId)
    {
        // Calculate total earned points (sum from referrals)
        $totalEarned = Referral::where('referrer_id', $userId)
            ->where('status', 1) // Only count approved referrals
            ->selectRaw('reward_point_id, COUNT(*) as referral_count')
            ->groupBy('reward_point_id')
            ->get()
            ->sum(function ($referral) {
                $rewardPoint = RewardPoint::find($referral->reward_point_id);
                return $rewardPoint ? ($rewardPoint->points * $referral->referral_count) : 0;
            });

        // Calculate total redeemed points using RedeemTransaction model
        $totalRedeemed = RedeemTransaction::where('user_id', $userId)
            ->where('status', 1) // Only count approved redemptions
            ->sum('points');

        // Ensure balance does not go negative
        return max($totalEarned - $totalRedeemed, 0);
    }
    
    public static function getOriginalRewardPoints($userId)
    {
        // Calculate total earned points (sum from referrals)
        $totalEarned = Referral::where('referrer_id', $userId)
            ->where('status', 1) // Only count approved referrals
            ->selectRaw('reward_point_id, COUNT(*) as referral_count')
            ->groupBy('reward_point_id')
            ->get()
            ->sum(function ($referral) {
                $rewardPoint = RewardPoint::find($referral->reward_point_id);
                return $rewardPoint ? ($rewardPoint->points * $referral->referral_count) : 0;
            });


        // Ensure balance does not go negative
        return max($totalEarned, 0);
    }

    public static function getNumPeopleRewarded($startDate = null, $endDate = null)
    {
        $query = self::distinct('user_id');
        if ($startDate && $endDate) {
            $query->whereBetween('date_created', [$startDate, $endDate]);
        }
        return $query->count('user_id');
    }

    public static function getTotalRewardPointsAwarded($startDate = null, $endDate = null)
    {
        $query = self::query();
        if ($startDate && $endDate) {
            $query->whereBetween('date_created', [$startDate, $endDate]);
        }
        return $query->sum('points') ?? 0;
    }

    // Additional methods can be added here based on your needs.
}
