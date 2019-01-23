import api from '../api';
const cartApi = {
    addCart:Function,
    list:Function,
    check:Function,
    delete:Function,
    deleteChecked:Function,
    checkAll:Function
};
cartApi.list=function(query){
    api.setPath('cart');
    return api.list(query)
}
cartApi.total=function(){
    api.setPath('cart/total');
    return api.list(null)
}
cartApi.addCart=function (query) {
    api.setPath('cart');
    return api.post(query)
};
cartApi.save=function (query) {
    api.setPath('cart');
    return api.save(query)
};
cartApi.check=function (id) {
    api.setPath('cart/check/'+id);
    return api.post(null)
};
cartApi.delete=function (id) {
    api.setPath('cart');
    return api.del(id);
};
cartApi.deleteChecked=function () {
    api.setPath('cart/checked');
    return api.del();
};
cartApi.checkAll=function (query) {
    api.setPath('cart/checkAll');
    return api.post(query);
};

export default cartApi;