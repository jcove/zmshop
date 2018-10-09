import api from '../api';
const captchaApi = {
    verify:Function
};
captchaApi.verify=function (captcha) {

    api.setPath('captcha-verify');
    return api.post({captcha:captcha})
};
export default captchaApi;