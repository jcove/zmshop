import axios from 'axios'
import {Message} from 'element-ui'
import config from './config'
import { message } from './methods'
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
        if (error.response.status === 401) {
            message({
                message: i18n.t('common.please_login'),
                type: 'error',
                redirect: config.baseApi + '/user/login?redirect='+encodeURI(location.href)
            })


            return Promise.reject(error);
        }
        if (error.response.status === 422) {
            const errors = error.response.data.errors;
            message({
                message: errors,
                type: 'error'
            })
            return ;
        }
        if (error.response.status !== 200 && error.response.status !== 404) {

            message({
                message: error.response.data.message,
                type: 'error'
            })
            return Promise.reject(error)
        }
        return Promise.resolve(error)

    });
service.setBaseUrl = function (baseUrl) {
    service.defaults.baseURL = baseUrl
}
export default service
