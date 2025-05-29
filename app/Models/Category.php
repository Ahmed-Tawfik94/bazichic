<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\Constants;
use App\Helpers\CouponGenerator;
use Exception;

class Category extends Model
{
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';
    protected $table = 'categories';
    protected $fillable = ['title', 'description', 'is_published', 'qcode', 'magazine_only', 'taxonomy',
    ];

    public static function getNumAllCategories(int $is_published, $startDate = null, $endDate = null)
    {
        $query = self::where('is_published', $is_published);
        if ($startDate && $endDate) {
            $query->whereBetween('date_created', [$startDate, $endDate]);
        }
        return $query->count();
    }

    public static function createCategory($data)
    {
        try {
            $data = (object)$data;
            $category = self::create([
                'title'=>$data->title,
                'description'=>$data->description,
                'is_published'=>$data->is_published,
                'qcode'=>$data->qcode,
                'magazine_only'=>$data->magazine_only,
                'taxonomy'=>$data->taxonomy
            ]);
            return [
                'error'=>false,
                'id'=>$category->id,
                'code'=>Constants::INSERT_SUCCESS
            ];
        } catch (Exception $e) {
            return [
                "error" => true,
                "message" => $e->getMessage(),
                "code" => Constants::INSERT_FAILURE,
            ];
        }

    }

    public static function updateCategory($id, $data)
    {
        try {
            $category = self::findOrFail($id);
            $data = (object) $data;
            $category->update([
                'title'=>$data->title,
                "description"=>$data->description,
                "taxonomy"=>$data->taxonomy,
                "magazine_only"=>$data->magazine_only,
                "is_published"=>$data->is_published,
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function generateCode()
    {
        $generator = new CouponGenerator();
        $tokenLength = 16;

        do {
            $voucherNum = $generator->generate($tokenLength);
        } while (self::isCodeTaken($voucherNum)); // Loop if the code already exists

        return $voucherNum;
    }

    public static function isCodeTaken($qcode)
    {
        return self::where('qcode', $qcode)->exists(); // Returns true if the code exists (taken)
    }

    public static function recheckQCode($qcode, $id)
    {
        return self::where('qcode', $qcode)->where('id', '!=', $id)->exists();
    }

    public static function getID($id)
    {
        return self::find($id);
    }

    public static function getNameByID($id)
    {
        return self::where('id', $id)->value('title');
    }

    public static function getIDByQCode($qcode)
    {
        return self::where('qcode', $qcode)->value('id');
    }

    public static function doNameExists($title)
    {
        return self::where('title', $title)->exists();
    }

    public static function getAllCategories($is_published = 1)
    {
        return self::when($is_published == 1, function ($query) {
            return $query->where('is_published', 1);
        })->orderBy('id', 'DESC')->get();
    }

    public static function getActiveEbookCategories()
    {
        return self::where('is_published', 1)->where('magazine_only', 0)->orderBy('id', 'DESC')->get();
    }

    public static function getAllMagazineCategories()
    {
        return self::where('is_published', 1)->where('magazine_only', 1)->get();
    }


    public static function deleteCategory($id)
    {
        return self::destroy($id);
    }

    public static function updateImage($id, $image)
    {
        $response = [
            "error" => true,
            "message" => "",
        ];

        try {
            if (!empty($image)) {
                $path = "images/categories/" . $id . ".jpg";
                $actualpath = $path;

                file_put_contents("uploads/" . $path, base64_decode($image));

                $category = self::findOrFail($id);
                $category->image = $actualpath;
                $category->save();

                $response["message"] = "Category image updated successfully.";
                $response["error"] = false;
            }
        } catch (Exception $e) {
            $response["message"] = "Could not upload category Image.";
        }

        return $response;
    }

    public static function isCodeValid(string $url)
    {
        return self::where('qcode', $qcode)->exists();
    }
}
