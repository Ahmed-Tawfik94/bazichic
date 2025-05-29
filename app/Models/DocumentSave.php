<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Constants;

class DocumentSave extends Model
{
    protected $table = 'document_saves';
    
    protected $fillable = [
        'user_id',
        'doc_id',
        'page',
        'progress',
        'date_created',
        'date_updated',
    ];

    public $timestamps = false; // Set to true if you want to use created_at and updated_at columns

    public static function createSave($user_id, $doc_id, $page, $progress, $date_created)
    {
        $response = [
            'error' => true,
        ];

        try {
            $save = self::create([
                'user_id' => $user_id,
                'doc_id' => $doc_id,
                'page' => $page,
                'progress' => $progress,
                'date_created' => $date_created,
            ]);

            $response['error'] = false;
            $response['id'] = $save->id;
            $response['code'] = Constants::INSERT_SUCCESS;
        } catch (\Exception $e) {
            // Log error or handle it as needed
            $response['code'] = Constants::INSERT_FAILURE;
        }

        return $response;
    }

    public static function updateSave($id, $page, $progress, $date_updated)
    {
        $response = [
            'error' => true,
            'code' => Constants::INSERT_FAILURE,
            'message' => 'Your request could not be processed.',
        ];

        try {
            $save = self::findOrFail($id);
            $save->update([
                'page' => $page,
                'progress' => $progress,
                'date_updated' => $date_updated,
            ]);

            $response['error'] = false;
            $response['code'] = Constants::INSERT_SUCCESS;
            $response['message'] = 'Your read has been saved successfully.';
        } catch (\Exception $e) {
            $response['message'] = 'Error while processing request: ' . $e->getMessage();
        }

        return $response;
    }

    public static function getNumSaves($doc_id)
    {
        return self::where('doc_id', $doc_id)->count();
    }

    public static function getNumAllMySaves($user_id)
    {
        return self::where('user_id', $user_id)->groupBy('doc_id')->count();
    }

    public static function getNumAllSaves($startDate = null, $endDate = null)
    {
        $query = self::query();
        if ($startDate && $endDate) {
            $query->whereBetween('date_created', [$startDate, $endDate]);
        }
        return $query->count();
    }

    public static function isSavedBy($user_id, $doc_id)
    {
        return self::where('doc_id', $doc_id)->where('user_id', $user_id)->exists();
    }

    public static function getLastSavedPage($user_id, $doc_id)
    {
        return self::where('doc_id', $doc_id)->where('user_id', $user_id)->value('page') ?? 0;
    }

    public static function getAllMySaves($user_id)
    {
        return self::with('document') // Assuming you have a relationship defined
            ->where('user_id', $user_id)
            ->orderBy('date_updated', 'desc')
            ->get();
    }

    public static function deleteSave($user_id, $doc_id)
    {
        return self::where('user_id', $user_id)->where('doc_id', $doc_id)->delete();
    }

    // Define relationship with Document model if needed
    public static function getActionRecordID($user_id, $doc_id)
    {
        return self::where('doc_id', $doc_id)->where('user_id', $user_id)->value('id');
    }

    public  function document()
    {
        return $this->belongsTo(Document::class, 'doc_id');
    }
}
