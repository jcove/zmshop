import request from './request'
const api = {
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
    domain: process.env.MIX_DOMAIN
}
if(localStorage.getItem('lang')){
  api.baseApi = 'http://'+ localStorage.getItem('lang') +'.'+ api.domain
}
console.log(api.baseApi);
api.list = function(query) {
  return request.get('/' + api.path, {
    params: query
  })
}
api.info = function(id) {
  var path            = api.path;
  if(id){
      path += '/' + id
  }
  return request.get('/' + path)
}
api.get = function(id,query) {
    var path            = api.path;
    if(id!==undefined && id!==null){
        path += '/' + id
    }
    console.log(path)
    return request.get('/' + path,{
      params:query
    })
}
api.search = function(query) {
  return request.get('/' + api.path + '/search', {
    params: query
  })
}
api.save = function(query) {
  var path = api.path
  if (query.id && query.id !== 0 && query.id !== '' && query.id !== null) {
      query._method='put'
    path = api.path + '/' + query.id
  }
  return request.post('/' + path, query)
}
api.del = function(id) {

  var query={'_method':'delete'};
    var path            = api.path;
    if(id){
        path += '/' + id
    }
  return request.post('/' + path,query)
}
api.post = function(query) {
  return request.post('/' + api.path, query)
}
api.setPath = function(path) {
  api.path = path
}
api.getFileUrl = function(path) {
  if (path) {
    if (path.indexOf('http') < 0) {
      return api.cdnHost + path
    }
  }
  return path
}
export default api
