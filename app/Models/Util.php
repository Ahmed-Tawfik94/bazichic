<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Helpers\Constants;
use Exception;
use Carbon\Carbon;
use PDOException;

class Util extends Model
{
    protected $table = 'users'; // Set this to the appropriate table name if different
    protected $fillable = ['user_name']; // Add any fillable attributes here

    public static function dateDiffInDays($date1, $date2)
    {
        $diff = strtotime($date2) - strtotime($date1);
        return abs(round($diff / 86400));
    }

    public static function createNewUsername($length)
    {
        return self::createUniqueString($length, "abcdefghijklmnopqrstuvwxyz0123456789", 'isUserNameExists');
    }

    public static function generateTXNID($length)
    {
        return self::createUniqueString($length, "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 'isTXNIDExists');
    }

    public static function generateOTP($length)
    {
        return self::createUniqueString($length, "0123456789", 'isUserNameExists');
    }

    public static function createFileName()
    {
        return self::createUniqueString(8, "abcdefghijklmnopqrstuvwxyz0123456789", 'isUserNameExists');
    }

    private static function createUniqueString($length, $characters, $existsMethod)
    {
        $name ='';
        try{
            do {
                $name = Str::random($length);
            } while (self::$existsMethod($name));
        } catch (Exception $e){
            printf("%s\n", $e->getMessage());
        }


        return $name;
    }

    public static function isUserNameExists($user_name)
    {
        return self::where('user_name', $user_name)->exists();
    }

    public static function isTXNIDExists($txn_id)
    {
        return DB::table('transaction_details')->where('txn_id', $txn_id)->exists();
    }

    public static function generateApiKey()
    {
        return md5(uniqid(rand(), true));
    }

    private static function getIntervalFromSeconds($seconds)
    {
        $days = floor($seconds / (24 * 60 * 60));
        $hours = floor(($seconds / (60 * 60)) % 24);
        $minutes = floor(($seconds / 60) % 60);

        if ($days > 0) {
            return ($days >= 30) ? floor($days / 30) . " months" : $days . " days";
        }

        if ($hours > 0) {
            return $hours . ($hours > 1 ? " hours" : " hour");
        }

        return ($minutes >= 1) ? $minutes . " minutes" : "few seconds";
    }

    public static function getTimeDifference($datetime, $full = false): string
    {
        $now = Carbon::now();
        $ago = Carbon::parse($datetime);
        $diff = $now->diff($ago);

        $timeStrings = [
            'y' => $diff->y . ' year' . ($diff->y > 1 ? 's' : ''),
            'm' => $diff->m . ' month' . ($diff->m > 1 ? 's' : ''),
            'd' => $diff->d . ' day' . ($diff->d > 1 ? 's' : ''),
            'h' => $diff->h . ' hour' . ($diff->h > 1 ? 's' : ''),
            'i' => $diff->i . ' minute' . ($diff->i > 1 ? 's' : ''),
            's' => $diff->s . ' second' . ($diff->s > 1 ? 's' : ''),
        ];

        // Filter out zero values
        $timeStrings = array_filter($timeStrings, fn($value, $key) => $diff->$key > 0, ARRAY_FILTER_USE_BOTH);

        if ($full) {
            return !empty($timeStrings)
                ? implode(', ', $timeStrings) . ' ago'
                : 'just now';
        }

        return !empty($timeStrings)
            ? reset($timeStrings) . ' ago'
            : 'just now';
    }


    public static function getFormalDate($date_created): string
    {
        try {
            return Carbon::parse($date_created)->format('d F Y');
        } catch (Exception $e) {
            return $date_created;
        }
    }

    /***** START OF REFERRAL CODE *******/
    public static function generateReferralCode($length = 8): string
    {
        return self::createUniqueString($length, "QWERTYUIOPASDFGHJKLZXCVBNM0123456789", 'isCodeExists');
    }

    public static function isCodeExists($code): bool
    {
        return Referral::where('code', $code)->exists();
    }
    /***** END OF REFERRAL CODE *******/

    /************* PAYPAL TESTS **************/
    public static function createPayTest($user_id, $title, $body): array
    {
        $response = ["error" => true];

        try {
            $response["id"] = DB::table('paypal_tests')->insertGetId([
                'user_id' => $user_id,
                'title' => $title,
                'body' => $body,
            ]);
            $response["error"] = false;
            $response["code"] = Constants::INSERT_SUCCESS;
        } catch (PDOException $e) {
            $response["code"] = Constants::INSERT_FAILURE;
            $response["msg"] = $e->getMessage();
        }

        return $response;
    }
    /************** END OF TESTS*************/
}
