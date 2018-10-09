<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseHistory extends Model
{
    public function files()
    {
        return $this->hasMany('App\Models\CaseHistoryFiles','case_id','id');
    }
}