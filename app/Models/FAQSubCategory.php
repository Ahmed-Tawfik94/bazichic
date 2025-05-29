<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQSubCategory extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table ='faq_sub_categories';
    protected $fillable = ['title','qcode','category_id','sort_id'];
    public static function getSubCategoryByID($id){
        
    }
    public function faq(){
        return $this->belongsToMany(FAQ::class);
    }
}
