<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/24
 * Time: 9:52
 */

namespace App\Http\Controllers;


use App\Models\GoodsCategory;
use Jcove\Ad\Models\Ad;

class IndexController extends Controller
{
    public function index(){
        $categories                             =   GoodsCategory::where('parent_id',0)->orderBy('order','desc')->get();
        $data['categories']                     =   $categories;
        $banners                                =   Ad::list('pc_index_banner');
        $data['banners']                        =   $banners;
        return view('pc.index.index',$data);
    }
}