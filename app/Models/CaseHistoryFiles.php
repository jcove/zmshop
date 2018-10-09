<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/22
 * Time: 11:47
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CaseHistoryFiles extends Model
{
    public function getPathAttribute($path){
        return storage_url($path);
    }

    public function file(){
        return $this->hasOne('App\Models\File','id','file_id');
    }
}