import api from '../api';
const regionApi = {
    children:Function
};
regionApi.children=function (id) {

    api.setPath('region/children');
    return api.get(id)
};
export default regionApi;