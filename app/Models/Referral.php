<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Helpers\Constants;

class Referral extends Model
{
    use HasFactory;
    protected $table = 'referrals';
    protected $fillable = ['referrer_id', 'referred_id', 'referral_code', 'points_awarded', 'status', 'transaction_type', 'reward_point_id',
    ];
    
    public $timestamps = false; // Set to true if you have created_at and updated_at columns
    
    public function referral()
    {
        return $this->hasOne(Referral::class, 'reward_point_id');
    }

    public static function createReferral(array $data)
    {
        try {
            $referral = self::create($data);
            return ['code'=>Constants::INSERT_SUCCESS,'message'=>'Referral Created Successfully','data'=>$referral];

        } catch (\Exception $e) {
            return ['code'=>Constants::INSERT_FAILURE,'message'=>$e->getMessage()];
        }
    }

    /**
     * Approve referral and award points.
     */
    public static function approveReferral(int $referredId)
    {
        $referral = self::where('referred_id', $referredId)->where('status', 0)->first();
        if ($referral) {
            $referral->update(['status' => 1]); // Approve referral

            RewardPoint::createOrUpdateRewardPoint($referral->referrer_id, (object)[
                'points' => $referral->points_awarded,
                'transaction_type' => 'Referral',
                'status' => 1, // Approved
            ]);
        }
    }

    // Relationship: A referral is linked to reward points
    public function rewardPoint()
    {
        return $this->belongsTo(RewardPoint::class, 'reward_point_id');
    }

    public static function createTransaction($data)
    {
        return ReferralTransaction::create($data); // Assuming you have a ReferralTransaction model
    }

    public static function updateReferral($code, $status, $date_updated)
    {
        return self::where('code', $code)->update([
            'status' => $status,
            'date_updated' => $date_updated,
        ]);
    }

    public static function getID($id)
    {
        return self::find($id);
    }
    public static function getNumMyRefeerals($id)
    {
        return self::where('referrer_id',$id)->count();
    }

    public static function getAllMyReferrals($user_id)
    {
        return self::where('referrer_id', $user_id)->get();
    }

    public static function getMyConnections($ref_user_id)
    {
        return self::where('referrer_id', $ref_user_id)->get(); // Assuming you have a User model
    }

    public static function getStatus($id)
    {
        return self::where('id', $id)->value('status');
    }

    public static function getUserID($code)
    {
        return self::where('code', $code)->value('user_id');
    }

    public static function getNumMyReferrals($user_id, $status = null)
    {
        $query = self::where('user_id', $user_id);
        if (!empty($status)) {
            $query->where('status', $status);
        }
        return $query->count();
    }

    public static function getNumAllSystemReferrals()
    {
        return self::count();
    }

    public static function getNumMyConnections($ref_user_id)
    {
        return User::where('ref_user_id', $ref_user_id)->count(); // Assuming you have a User model
    }

    // Count how many times a referral code was used
    public static function getNumRedeems($referralCode)
    {
        return self::whereHas('rewardPoint', function ($query) use ($referralCode) {
            $query->where('referral_code', $referralCode);
        })->count();
    }

    public static function isReferralCodeExist($code)
    {
        return self::where('code', $code)->exists();
    }

    public static function isReferralCodeValid($code)
    {
        $status = 'Pending';
        return self::where('code', $code)->where('status', $status)->exists();
    }

    public function generateCode(Request $request, Response $response)
    {
        $userId = $_SESSION['user_id']; // Adjust based on your session structure
        $user = User::find($userId);

        if (!$user) {
            return $response->withJson(['success' => false, 'message' => 'User not found.'], 404);
        }

        if ($user->referral_code) {
            return $response->withJson(['success' => false, 'message' => 'Referral code already exists.'], 400);
        }

        $referralCode = $this->generateReferralCode($userId, $user->first_name);
        $user->update(['referral_code' => $referralCode]);

        return $response->withJson(['success' => true, 'referral_code' => $referralCode]);
    }

    /**
     * Generate a unique referral code.
     */
    private function generateReferralCode(int $userId, string $firstName): string
    {
        $prefix = "BC";
        $initials = strtoupper(substr($firstName, 0, 2));
        $uniqueId = strtoupper(bin2hex(random_bytes(3)));
        return "{$prefix}{$userId}{$initials}{$uniqueId}";
    }
}
