<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/9/25
 * Time: 9:41
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FreightRegion extends Model
{
    protected $fillable = ['country_id', 'region_id','template_id','first_unit','first_money','continue_unit','continue_money'];
}