import api from '../api';
const freightApi = {
    list:Function
};
freightApi.fee=function (query) {
    api.setPath('freight/fee');
    return api.get(null,query)
};
freightApi.shippingFee=function (query) {
    api.setPath('shipping/fee');
    return api.get(null,query)
};
export default freightApi;