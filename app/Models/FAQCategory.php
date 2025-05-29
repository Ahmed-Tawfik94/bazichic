<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Constants;
use App\Helpers\CouponGenerator;

class FAQCategory extends Model
{
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';
    protected $table = 'faq_categories'; // specify the table name if different
    protected $fillable = ['title',]; // mass assignable fields

    public static function getNameByID($id)
    {
        return self::where('id', $id)->value('title');
    }

    public static function doesIDExists($id)
    {
        return self::where('id', $id)->exists();
    }

    public static function createCategory($title): array
    {
        try {
            $category = self::create([
                'title' => $title,
            ]);
            return [
                'error' => false,
                'id' => $category->id,
                'code' => Constants::INSERT_SUCCESS,
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'code' => Constants::INSERT_FAILURE,
            ];
        }
    }

    public static function updateCategory($id, $title):array
    {
        try {
            $category = self::findOrFail($id);
            $category->update([
                'title' => $title,
            ]);
            return [
                'error' => false,
                'id' => $category->id,
                'code' => Constants::INSERT_SUCCESS,
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'code' => Constants::INSERT_FAILURE,
            ];
        }
    }

    public  function getFAQSubCategories()
    {
        return $this->hasMany(FAQSubCategory::class, 'category_id');
    }

    public static function getAllFAQSubCategories()
    {
        return FAQSubCategory::orderBy('sort_id')->get();
    }

    public static function deleteCategory($id):array
    {
        try{
            $category= self::destroy($id);
            return [
                'error' => false,
                'id' => $category,
                'code' => Constants::INSERT_SUCCESS,
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'code' => Constants::INSERT_FAILURE,
            ];
        }
    }

    /******************* SUB CATEGORIES *********************/

    public static function createSubCategory($title, $qcode, $category_id)
    {
        try {
            $subCategory = FAQSubCategory::create([
                'title' => $title,
                'qcode' => $qcode,
                'category_id' => $category_id,
            ]);
            return [
                'error' => false,
                'id' => $subCategory->id,
                'code' => Constants::INSERT_SUCCESS,
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'code' => Constants::INSERT_FAILURE,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function updateSubCategory($id, $title, $category_id)
    {
        try {
            $subCategory = FAQSubCategory::findOrFail($id);
            $subCategory->update([
                'title' => $title,
                'category_id' => $category_id,
            ]);
            return [
                'id' => $subCategory->id,
                'code' => Constants::INSERT_SUCCESS,
            ];
        } catch (\Exception $e) {
            return [
                'code' => Constants::INSERT_FAILURE,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function deleteSubCategory($id)
    {
        try{

             FAQSubCategory::destroy($id);
            return [
                'code' => Constants::INSERT_SUCCESS,
            ];
        } catch (\Exception $e){
            return [
                'code' => Constants::INSERT_FAILURE,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function doesSubCategoryIDExists($id)
    {
        return FAQSubCategory::where('id', $id)->exists();
    }

    public static function generateSubCategoryCode()
    {
        $generator = new CouponGenerator();
        $tokenLength = 16;
        $voucherNum = $generator->generate($tokenLength);
        if (self::isCodeValid($voucherNum)) {
            return self::generateSubCategoryCode(); // Recurse if code exists
        }
        return $voucherNum;
    }

    public static function isCodeValid($qcode)
    {
        return FAQSubCategory::where('qcode', $qcode)->exists();
    }
    public function faq(){
        return $this->belongsToMany(FAQ::class);
    }
}
