import api from '../api';
const userApi = {
    register:Function,
    login:Function,
    save:Function,
    modifyPassword:Function
};
userApi.register=function (query) {
    api.setPath('user/register');
    return api.post(query)
};
userApi.login = function (qurey) {
    api.setPath('user/login');
    return api.post(qurey)
};
userApi.save=function(query){
    api.setPath('user');
    query._method = 'put'
    return api.save(query)
};

userApi.modifyPassword=function(query){
    api.setPath('password/modify');
    return api.post(query)
};
userApi.resetPassword=function(query){
    api.setPath('password/reset');
    return api.post(query)
};
userApi.my = function () {
    api.setPath('user/my')
    return api.get(null)
}

export default userApi;