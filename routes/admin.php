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
    $router->resource('goodsCategory', 'GoodsCategoryController')->names([
        'show'  =>  'admin.goodsCategory.show'
    ])->middleware(['filter_image','permission:Goods category Manage']);
    Route::resource('goodsModel', 'GoodsModelController')->middleware(['permission:Goods Model Manage']);
    Route::resource('brand', 'BrandController')->middleware(['filter_image','permission:Brand Manage']);
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
    Route::post('delivery/delivery/{id}', 'DeliveryController@delivery')->middleware(['permission:Order delivery']);
    $router->get('delivery', 'DeliveryController@index')->middleware(['permission:Delivery show']);
    $router->get('delivery/{id}', 'DeliveryController@show')->middleware(['permission:Delivery show']);
    //$router->resource('delivery', 'DeliveryController');
    Route::get('express/query','ExpressController@get');
    Route::apiResource('express', 'ExpressController');

    $router->resource('config', 'ConfigController')->middleware(['filter_image','permission:Config Manage']);
    Route::get('goods/relation/{id}', 'GoodsController@relation');
    $router->get('case','CaseHistoryController@index')->name('admin.case.index')->middleware(['permission:Case show']);;
    Route::get('country', 'CountryController@index');
    Route::apiResource('country', 'CountryController')->middleware(['permission:Country Manage']);
    Route::apiResource('freight', 'FreightTemplateController')->middleware(['permission:Freight Manage']);

});
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => 'App\Http\Controllers\Admin',
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('order/export','OrderController@export')->name('admin.order.export')->middleware(['permission:Order export']);;
    $router->get('order','OrderController@index')->name('admin.order.index')->middleware(['permission:Order show']);
    $router->post('order/pay/{id}','OrderController@pay')->name('admin.order.pay')->middleware(['permission:Order pay']);
    $router->post('order/cancel/{id}','OrderController@cancel')->name('admin.order.cancel')->middleware(['permission:Order cancel']);
    $router->get('order/{id}','OrderController@show')->name('admin.order.show')->middleware(['permission:Order show']);
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

});

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),

], function (Router $router) {
    Route::get('file/download/{id}','FileController@download')->name('admin.file.download');

});


