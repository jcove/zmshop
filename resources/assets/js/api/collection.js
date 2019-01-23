import api from '../api';
const collectionApi = {
    cartTo:Function,
    delete:Function,
};
collectionApi.cartTo=function () {
    api.setPath('collection/cartTo');
    return api.post()
};
collectionApi.list=function () {
    api.setPath('collection');
    return api.list()
};
collectionApi.delete = function (id) {
    api.setPath('collection');
    return api.del(id);
};
collectionApi.check = function (id) {
    api.setPath('collection/check');
    return api.get(id);
};
collectionApi.collect=function(query){
    api.setPath('collection');
    return api.save(query)
}
export default collectionApi;