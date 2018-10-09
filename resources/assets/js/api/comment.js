import api from '../api';
const commentApi = {
    list:Function,
    comment:Function
};
commentApi.list=function (query) {
    api.setPath('comment');
    return api.list(query)
};
commentApi.comment = function (query) {
    api.setPath('comment');
    return api.post(query)
};
commentApi.user = function () {
    api.setPath('comment/user');
    return api.get()
};

export default commentApi;