import api from '../api';
const brandApi = {
    list:Function
};
brandApi.list=function (query) {

    api.setPath('brand');
    return api.list(query)
};
export default brandApi;