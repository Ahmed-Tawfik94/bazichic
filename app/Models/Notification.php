<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Constants;

class Notification extends Model
{
//    use SoftDeletes; // If you want to enable soft deletes
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';
    const DELETED_AT = null;
    protected $table = 'notifications';

    protected $fillable = [
        'sender_id',
        'title',
        'message',
        'seen',
        'date_created'
    ];

    // Create a new notification
    public static function createNotification(object $data): array
    {
        try {
            $notification = self::create([
                'sender_id' => $data->sender_id,
                'title' => $data->title,
                'message' => $data->message,
                'seen' => $data->seen,
            ]);
            return [
                'error' => false,
                'id' => $notification->id,
                'code' => Constants::INSERT_SUCCESS
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'msg' => $e->getMessage(),
                'code' => Constants::INSERT_FAILURE
            ];
        }
    }

    // Get number of unread notifications
    public static function getNumUnreadNotifications($receiver_id)
    {
        return self::withoutGlobalScopes()->where('receiver_id', $receiver_id)
            ->where('seen', 0)
            ->count();
    }

    // Update notification status
    public static function updateStatus($id, $seen)
    {
        return self::withoutGlobalScopes()->where('id', $id)
            ->update(['seen' => $seen]);
    }

    // Get notification by ID
    public static function getById($id)
    {
        return self::find($id);
    }

    // Get notifications for a user with pagination
    public static function getNotificationsFor($receiver_id, $page = 1, $limit = 10)
    {
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;

        // Build and execute the query
        return self::withoutGlobalScopes()
            ->where('receiver_id', $receiver_id)
            ->orderBy('date_created', 'DESC')
            ->skip($offset)
            ->take($limit)
            ->get();

    }

    // Get a few notifications for a user
    public static function getFewNotificationsFor($receiver_id)
    {
        return self::where('receiver_id', $receiver_id)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get();
    }

    // Get total notifications for a user
    public static function getNumAllNotisFor($receiver_id)
    {
        return  self::withoutGlobalScopes()->where('receiver_id', $receiver_id)->get()->count();
    }

    // Delete a notification
    public static function deleteNotification($id): int
    {
        return self::destroy($id);
    }
    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Generate action link based on data title
    public static function getActionLink($data_id, $data_title): string
    {
        if (!empty($data_title) && !empty($data_id)) {
            switch ($data_title) {
                case "Like":
                case "Review":
                    return "book-detail/" . $data_id;

                case "Membership":
                    return "my-subscriptions";

                case "Registration":
                    return "view-profile/" . $data_id;

                default:
                    return "#";
            }
        }
        return "#";
    }
}
