import api from '../api';
const goodsCategoryApi = {
    list:Function,
    children:Function,
    path:'goodsCategory'
};
goodsCategoryApi.list=function (query) {

    api.setPath('goodsCategory');
    return api.list(query)
};
goodsCategoryApi.get=function(id){
    api.setPath(goodsCategoryApi.path);
    return api.get(id)
};
goodsCategoryApi.children=function(id){
    api.setPath(goodsCategoryApi.path+'/'+'children');
    return api.get(id)
};
goodsCategoryApi.brand=function(id){
    api.setPath(goodsCategoryApi.path+'/'+'brand');
    return api.get(id)
};
goodsCategoryApi.relations=function(id){
    api.setPath(goodsCategoryApi.path+'/brothers');
    return api.get(id);
}
export default goodsCategoryApi;