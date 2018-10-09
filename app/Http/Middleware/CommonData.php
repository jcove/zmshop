<?php

namespace App\Http\Middleware;

use App\Models\GoodsCategory;
use App\Models\Nav;
use Closure;
use Jcove\Article\Models\Article;
use Jcove\Article\Models\ArticleCategory;
use Jcove\Restful\Utils;

class CommonData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->acceptsHtml()){
//            if(Utils::isMobileBrowser()){
//                return response()->redirectTo('/m');
//            }
            $this->footerArticle();
            $this->nav();
            $this->goodsCategoryTree();
        }
        return $next($request);
    }

    protected function footerArticle(){
        $categories                         =   ArticleCategory::where('id','<',5)->get();//默认为前5个分类
        foreach ($categories as $key => $value){
            $categories[$key]->articles     =   Article::where('category_id',$value->id)->take(6)->get();
        }
        view()->share('footer_categories', $categories);
    }

    protected function nav(){
        $navs                               =   Nav::orderBy('order','desc')->get();
        view()->share('navs',$navs);
    }

    protected function goodsCategoryTree(){
        $tree                               =   GoodsCategory::getAllCategory(0);
        view()->share('goods_category_tree',$tree);
    }
}
