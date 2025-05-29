<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocKeywords extends Model
{
    protected $table = 'doc_keywords';
    protected $fillable = [];
    public static function getAllTags($id) {
        $tag = self::find($id);
        return $tag ? $tag->tag : null;
    }
}
