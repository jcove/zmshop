const config = {
    path: '',
    list: Function,
    search: Function,
    save: Function,
    del: Function,
    post: Function,
    setPath: Function,
    baseApi: process.env.MIX_BASE_API,
    fileApi: process.env.MIX_FILE_API,
    adminApi: process.env.MIX_ADMIN_API,
    cdnHost: process.env.MIX_CDN_HOST,
    domain: process.env.MIX_DOMAIN,
    debug:process.env.MIX_DEBUG==='true'
}
window.conifg= config;
if(localStorage.getItem('lang')){
    if(!config.debug){
        config.baseApi = 'http://'+ localStorage.getItem('lang') +'.'+ config.domain;
    }
}
window.siteDomain = config.baseApi;
export default config