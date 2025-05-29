<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities'; // Specify the table name if not plural of the class name

    protected $fillable = [' who_id', 'title','message','data_id','data_title','seen',];

    // If your timestamps are not named 'created_at' and 'updated_at', specify them
    public $timestamps = false;

    // Create a new activity
    public static function createActivity(object $data)
    {
        return self::create([
            'who_id' => $data->who_id,
            'title' => $data->title,
            'message' => $data->message,
            'seen' => 0,
            'data_id' => $data->data_id,
            'data_title' => $data->data_title,
        ]);
    }

    // Get the number of unread activities
    public static function getNumUnreadActivities($who_id)
    {
        return self::where('who_id', $who_id)
            ->where('seen', 0)
            ->count();
    }

    // Update activity status
    public static function updateStatus(int $id,int $status)
    {
        return self::where('id', $id)
            ->update(['seen' => $status]);
    }

    // Get activity by ID
    public static function getID($id)
    {
        return self::find($id);
    }

    // Get activities for a specific user
    public static function getActivitiesFor($who_id, $sno = 1)
    {
        $perPage = 10;
        return self::where('who_id', $who_id)
            ->orderBy('id', 'desc')
            ->skip(($sno - 1) * $perPage)
            ->take($perPage)
            ->get();
    }

    // Get a few activities for a specific user
    public static function getFewActivitiesFor($who_id)
    {
        return self::where('who_id', $who_id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
    }

    // Get the number of activities for a specific user
    public static function getNumActivitiesFor($who_id)
    {
        return self::where('who_id', $who_id)->count();
    }

    // Delete an activity
    public static function deleteActivity($id)
    {
        return self::destroy($id);
    }

    // Get action link based on data title
    public static function getActionLink($data_id, $data_title): string
    {
        if (!empty($data_title) && !empty($data_id)) {
            switch ($data_title) {
                case "Like":
                case "Review":
                    return "book-detail/" . $data_id;

                case "Membership":
                    return "membership-details/" . $data_id;

                default:
                    return "#";
            }
        }
        return "#";
    }

    // Get a few top activities
    public static function getFewTopActivities()
    {
        return self::orderBy('id', 'desc')->limit(10)->get();
    }
}
