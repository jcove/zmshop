<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public static function children($id){
        if(is_string($id)){
            $region = static ::where('name',$id)->first();
            if($region){
                $id                             =   $region->id;
            }
        }
        return static :: where('parent_id',$id)->get();
    }

    public function getParentId($id){
        $array                                  =   [];
        $parentId                               =   static ::where('id',$id)->value('parent_id');
        if($parentId!=0){
            $array[]                            =   $parentId;
            $pids                               =   $this->getParentId($parentId);
            if(!empty($pids)){
                $array                          =   array_merge($array,$parentId);
            }
        }
        return $array;

    }

    public static function getByName($name){
        return static :: where('name',$name)->first();
    }
}