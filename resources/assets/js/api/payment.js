import api from '../api';
const paymentApi = {
    list:Function
};
paymentApi.list=function (query) {

    api.setPath('payment');
    return api.list(query)
};
export default paymentApi;