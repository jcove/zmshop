import api from '../api';
const goodsApi = {
    list:Function
};
goodsApi.list=function (query) {
    api.setPath('goods');
    return api.list(query)
};
goodsApi.relation=function(id){
    api.setPath('goods/relation');
    return api.get(id);
}
goodsApi.checkRx=function (query) {
    api.setPath('goods/rx/check');
    return api.get(null,query)
};
export default goodsApi;