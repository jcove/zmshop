import api from '../api';
const addressApi = {
    list:Function,
    delete:Function,
    default:Function
};
addressApi.save=function (query) {

    api.setPath('address');
    return api.save(query)
};
addressApi.delete=function(id){
    api.setPath('address');
    return api.del(id)
};
addressApi.default = function () {
    api.setPath('address/default');
    return api.info();
}
addressApi.list=function(){
    api.setPath('address');
    return api.get()
}
addressApi.region=function(){
    api.setPath('address/region');
    return api.get()
}

export default addressApi;