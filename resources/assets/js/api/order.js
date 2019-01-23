import api from '../api';
const orderApi = {
    createOrder:Function,
    confirm:Function,
    list:Function,
    status:Function,
    pay:Function
};
orderApi.get= function (id) {
    api.setPath('order');
    return api.get(id)
};
orderApi.createOrder=function (query) {

    api.setPath('order');
    return api.post(query)
};
orderApi.confirm=function (id) {
    api.setPath('order/confirm/'+id);
    return api.post(null)
};
orderApi.list = function (query) {
    api.setPath('order');
    return api.list(query);
};
orderApi.status = function () {
    api.setPath('order/status');
    return api.get();
};
orderApi.pay = function (id) {
    api.setPath('order/pay');
    return api.get(id);
};
orderApi.cancel = function (id) {
    api.setPath('order/cancel/'+id);

    return api.post(null);
};
export default orderApi;