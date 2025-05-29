<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Constants;
use Exception;

class DocumentReview extends Model
{
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';
    protected $table = 'document_reviews';
    protected $fillable = ['doc_id', 'user_id', 'stars', 'text', 'date_created', 'date_updated'];

    public static function addReview($doc_id, $user_id, $stars, $text, $date_created)
    {
        $response = ['error' => true];
        try {
            $review = self::create([
                'doc_id' => $doc_id,
                'user_id' => $user_id,
                'stars' => $stars,
                'text' => $text,
                'date_created' => $date_created,
            ]);
            $response['error'] = false;
            $response['id'] = $review->id;
            $response['code'] = Constants::INSERT_SUCCESS;
        } catch (Exception $e) {
            $response['code'] = Constants::INSERT_FAILURE;
        }
        return $response;
    }

    public static function getReviewedRecordID($user_id, $doc_id)
    {
        return self::where('doc_id', $doc_id)->where('user_id', $user_id)->value('id');
    }

    public static function updateReview($id, $stars, $text, $date_updated)
    {
        $response = [
            'error' => true,
            'code' => Constants::INSERT_FAILURE,
            'message' => "Your request could not be processed.",
        ];
        try {
            $review = self::find($id);
            if ($review) {
                $review->stars = $stars;
                $review->text = $text;
                $review->date_updated = $date_updated;
                $review->save();
                
                $response['code'] = Constants::INSERT_SUCCESS;
                $response['error'] = false;
                $response['message'] = "Your review has been updated successfully.";
            } else {
                $response['message'] = "Failed to update review.";
            }
        } catch (Exception $e) {
            $response['error'] = false;
            $response['message'] = "Error while processing request: " . $e->getMessage();
        }
        return $response;
    }

    public static function getID($id)
    {
        return self::find($id);
    }

    public static function getMyReview($user_id, $doc_id)
    {
        return self::where('user_id', $user_id)->where('doc_id', $doc_id)->first();
    }

    public static function getReviewsFor($doc_id)
    {
        return self::where('doc_id', $doc_id)->orderBy('id', 'DESC')->get();
    }

    public static function getAllReviewsByUser($user_id)
    {
        return self::where('user_id', $user_id)->orderBy('id', 'DESC')->get();
    }

    public static function getAllDocReviews()
    {
        return self::all();
    }

    public static function isReviewedBy($user_id, $doc_id)
    {
        return self::where('doc_id', $doc_id)->where('user_id', $user_id)->exists();
    }

    public static function getNumReviewsFor($doc_id)
    {
        return self::where('doc_id', $doc_id)->count();
    }

    public static function getAvgReviewsFor($doc_id)
    {
        return self::where('doc_id', $doc_id)->avg('stars');
    }

    public static function getTotalReviewsDone($user_id)
    {
        return self::where('user_id', $user_id)->count();
    }

    public static function getNumAllReviews($startDate = null, $endDate = null)
    {
        $query = self::query();
        if ($startDate && $endDate) {
            $query->whereBetween('date_created', [$startDate, $endDate]);
        }
        return $query->count();
    }

    public static function deleteReview($id)
    {
        return self::destroy($id);
    }
}
