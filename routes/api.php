<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('address/region','UserAddressController@region');
Route::group(['middleware'=>'auth:api'],function ($route){
    Route::get('address/default','UserAddressController@getDefault');
    Route::apiResource('address', 'UserAddressController');

    Route::post('cart', 'CartController@add');
    $route->delete('cart/checked','CartController@deleteChecked');
    $route->delete('cart/{id}','CartController@destroy');
    Route::get('cart', 'CartController@index');
    Route::post('cart/check/{id}', 'CartController@check');
    Route::post('order','OrderController@createOrder');
    Route::post('order/confirm/{id}','OrderController@confirm');
    Route::get('order','OrderController@index');
    Route::post('comment','CommentController@store');
    Route::get('user/my','UserController@my');
    $route->post('password/modify', 'Auth\PasswordController@modify');
    $route->resource('user',UserController::class);
    $route->get('order/status','OrderController@status');
    $route->post('collection/cartTo','CollectionController@cartToCollection');
    $route->get('collection/check/{goodsId}','CollectionController@check');
    Route::apiResource('collection', 'CollectionController');
    Route::get('/comment/user','CommentController@user')->name('comment.user');
    Route::apiResource('suggestion', 'SuggestionController');
    Route::get('order/pay/{id}','OrderController@getPay');
    Route::get('order/{id}','OrderController@show');
    Route::get('caseHistory/check', 'CaseHistoryController@check');
    Route::apiResource('caseHistory', 'CaseHistoryController');
    $route->post('order/confirm/{id}','OrderController@confirm');
    $route->post('order/cancel/{id}','OrderController@cancel');
    $route->apiResource('returnGoods', 'ReturnGoodsController');
    Route::get('shipping/fee', 'FreightTemplateController@fee');

});
Route::get('comment','CommentController@index');
Route::post('user/register', 'Auth\RegisterController@register');
Route::post('user/login', 'Auth\LoginController@login')->name('login');
Route::get('goodsCategory/tree', 'GoodsCategoryController@tree');
Route::get('goodsCategory/brand/{id}','GoodsCategoryController@brand');
Route::get('goodsCategory/brothers/{id}', 'GoodsCategoryController@brothers');
Route::apiResource('goodsCategory', GoodsCategoryController::Class);
Route::resource('goodsModel', 'GoodsModelController');
Route::apiResource('goodsModelAttribute', 'GoodsModelAttributeController');
Route::apiResource('brand', 'BrandController');

Route::post('file','FileController@upload');
Route::apiResource('goodsModelSpecification', 'GoodsModelSpecificationController');
Route::post('goods/createSpecificationParams','GoodsController@createSpecificationParams');

Route::get('goods/{id}', 'GoodsController@show');
Route::get('goods', 'GoodsController@index');
Route::get('goodsCategory/children/{id}', 'GoodsCategoryController@children');
Route::get('goodsCategory/tree', 'GoodsCategoryController@tree');

Route::get('goods/relation/{id}', 'GoodsController@relation');
Route::apiResource('nav', 'NavController');
Route::get('comment/rank/{goods_id}','CommentController@rank');
Route::apiResource('region', 'RegionController');
Route::get('region/children/{id}','RegionController@children');
Route::post('password/reset', 'Auth\PasswordController@reset');
Route::get('sms/send','SmsController@send');
Route::post('sms/verify','SmsController@verify');
Route::get('express/query','ExpressController@get');

Route::apiResource('payment', 'PaymentController');
Route::get('goods/specifications/{id}','GoodsController@specifications');

Route::apiResource('express', 'ExpressController');

Route::resource('article/category', 'ArticleCategoryController');
Route::resource('article', 'ArticleController');

Route::apiResource('country', 'CountryController');
Route::get('freight/fee', 'FreightTemplateController@getShippingFee');
Route::apiResource('freightTemplate', 'FreightTemplateController');


