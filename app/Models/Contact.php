<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Constants;
use Exception;

class Contact extends Model
{
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';
    protected $table = 'contacts';
    protected $fillable = ['name', 'user_id', 'email', 'subject', 'message',];

    public static function createContact(object $data)
    {
        $response = [
            "error" => true,
            "msg" => "",
            "code" => Constants::INSERT_FAILURE,
        ];

        try {
            $contact = self::create([
                "name" => $data->name,
                "user_id" => $data->user_id,
                "email" => $data->email,
                "subject" => $data->subject,
                "message" => $data->message
            ]);
            $response["error"] = false;
            $response["id"] = $contact->id;
            $response["code"] = Constants::INSERT_SUCCESS;
        } catch (Exception $e) {
            $response["msg"] = $e->getMessage();
        }

        return $response;
    }

    public static function getID($id)
    {
        return self::find($id);
    }

    public static function getAllMessages()
    {
        return self::orderBy('id', 'DESC')->get();
    }

    public static function getNumMessages($startDate = null, $endDate = null)
    {
        $query = self::query();
        if ($startDate && $endDate) {
            $query->whereBetween('date_created', [$startDate, $endDate]);
        }
        return $query->count();
    }

    public static function deleteContact($id)
    {
        return self::destroy($id);
    }
}
