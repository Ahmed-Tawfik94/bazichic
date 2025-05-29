<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Constants;

class DocumentLike extends Model
{
    const CREATED_AT = 'date_created';
    const UPDATED_AT = null;
    protected $table = 'document_likes';
    protected $fillable = ['user_id', 'doc_id', 'date_created'];

    public static function createLike($user_id, $doc_id, $date_created)
    {
        $response = ['error' => true];
        try {
            $like = self::create([
                'user_id' => $user_id,
                'doc_id' => $doc_id,
                'date_created' => $date_created,
            ]);
            $response['error'] = false;
            $response['id'] = $like->id;
            $response['code'] = Constants::INSERT_SUCCESS;
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
            $response['code'] = Constants::INSERT_FAILURE;
        }
        return $response;
    }
    public  function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getID($id)
    {
        return self::find($id);
    }

    public static function getNumLikes($doc_id)
    {
        return self::where('doc_id', $doc_id)->count();
    }

    public static function getTotalLikesDone($user_id)
    {
        return self::where('user_id', $user_id)->count();
    }

    public static function getNumAllLikes($startDate = null, $endDate = null)
    {
        $query = self::query();
        if ($startDate && $endDate) {
            $query->whereBetween('date_created', [$startDate, $endDate]);
        }
        return $query->count();
    }

    public static function getMyBookmarks($user_id)
    {
        return Document::
            whereIn('id', self::where('user_id', $user_id)->pluck('doc_id'))
            ->where('is_published', 1)
            ->limit(100)
            ->get();
    }

    public static function isLikedBy($user_id, $doc_id)
    {
        return self::where('doc_id', $doc_id)->where('user_id', $user_id)->exists();
    }

    public static function getActionRecordID($user_id, $doc_id)
    {
        return self::where('doc_id', $doc_id)->where('user_id', $user_id)->value('id');
    }

    public static function deleteLike($id)
    {
        return self::destroy($id);
    }

    public static function deleteFav($user_id, $doc_id)
    {
        return self::where('user_id', $user_id)->where('doc_id', $doc_id)->delete();
    }
}
