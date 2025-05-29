<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedeemTransaction extends Model
{
    protected $table = 'redeem_transactions'; // Table name
    protected $fillable = ['user_id', 'points', 'type', 'status', 'date_created']; // Fillable fields

    public $timestamps = false; // No automatic timestamps

    // Relationship: A redemption belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
