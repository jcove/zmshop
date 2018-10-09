import api from '../api';
const collectionApi = {
    cartTo:Function,
    delete:Function,
};
collectionApi.cartTo=function () {
    api.setPath('collection/cartTo');
    return api.post()
};
collectionApi.delete = function (id) {
    api.setPath('collection');
    return api.del(id);
};
collectionApi.collect=function(query){
    api.setPath('collect');
    return api.save(query)
}
export default collectionApi;