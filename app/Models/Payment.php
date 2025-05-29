<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'transaction_details';

    protected $fillable = ['item_code', 'sender_id', 'amount', 'item', 'status', 'currency', 'txn_id',
    ];

    public  $timestamps = false; // If you don't have created_at and updated_at columns

    public static function isRefQCodeExists($refCode)
    {
        return self::where('refCode', $refCode)->exists();
    }

    public static function getByRefCode($refCode)
    {
        return self::where('refCode', $refCode)->first();
    }

    public static function createPayment(object $data)
    {
        return self::updateOrCreate(
            [
                'sender_id'=>$data->sender_id,
                'txn_id'=>$data->txn_id
            ],
            [
            'item_code'=>$data->item_code,
            'amount'=>$data->amount,
            'item'=>$data->item,
            'status'=>$data->status,
            'currency'=>$data->currency,
            'txn_id'=>$data->txn_id,
        ]);
    }

    public static function getID($id)
    {
        return self::find($id);
    }
    public static function getSumAllTransactions($status)
    {
        return self::where('status', $status)
            ->sum('amount');
    }

    public static function getMyPayments($user_id)
    {
        return self::where('user_id', $user_id)->orderBy('id', 'DESC')->get();
    }

    public static function getAllTransactions()
    {
        return self::orderBy('id', 'DESC')->get();
    }

    public static function getUserByTXNID($txn_id)
    {
        return self::where('txn_id', $txn_id)->value('user_id');
    }

    public static function getTXNByItemID($item_code)
    {
        return self::where('item_code', $item_code)->value('txn_id');
    }

    public static function getTXNByQcode($refCode)
    {
        return self::where('refCode', $refCode)->value('txn_id');
    }

    public static function getTXNModeByQcode($refCode)
    {
        return self::where('stripe_subscription_id', $refCode)->value('mode');
    }

    public static function getTXNStatusByQcode($refCode)
    {
        return self::where('refCode', $refCode)->value('status');
    }

    public static function isPlanTransactionExists($user_id, $status)
    {
        return self::where('user_id', $user_id)->where('status', $status)->exists();
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public static function getSumTransactionsByDateRange(string $status,  $startDate,  $endDate): float
    {
        $data = [];
        if ($startDate && $endDate){
         $data = self::where('status', $status)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        }else{
            $data = self::where('status', $status)
                ->sum('amount');
        }

        return $data;
    }
}
