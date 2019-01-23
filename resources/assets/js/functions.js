import collectionApi from "./api/collection";
import i18n from './lang/index'
import {Message} from 'element-ui'
window.deleteCollection = function(id) {
    collectionApi.delete(id).then(response =>{
        console.log(response);
        if(response){
            Message({
                message: i18n.t('common.success'),
                type: 'success',
                duration: 5 * 1000
            });
            location.reload();
        }
    });
};
window.clearLanguage=function(){
    localStorage.removeItem('lang');
    location.href= window.siteDomain;
};