<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('address/region','UserAddressController@region');
Route::group(['middleware'=>'common_data'],function ($route){
    $route->get('/', 'IndexController@index')->middleware(['access_control']);
    $route->get('/home', 'IndexController@index')->middleware(['access_control']);
    Route::resource('goodsModelAttribute', 'GoodsModelAttributeController');
    Route::resource('brand', 'BrandController');
    Route::resource('goods', 'GoodsController');


    Route::resource('goodsModelSpecification', 'GoodsModelSpecificationController');
    Route::resource('config', 'ConfigController');
    Route::get('goodsCategory/brand/{id}','GoodsCategoryController@brand');
    Route::resource('goodsCategory', GoodsCategoryController::Class);
    Route::get('goodsCategory/children/{id}', 'GoodsCategoryController@children');
    Route::get('goodsCategory/brothers/{id}', 'GoodsCategoryController@brothers');
    Route::get('/user/register', 'Auth\RegisterController@showRegistrationForm')->name('user.showRegistrationForm');
    $route->get('/user/login', 'Auth\LoginController@showLoginForm')->name('user.showLoginForm');
    Route::get('/ad','AdController@index');
    Route::post('user/register', 'Auth\RegisterController@register')->name('register');
    Route::post('user/login', 'Auth\LoginController@login')->name('login');
    $route->get('password/forget', 'Auth\PasswordController@showForgetForm')->name('password.forgetForm');
    $route->get('order/success/{id}', 'OrderController@createSuccess')->name('order.success');
    Route::get('pay/success/{id}','PayController@paySuccess')->name('pay.success');
    Route::get('pay/cancel/{id}','PayController@cancel')->name('pay.cancel');
    $route->get('order/pay/{id}','OrderController@getPay');
    Route::resource('returnGoods', 'ReturnGoodsController');
    Route::resource('article', 'ArticleController');
});

Route::group(['middleware'=>['auth']],function ($route){
    $route->post('cart/check/{id}', 'CartController@check')->name('cart.check');
    $route->post('cart/checkAll', 'CartController@checkAll')->name('cart.checkAll');
    $route->get('address/default', 'UserAddressController@getDefault');

    $route->post('order', 'OrderController@createOrder');
    $route->post('order/confirm/{id}','OrderController@confirm');
    Route::post('comment','CommentController@store');
    $route->get('order/status','OrderController@status');
    Route::get('order/{id}','OrderController@show');
    $route->delete('cart/checked','CartController@deleteChecked');
    $route->delete('cart/{id}','CartController@destroy');
    $route->post('password/modify', 'Auth\PasswordController@modify');
    $route->put('user/{id}','UserController@update')->middleware('filter_image');
    Route::get('caseHistory/check', 'CaseHistoryController@check')->name('caseHistory.check');
    Route::resource('caseHistory', 'CaseHistoryController');
    $route->get('collection/check/{goodsId}','CollectionController@check')->name('collection.check');
    Route::get('shipping/fee', 'FreightTemplateController@fee');

});

Route::group(['middleware'=>['common_data','auth']],function ($route){
    Route::get('user/my', 'UserController@my')->name('user.my');
    Route::get('user/logout', 'Auth\LoginController@logout')->name('user.logout');
    Route::post('cart', 'CartController@add');
    Route::get('cart','CartController@index')->name('cart.index');
    Route::get('order','OrderController@index')->name('order.index');
    Route::resource('address', 'UserAddressController');
    $route->get('cart/submit', 'CartController@submit')->name('cart.submit');
    $route->get('order/comment/{id}','OrderController@toComment')->name('order.toComment');
    $route->post('collection/cartTo','CollectionController@cartToCollection');
    Route::resource('collection', 'CollectionController');
    Route::get('/comment/user','CommentController@user')->name('comment.user');
    Route::resource('suggestion', 'SuggestionController');
    $route->get('user/safe','UserController@safe')->name('user.safe');
});



Route::resource('nav', 'NavController');
Route::get('/comment/rank/{goods_id}','CommentController@rank');
Route::get('/comment','CommentController@index');

Route::post('/captcha-verify', 'CaptchaController@verify');
Route::post('/sms/verify', 'SmsController@verify');
Route::post('/sms/send', 'SmsController@send');
Route::resource('region', 'RegionController');
Route::get('region/children/{id}','RegionController@children');
Route::post('file','FileController@upload');
Route::post('password/reset', 'Auth\PasswordController@reset');
Route::get('goods/relation/{id}', 'GoodsController@relation');
Route::get('sms/send','SmsController@send');
Route::post('sms/verify','SmsController@verify');
Route::resource('caseHistory', 'CaseHistoryController');
Route::get('express/query','ExpressController@get');
Route::resource('payment', 'PaymentController');
Route::resource('delivery', 'DeliveryController');
Route::resource('express', 'ExpressController');
Route::get('customer','CustomerServiceController@index')->name('customer.index');
Route::get('file/download/{id}','FileController@download')->name('file.download');


Route::resource('country', 'CountryController');
Route::get('freight/fee', 'FreightTemplateController@getShippingFee');
Route::resource('freightTemplate', 'FreightTemplateController');
Route::get('goods/rx/check','GoodsController@checkRx');