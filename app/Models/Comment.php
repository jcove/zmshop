<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/18
 * Time: 15:46
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }
}