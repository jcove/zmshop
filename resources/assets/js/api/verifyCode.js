import api from '../api';
const verifyCodeApi = {
    verify:Function
};
verifyCodeApi.verify=function (mobile,code) {
    api.setPath('sms/verify');
    return api.post({mobile:mobile,code:code})
};

verifyCodeApi.send=function (mobile) {
    api.setPath('sms/send');
    return api.post({mobile:mobile})
};

export default verifyCodeApi;