'use strict';

var _elementUi = require('element-ui');

var _elementUi2 = _interopRequireDefault(_elementUi);

var _vueI18n = require('vue-i18n');

var _vueI18n2 = _interopRequireDefault(_vueI18n);

var _recommendCategory = require('./components/recommendCategory.vue');

var _recommendCategory2 = _interopRequireDefault(_recommendCategory);

var _categoryBox = require('./components/categoryBox');

var _categoryBox2 = _interopRequireDefault(_categoryBox);

var _registerForm = require('./components/registerForm');

var _registerForm2 = _interopRequireDefault(_registerForm);

var _goodsPrice = require('./components/goodsPrice');

var _goodsPrice2 = _interopRequireDefault(_goodsPrice);

var _goodsInfo = require('./components/goodsInfo');

var _goodsInfo2 = _interopRequireDefault(_goodsInfo);

var _goodsComments = require('./components/goodsComments');

var _goodsComments2 = _interopRequireDefault(_goodsComments);

var _loginForm = require('./components/loginForm');

var _loginForm2 = _interopRequireDefault(_loginForm);

var _specification = require('./components/specification');

var _specification2 = _interopRequireDefault(_specification);

var _cart = require('./components/cart');

var _cart2 = _interopRequireDefault(_cart);

var _address = require('./components/address');

var _address2 = _interopRequireDefault(_address);

var _submit = require('./components/submit');

var _submit2 = _interopRequireDefault(_submit);

var _filterParams = require('./components/filterParams');

var _filterParams2 = _interopRequireDefault(_filterParams);

var _userBase = require('./components/userBase');

var _userBase2 = _interopRequireDefault(_userBase);

var _orderList = require('./components/orderList');

var _orderList2 = _interopRequireDefault(_orderList);

var _commentForm = require('./components/commentForm');

var _commentForm2 = _interopRequireDefault(_commentForm);

var _comment = require('./components/comment');

var _comment2 = _interopRequireDefault(_comment);

var _suggestionForm = require('./components/suggestionForm');

var _suggestionForm2 = _interopRequireDefault(_suggestionForm);

var _safeInfo = require('./components/safeInfo');

var _safeInfo2 = _interopRequireDefault(_safeInfo);

var _forgetForm = require('./components/forgetForm');

var _forgetForm2 = _interopRequireDefault(_forgetForm);

var _brandCategory = require('./components/brandCategory');

var _brandCategory2 = _interopRequireDefault(_brandCategory);

var _goodsList = require('./components/goodsList');

var _goodsList2 = _interopRequireDefault(_goodsList);

var _banner = require('./components/banner');

var _banner2 = _interopRequireDefault(_banner);

var _banner3 = require('./components/banner3');

var _banner4 = _interopRequireDefault(_banner3);

var _goPayMessage = require('./components/goPayMessage');

var _goPayMessage2 = _interopRequireDefault(_goPayMessage);

var _relations = require('./components/relations');

var _relations2 = _interopRequireDefault(_relations);

var _relationCategory = require('./components/relationCategory');

var _relationCategory2 = _interopRequireDefault(_relationCategory);

var _relationBrand = require('./components/relationBrand');

var _relationBrand2 = _interopRequireDefault(_relationBrand);

var _welcome = require('./components/welcome');

var _welcome2 = _interopRequireDefault(_welcome);

var _express = require('./components/express');

var _express2 = _interopRequireDefault(_express);

var _returnGoods = require('./components/returnGoods');

var _returnGoods2 = _interopRequireDefault(_returnGoods);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');


Vue.use(_recommendCategory2.default);
Vue.use(_categoryBox2.default);
Vue.use(_registerForm2.default);
Vue.use(_goodsPrice2.default);
Vue.use(_goodsInfo2.default);
Vue.use(_goodsComments2.default);
Vue.use(_loginForm2.default);
Vue.use(_specification2.default);
Vue.use(_cart2.default);
Vue.use(_address2.default);
Vue.use(_submit2.default);
Vue.use(_filterParams2.default);
Vue.use(_userBase2.default);
Vue.use(_orderList2.default);
Vue.use(_commentForm2.default);
Vue.use(_comment2.default);

Vue.use(_suggestionForm2.default);
Vue.use(_safeInfo2.default);
Vue.use(_forgetForm2.default);
Vue.use(_brandCategory2.default);
Vue.use(_goodsList2.default);
Vue.use(_banner2.default);
Vue.use(_banner4.default);
Vue.use(_goPayMessage2.default);
Vue.use(_relations2.default);
Vue.use(_relationCategory2.default);
Vue.use(_relationBrand2.default);
Vue.use(_welcome2.default);
Vue.use(_express2.default);
Vue.use(_returnGoods2.default);

//
// Vue.component('recommend_category', require('./components/recommendCategory.vue'));
// Vue.component('category-box',require('./components/categoryBox'));
// Vue.component('register-form',require('./components/registerForm'));
// Vue.component('goods-price',require('./components/goodsPrice'));
// Vue.component('goods-info',require('./components/goodsInfo'));
// Vue.component('goods-comments',require('./components/goodsComments'));
// Vue.component('login-form',require('./components/loginForm'));
// Vue.component('specification',require('./components/specification'));
// Vue.component('cart',require('./components/cart'));
// Vue.component('user-address',require('./components/address'));
// Vue.component('submit',require('./components/submit'));
// Vue.component('filter-params',require('./components/filterParams'));
// Vue.component('user-base',require('./components/userBase'));
// Vue.component('order-list',require('./components/orderList'));
// Vue.component('comment-form',require('./components/commentForm'));
// Vue.component('comment',require('./components/comment'));
// Vue.component('suggestion-form',require('./components/suggestionForm'));
// Vue.component('safe-info',require('./components/safeInfo'));
// Vue.component('forget-form',require('./components/forgetForm'));
// Vue.component('brand-category',require('./components/brandCategory'));
// Vue.component('goods-list',require('./components/goodsList'));
// Vue.component('banner',require('./components/banner'));
// Vue.component('banner3',require('./components/banner3'));
// Vue.component('go-pay-message',require('./components/goPayMessage'));
// Vue.component('relations',require('./components/relations'));
// Vue.component('relation-category',require('./components/relationCategory'));
// Vue.component('relation-brand',require('./components/relationBrand'));
// Vue.component('welcome',require('./components/welcome'));
// Vue.component('express',require('./components/express'));
// Vue.component('return-goods',require('./components/returnGoods'));
Vue.use(_elementUi2.default);
Vue.use(_vueI18n2.default);
var i18n = new _vueI18n2.default({ locale: localStorage.getItem('lang') || 'cn', // 语言标识
    messages: {
        'cn': require('./lang/cn'), // 中文语言包
        'en': require('./lang/en'), // 英文语言包
        'jp': require('./lang/jp')
    } });

var app = new Vue({
    el: '#app',
    i18n: i18n
});


require('./functions');