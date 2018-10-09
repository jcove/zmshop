import axios from 'axios'
import {Message} from 'element-ui'
import config from './config'
import i18n from './lang/index'

// create an axios instance
const service = axios.create({
    baseURL: config.baseApi, // api的base_url
    timeout: 30000 // request timeout
});

// request interceptor
service.interceptors.request.use(config => {
    // Do something before request is sent
    if (config.method === 'post') {
        config.headers['Content-Type'] = 'application/json; charset=utf-8';
    }
    config.headers['Accept'] = 'application/json';
    return config
}, error => {
    // Do something with request error
    Promise.reject(error)
})

// respone interceptor
service.interceptors.response.use(
    response => {
        return response.data
    },
    error => {
        console.log(error);
        if (error.response.status === 401) {
            Message({
                message:i18n.t('common.please_login'),
                type: 'error',
                duration: 1.5 * 1000,
                showClose:true,
                onClose:function () {
                    location.href = config.baseApi + '/user/login?redirect='+encodeURI(location.href);
                }
            });


            return Promise.reject(error);
        }
        if (error.response.status === 422) {
            const keys = Object.keys(error.response.data.errors)
            const errors = error.response.data.errors;
            var err =   '';
            keys.forEach(function(key) {
               err+='<p>'+ errors[key][0] + '</p>'
            });
            Message({
                message: err,
                type: 'error',
                dangerouslyUseHTMLString: true,
                duration: 3 * 1000,
                showClose:true
            });
            return ;
        }
        if (error.response.status !== 200 && error.response.status !== 404) {
            Message({
                message: error.response.data.message,
                type: 'error',
                duration: 3 * 1000
            })
            return Promise.reject(error)
        }
        return Promise.resolve(error)

    });
service.setBaseUrl = function (baseUrl) {
    service.defaults.baseURL = baseUrl
}
export default service
