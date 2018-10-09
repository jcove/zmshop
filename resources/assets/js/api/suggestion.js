import api from '../api';
const suggestionApi = {
    save:Function
};
suggestionApi.save=function (query) {

    api.setPath('suggestion');
    return api.save(query)
};
export default suggestionApi;