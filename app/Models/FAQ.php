<?php

namespace App\Models;

use App\Helpers\Constants;
use App\Helpers\CouponGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FAQ extends Model
{
    use HasFactory;
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    protected $table = 'faqs'; // Specify the table name if it doesn't follow convention

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'subcategory_id',
        'qcode',
        'url',
        'is_published'
    ];

    public static function createFAQ(object $data): array
    {

        try {
            $faq = self::create([
                'title'=>$data->title,
                'description'=>$data->description,
                'category_id'=>$data->category_id,
                'subcategory_id'=>$data->subcategory_id,
                'url'=>$data->url,
                'is_published'=>$data->is_published,
            ]);

            return[
                'id' => $faq->id,
                'code' => Constants::INSERT_SUCCESS];
        } catch (\Exception $e) {
            // Log or handle exception as needed
            return[
                'message'=>$e->getMessage(),
                'code' => Constants::INSERT_FAILURE];

        }
    }

    public static function updateFAQ(object $data):array
    {
        try {
            $faq = self::findOrFail($data->id);
            $faq->title = $data->title;
            $faq->description = $data->description;
            $faq->category_id=$data->category_id;
            $faq->subcategory_id = $data->subcategory_id;
            $faq->url=$data->url;
            $faq->is_published =$data->is_published;
            $faq->save();
            return[
                'id' => $faq->id,
                'code' => Constants::INSERT_SUCCESS];
        } catch (\Exception $e) {
            return[
                'message'=>$e->getMessage(),
                'code' => Constants::INSERT_FAILURE];
        }

    }

    public static function generateCode()
    {
        $generator = new CouponGenerator();
        $tokenLength = 16;
        $voucherNum = $generator->generate($tokenLength);

        if (self::isCodeValid($voucherNum)) {
            return self::generateCode();
        }

        return $voucherNum;
    }

    public static function isCodeValid($qcode)
    {
        return self::where('qcode', $qcode)->exists();
    }

    public static function isUFNExists($url, $exceptionID = 0)
    {
        $query = self::where('url', $url);
        if ($exceptionID > 0) {
            $query->where('id', '!=', $exceptionID);
        }
        return $query->exists();
    }

    public static function recheckQCode($qcode, $id)
    {
        return self::where('qcode', $qcode)->where('id', '!=', $id)->exists();
    }

    public static function getID($id)
    {
        return self::find($id);
    }

    public static function getByQCode($qcode)
    {
        return self::where('qcode', $qcode)->first();
    }

    public static function getBySEOUrl($url)
    {
        return self::where('url', $url)->first();
    }

    public static function getNameByID($id)
    {
        return self::where('id', $id)->value('title');
    }

    public static function getIDByQCode($qcode)
    {
        return self::where('qcode', $qcode)->value('id');
    }

    public static function getQCode($id)
    {
        return self::where('id', $id)->value('qcode');
    }

    public static function doesIDExists($id)
    {
        return self::where('id', $id)->exists();
    }

    public static function getNumFAQsInCategory($category_id)
    {
        return self::where('category_id', $category_id)->count();
    }

    public static function getNumFAQsInSubCategory($subcategory_id)
    {
        return self::where('subcategory_id', $subcategory_id)->count();
    }

    public static function getAllFAQs($is_published = 1)
    {
        return self::when($is_published, function ($query) {
            return $query->where('is_published', 1);
        })->orderBy('id', 'desc')->get();
    }

    public static function deleteFAQ($id):array
    {
        try {
            self::destroy($id);
            return[
                'code' => Constants::INSERT_SUCCESS];
        }catch (\Exception $e){
            return[
                'message'=>$e->getMessage(),
                'code' => Constants::INSERT_FAILURE];
        }
    }

    public function category()
    {
        return $this->hasOne(FAQCategory::class,'id');
    }
    public function sub_category()
    {
        return $this->hasOne(FAQSubCategory::class,'id');
    }
}
