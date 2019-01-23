import api from '../api';
const returnGoodsApi = {
    list:Function
};
returnGoodsApi.list=function (query) {

    api.setPath('returnGoods');
    return api.list(query)
};
returnGoodsApi.check=function (query) {
    api.setPath('returnGoods/check');
    return api.list(query)
};
returnGoodsApi.save=function(query){
    api.setPath('returnGoods');
    return api.save(query)
}
returnGoodsApi.send=function(query){
    api.setPath('returnGoods/send');
    return api.save(query)
}
export default returnGoodsApi;