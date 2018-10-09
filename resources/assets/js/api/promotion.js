import api from '../api';
const promotionApi = {
    products:Function
};
promotionApi.products=function (query) {
    api.setPath('shop/promotion/products');
    return api.post(query)
};
promotionApi.product=function (query) {
    api.setPath('shop/promotion/product');
    return api.post(query)
};
export default promotionApi;