<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsCategory extends Model
{
    protected $guarded = ['id'];

    public static function getChildren($parentId=0){
        return static::where(['is_show'=>1,'parent_id'=> $parentId])->orderBy('order' ,'asc')->get();
    }
    public static function getAllCategoryId($parentId){
        $categories                             =   static ::where('parent_id' , $parentId)->orderBy('order' ,'asc')->get();
        $allCategories[]                        =   $parentId;
        if (!empty($categories)) {
            foreach ($categories as $key => $category) {
                $allCategories[]                =   $category->id;

                $categoryChild                  =   static ::getAllCategoryId($category->id);
                if (!empty($categoryChild)) {
                    foreach ($categoryChild as $row){
                        $allCategories[]        = $row;
                    }

                }
            }
        }
        return $allCategories;
    }

    public static function getAllCategory($parentId = 0) {
        $categories                             =   static ::getChildren($parentId);
        $allCategories                          =   [];
        $temp                                   =   [];
        if (!empty($categories)) {
            foreach ($categories as $key => $category) {

                $categoryChild                  =   static ::getAllCategory($category->id);

                $category->child                =   $categoryChild ? $categoryChild:[];

                $allCategories[]                =   $category;

            }
        }
        return $allCategories;
    }

    public static function getIconAttribute($value){
        return storage_url($value);
    }
    public static function getCoverAttribute($value){
        return storage_url($value);
    }
}