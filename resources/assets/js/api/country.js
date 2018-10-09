import api from '../api';
const countryApi = {
    list:Function
};
countryApi.list=function (query) {
    api.setPath('country');
    return api.list(query)
};
export default countryApi;