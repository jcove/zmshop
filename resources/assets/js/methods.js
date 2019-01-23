import config from "./config";
import Cookies from 'js-cookie'
import {Toast} from 'mint-ui'
import {Message} from 'element-ui'
import i18n from "./lang";
export function getCategoryRoute(id) {
    return config.baseApi + '/goods?category_id=' + id;
}

export function login(user) {
    Cookies.set('user',JSON.stringify(user))
}
export function logout() {
    Cookies.remove('user')
}
export function isLogin() {
    return Cookies.get('user') != null
}
export function mobileBrowser() {
    if(/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))){
        if(/iPad/i.test(navigator.userAgent)){
           return false
        }
        return true
    }
    return false
}

export function message(option){
    const message = option.message;
    const type = option.type ? option.type : 'success'
    const redirect = option.redirect;
    if(mobileBrowser()){
        var string          =   '';
        if(message instanceof Object){
            const keys = Object.keys(message);
            keys.forEach(function(key) {
                Toast({
                    message: message[key][0],
                    position: 'middle',
                    duration: 1.5*1000
                });
            });
        }else{
            string          =   message;
            Toast({
                message: string,
                position: 'middle',
                duration: 1.5*1000
            });
        }

    }else {
        var str          =   '';
        console.log(message)
        if(message instanceof Object){
            const keys = Object.keys(message);
            keys.forEach(function(key) {
                str+='<p>'+ message[key][0] + '</p>'
            });
        }else{
            str             =   message;
        }
        Message({
            message: str,
            type: type,
            dangerouslyUseHTMLString: true,
            duration: 2 * 1000,
            showClose:true
        });
    }
    if(redirect && redirect!==''){
        setTimeout(function () {
            location.href = redirect;
        },1.5*1000);
    }
}