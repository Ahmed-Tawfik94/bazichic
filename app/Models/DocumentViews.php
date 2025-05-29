<?php

namespace App\Models;
use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class DocumentViews extends Model
{
    const UPDATED_AT = 'date_updated';
    const CREATED_AT = 'date_created';
    protected $table = 'document_views';
    protected $fillable = ['document_id', 'user_id'];

    public static function addDocumentView($document_id, $user_id)
 {
    $response = [
        "error" => true,
    ];

    try {
        // Create a new DocumentView instance
        $documentView=self::create([
            'document_id' => $document_id,
            'user_id' => $user_id
        ]);
        // Save the DocumentView instance to the database
        if ($documentView->save()) {
            $response["error"] = false;
            $response["id"] = $documentView->id; // Use Eloquent's auto-incremented ID
            $response["code"] = Constants::INSERT_SUCCESS;
        } else {
            $response["code"] = Constants::INSERT_FAILURE;
        }

        return $response;
    } catch (\Exception $e) {
        // Log the error message instead of echoing
        var_dump($e->getMessage());
        return $response;
    }
 }
}
