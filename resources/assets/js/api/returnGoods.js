import api from '../api';
const returnGoodsApi = {
    list:Function
};
returnGoodsApi.list=function (query) {

    api.setPath('returnGoods');
    return api.list(query)
};
returnGoodsApi.save=function(query){
    api.setPath('returnGoods');
    return api.save(query)
}
export default returnGoodsApi;