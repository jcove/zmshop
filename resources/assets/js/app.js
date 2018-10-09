
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
import ElementUI from 'element-ui'
import i18n from './lang/index'



Vue.component('recommend_category', require('./components/recommendCategory.vue'));
Vue.component('category-box',require('./components/categoryBox'));
Vue.component('register-form',require('./components/registerForm'));
Vue.component('goods-price',require('./components/goodsPrice'));
Vue.component('goods-info',require('./components/goodsInfo'));
Vue.component('goods-comments',require('./components/goodsComments'));
Vue.component('login-form',require('./components/loginForm'));
Vue.component('specification',require('./components/specification'));
Vue.component('cart',require('./components/cart'));
Vue.component('user-address',require('./components/address'));
Vue.component('submit',require('./components/submit'));
Vue.component('filter-params',require('./components/filterParams'));
Vue.component('user-base',require('./components/userBase'));
Vue.component('order-list',require('./components/orderList'));
Vue.component('comment-form',require('./components/commentForm'));
Vue.component('comment',require('./components/comment'));
Vue.component('suggestion-form',require('./components/suggestionForm'));
Vue.component('safe-info',require('./components/safeInfo'));
Vue.component('forget-form',require('./components/forgetForm'));
Vue.component('brand-category',require('./components/brandCategory'));
Vue.component('goods-list',require('./components/goodsList'));
Vue.component('slider',require('./components/slider'));
Vue.component('banner3',require('./components/banner3'));
Vue.component('go-pay-message',require('./components/goPayMessage'));
Vue.component('relations',require('./components/relations'));
Vue.component('relation-category',require('./components/relationCategory'));
Vue.component('relation-brand',require('./components/relationBrand'));
Vue.component('welcome',require('./components/welcome'));
Vue.component('express',require('./components/express'));
Vue.component('return-goods',require('./components/returnGoods'));
Vue.component('nav-category-tree',require('./components/pc/navCategoryTree'));
Vue.component('nav-bar',require('./components/pc/navBar'));
Vue.component('recommend-category-side',require('./components/pc/recommendCategorySide'));
Vue.component('banner',require('./components/pc/banner'));
Vue.component('floor-nav',require('./components/pc/floorNav'));
Vue.component('visited-goods-list',require('./components/pc/visitedGoodsList'));
Vue.component('goods-list-left-adv',require('./components/pc/goodsListLeftAdv'));
Vue.component('category-slider',require('./components/pc/categorySlider'));
Vue.use(ElementUI);




const app = new Vue({
    el: '#app',
    i18n
});

require('./functions');



