<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreTag extends Model
{
    protected $table = 'store_tags';
    protected $fillable = [];

    public static function getAllRibbonTags()
    {
        return self::all();
    }
}
