import api from '../api';
const caseHistoryApi = {
    check:Function,
    save:Function
};
caseHistoryApi.save=function (query) {
    api.setPath('caseHistory');
    return api.save(query)
};
caseHistoryApi.check=function () {
    api.setPath('caseHistory/check');
    return api.get()
};
export default caseHistoryApi;