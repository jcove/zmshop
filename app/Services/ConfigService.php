<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/10/9
 * Time: 14:28
 */

namespace App\Services;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConfigService
{
    public static function load(){
        if (Schema::hasTable('config'))
        {
            $configs                     = DB::table('configs')->get();
            foreach ($configs as $config) {
                config([$config->name => $config->value]);
            }
        }
    }
}