<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/30
 * Time: 16:02
 */

namespace App\Http\Controllers\Admin;


use Illuminate\Support\Facades\Auth;
use Jcove\Admin\Facades\Admin;

class ArticleController extends \Jcove\Article\Controllers\ArticleController
{
    protected function prepareSave()
    {
        $this->model->author_id             =   Admin::id(config('admin.api_guard'));
    }
}