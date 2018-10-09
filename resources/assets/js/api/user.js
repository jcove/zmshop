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
userApi.save=function(qurey){
    api.setPath('user');
    return api.save(qurey)
};

userApi.modifyPassword=function(query){
    api.setPath('password/modify');
    return api.post(query)
};
userApi.resetPassword=function(query){
    api.setPath('password/reset');
    return api.post(query)
};


export default userApi;