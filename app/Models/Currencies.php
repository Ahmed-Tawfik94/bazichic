<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    protected $table = 'currencies';
    protected $fillable = [];

    public static function getAllCurrencies()
    {
        return self::all();
    }
}
