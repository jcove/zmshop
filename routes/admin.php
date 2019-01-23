<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/20
 * Time: 17:38
 */

use Illuminate\Routing\Router;
\Jcove\Admin\Facades\Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
], function (Router $router) {

    Route::post('goods', 'GoodsController@store')->middleware(['filter_image','permission:Goods edit']);
    $router->put('goods/{id}','GoodsController@update')->middleware(['filter_image','permission:Goods edit']);
    $router->get('goods','GoodsController@index')->middleware(['filter_image','permission:Goods show']);
    $router->get('goods/{id}','GoodsController@show')->middleware(['filter_image','permission:Goods show'])->name('admin.goods.show');
    $router->delete('goods/{id}','GoodsController@destroy')->middleware(['permission:Goods delete'])->name('admin.goods.delete');
    Route::get('goodsModel', 'GoodsModelController@index');
    Route::get('goodsModel/{id}', 'GoodsModelController@show');
    Route::resource('goodsModelAttribute', 'GoodsModelAttributeController')->middleware(['permission:Goods Model Manage']);
    Route::resource('goodsModelSpecification', 'GoodsModelSpecificationController')->middleware(['permission:Goods Model Manage']);
    Route::post('goods/createSpecificationParams', 'GoodsController@createSpecificationParams');
    Route::get('goods', 'GoodsController@index');

    $router->get('goodsCategory/children/{id}', 'GoodsCategoryController@children');
    $router->get('goodsCategory/tree', 'GoodsCategoryController@tree');
    $router->get('goodsCategory', 'GoodsCategoryController@index')->name('admin.goodsCategory.show');
    $router->post('goodsCategory', 'GoodsCategoryController@store')->middleware(['filter_image','permission:Goods Category Manage']);
    $router->put('goodsCategory/{id}', 'GoodsCategoryController@update')->middleware(['filter_image','permission:Goods Category Manage']);
    $router->delete('goodsCategory/{id}', 'GoodsCategoryController@store')->middleware(['permission:Goods Category Manage']);
    Route::post('goodsModel', 'GoodsModelController@store')->middleware(['permission:Goods Model Manage']);
    Route::put('goodsModel/{id}', 'GoodsModelController@update')->middleware(['permission:Goods Model Manage']);
    Route::delete('goodsModel/{id}', 'GoodsModelController@destroy')->middleware(['permission:Goods Model Manage']);
    Route::get('brand', 'BrandController@index');
    Route::get('brand/{id}', 'BrandController@show');
    Route::post('brand', 'BrandController@store')->middleware(['filter_image','permission:Brand Manage']);
    Route::put('brand/{id}', 'BrandController@update')->middleware(['filter_image','permission:Brand Manage']);
    Route::delete('brand/{id}', 'BrandController@destroy')->middleware(['filter_image','permission:Brand Manage']);
    Route::apiResource('nav', 'NavController')->middleware(['permission:Nav Manage']);
    Route::get('adPosition/search','Admin\AdPositionController@search' );
    Route::resource('adPosition','Admin\AdPositionController' )->middleware(['filter_image','permission:Ad Manage']);
    Route::resource('ad','Admin\AdController' )->middleware(['filter_image','permission:Ad Manage']);
    Route::resource('comment','Admin\CommentController' )->middleware(['filter_image','permission:Comment Manage']);
    $router->get('suggestion', 'SuggestionController@index')->name('admin.suggestion.show')->middleware(['permission:Suggestion show']);
    $router->delete('suggestion/{id}', 'SuggestionController@destroy')->name('admin.suggestion.destroy')->middleware(['permission:Suggestion delete']);

    $router->get('region','RegionController@index');
    $router->get('region/{id}','RegionController@show');
    $router->post('region','RegionController@store')->middleware(['permission:Region Manage']);
    $router->put('region/{id}','RegionController@update')->middleware(['permission:Region Manage']);
    $router->delete('region/{id}','RegionController@destroy')->middleware(['permission:Region Manage']);
    Route::get('region/children/{id}','RegionController@children');


    Route::get('express/query','ExpressController@get');
    Route::get('express/{id}','ExpressController@show');
    Route::get('express','ExpressController@index');
    Route::post('express', 'ExpressController@store')->middleware(['permission:Express Manage']);
    Route::put('express/{id}', 'ExpressController@update')->middleware(['permission:Express Manage']);
    Route::delete('express/{id}', 'ExpressController@destroy')->middleware(['permission:Express Manage']);
    $router->resource('config', 'ConfigController')->middleware(['filter_image','permission:Config Manage']);
    Route::get('goods/relation/{id}', 'GoodsController@relation');
    $router->get('case','CaseHistoryController@index')->name('admin.case.index')->middleware(['permission:Case Show']);
    $router->get('user/goods/{id}','CaseHistoryController@goods')->name('admin.case.goods')->middleware(['permission:User Goods Show']);

    Route::get('country', 'CountryController@index');
    Route::get('country/{id}', 'CountryController@show');
    Route::post('country', 'CountryController@store')->middleware(['permission:Country Manage']);
    Route::put('country/{id}', 'CountryController@update')->middleware(['permission:Country Manage']);
    Route::delete('country/{id}', 'CountryController@destroy')->middleware(['permission:Country Manage']);
    Route::get('freight/{id}', 'FreightTemplateController@show');
    Route::get('freight', 'FreightTemplateController@index');
    Route::post('freight', 'FreightTemplateController@store')->middleware(['permission:Freight Manage']);
    Route::put('freight/{id}', 'FreightTemplateController@update')->middleware(['permission:Freight Manage']);
    Route::delete('freight/{id}', 'FreightTemplateController@destroy')->middleware(['permission:Freight Manage']);

});
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => 'App\Http\Controllers\Admin',
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('order/export/excel','OrderController@exportExcel')->name('admin.order.excel')->middleware(['permission:Order Export']);
    $router->get('order/export','OrderController@export')->name('admin.order.export')->middleware(['permission:Order Export']);
    $router->get('user/export','UserController@exportExcel')->name('admin.user.export')->middleware(['permission:User Export']);;
    $router->get('order','OrderController@index')->name('admin.order.index')->middleware(['permission:Order Show']);
    $router->post('order/pay/{id}','OrderController@pay')->name('admin.order.pay')->middleware(['permission:Order Pay']);
    $router->post('order/cancel/{id}','OrderController@cancel')->name('admin.order.cancel')->middleware(['permission:Order Cancel']);
    $router->get('order/{id}','OrderController@show')->name('admin.order.show')->middleware(['permission:Order Show']);
    $router->put('order/{id}','OrderController@update')->name('admin.order.update')->middleware(['permission:Order Update']);
    $router->resource('article','ArticleController')->names([
        'show'  =>  'admin.article.show'
    ])->middleware(['filter_image','permission:Article Manage']);
    $router->get('articleCategory/tree','ArticleCategoryController@tree')->middleware(['permission:Article Manage']);
    $router->resource('articleCategory','ArticleCategoryController')->middleware(['permission:Article Manage']);
    $router->post('promotion/register','PromotionController@register')->middleware(['permission:Promotion Manage']);
    $router->post('promotion/product','PromotionController@product')->middleware(['permission:Promotion Manage']);
    $router->get('promotion/query','PromotionController@query')->middleware(['permission:Promotion Manage']);
    $router->post('promotion/products','PromotionController@products')->middleware(['permission:Promotion Manage']);
    $router->get('promotion/search','PromotionController@search')->middleware(['permission:Promotion Manage']);
    $router->resource('promotion', 'PromotionController')->middleware(['permission:Promotion Manage']);
    $router->delete('promotion/product/{id}', 'PromotionController@deleteProduct')->middleware(['permission:Promotion Manage']);
    Route::resource('user', 'UserController')->middleware(['filter_image','permission:User Manage']);
    $router->get('address','UserAddressController@index')->name('admin.address.index')->middleware(['permission:User Address Show']);
    $router->post('address','UserAddressController@store')->name('admin.address.create')->middleware(['permission:User Address Edit']);
    $router->put('address/{id}','UserAddressController@update')->name('admin.address.update')->middleware(['permission:User Address Edit']);
    $router->delete('address/{id}','UserAddressController@destroy')->name('admin.address.delete')->middleware(['permission:User Address Delete']);
    $router->get('delivery/print/{id}', 'DeliveryController@print')->middleware(['permission:Delivery Show'])->name('admin.delivery.print');
    $router->get('delivery/export', 'DeliveryController@export')->middleware(['permission:Delivery Export'])->name('admin.delivery.export');
    Route::post('delivery/delivery/{id}', 'DeliveryController@delivery')->middleware(['permission:Order Delivery']);
    $router->get('delivery', 'DeliveryController@index')->middleware(['permission:Delivery Show']);
    $router->get('delivery/{id}', 'DeliveryController@show')->middleware(['permission:Delivery Show']);
    $router->get('returnGoods','ReturnGoodsController@index')->middleware(['permission:Return Goods Show']);
    $router->put('returnGoods/{id}','ReturnGoodsController@update')->middleware(['permission:Return Goods Manage']);
    Route::post('goods', 'GoodsController@store')->middleware(['filter_image','permission:Goods Edit']);
    $router->get('goods/export','GoodsController@exportExcel')->middleware(['permission:Goods Export']);
    $router->put('goods/{id}','GoodsController@update')->middleware(['filter_image','permission:Goods Edit']);

    $router->get('goods/{id}','GoodsController@show')->middleware(['filter_image','permission:Goods Show'])->name('admin.goods.show');
    $router->delete('goods/{id}','GoodsController@destroy')->middleware(['permission:Goods Delete'])->name('admin.goods.delete');

    $router->get('goods','GoodsController@index')->middleware(['filter_image','permission:Goods Show']);
    Route::post('goods/createSpecificationParams', 'GoodsController@createSpecificationParams');
});

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),

], function (Router $router) {
    Route::get('file/download/{id}','FileController@download')->name('admin.file.download');
    $router->get('order/export/excel','OrderController@exportExcel')->name('admin.order.excel');

});

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => 'App\Http\Controllers\Admin',

], function (Router $router) {
    $router->get('order/export/excel','OrderController@exportExcel')->name('admin.order.excel');

});


