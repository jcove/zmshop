<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/24
 * Time: 9:52
 */

namespace App\Http\Controllers;


use App\Models\GoodsCategory;
use Jcove\Ad\Models\Ad;
use Jcove\Restful\Restful;

class IndexController extends Controller
{
    use Restful;
    public function index(){
        $categories                             =   GoodsCategory::where('parent_id',0)->orderBy('order','desc')->get();
        $recommendCategories                    =   GoodsCategory::where('is_recommend',1)->orderBy('order','desc')->get();
        $data['categories']                     =   $categories;
        $banners                                =   Ad::list('pc_index_banner');
        $data['banners']                        =   $banners;
        $data['recommend_categories']           =   $recommendCategories;
        return $this->respond($data);
    }
}