import api from '../api';
const adApi = {
    list:Function
};
adApi.list=function (query) {

    api.setPath('common/ad');
    return api.list(query)
};
export default adApi;