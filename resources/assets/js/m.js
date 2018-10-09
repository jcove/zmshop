
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueI18n from 'vue-i18n'
Vue.use(VueI18n);

const i18n = new VueI18n({ locale: localStorage.getItem('lang') || 'cn', // 语言标识
    messages: {
        'cn': require('./lang/cn'), // 中文语言包
        'en': require('./lang/en'), // 英文语言包
        'jp': require('./lang/jp')
    } });


const app = new Vue({
    el: '#app',
    i18n
});

require('./functions');



