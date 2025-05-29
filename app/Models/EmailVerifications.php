<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerifications extends Model
{
    protected $table = 'email_verifications';
    protected $fillable = ['user_id','token','expires_at','verified_at'];


    public static function createVerification($user_id,$token){
        $record = self::create(
            [
                'user_id'=>$user_id,
                'token'=>$token,
                'expires_at'=>date('Y-m-d H:i:s', strtotime('+1 hour'))
            ]
        );
        if($record){
            return $record->token;
        }
        return false;
    }
    public static function verify($token): array
    {
        $record = self::where('token', $token)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->first();

        if (!$record) {
            return [
                'status' => 'error',
                'message' => 'Invalid or expired token.'
            ];
        }

        if ($record->verified_at) {
            return [
                'status' => 'error',
                'message' => 'Token has already been used.'
            ];
        }

        $record->update(['verified_at' => date('Y-m-d H:i:s')]);
        return [
            'status' => 'success',
            'user_id' => $record->user_id
        ];
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
