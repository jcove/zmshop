new Vue({
	el: '#addressList',
	data: {
		language: localStorage.choose,
		userId: sessionStorage.user,
		pageTitle: ['我的收货地址','My shipping address','私の荷物を受け取ります。'],
		addressList: [],
		addAdress:['添加地址','Add Region','住所を追加する'],
		defaultSet:['默认','default','もくだくする'],
		edit: ['编辑','edit','書替え'],
//		set: ['设置','set','仕懸ける'],
//		reset: ['重置','reset','リセット']
	},
	methods: {
		addAddress: function() {
			sessionStorage.currenteditaddress = '';
			mui.openWindow({
				url: 'address-edit.html'
			});
		},
		editAddress: function(item) {
			mui.openWindow({
				url: 'address-edit.html'
			});
			sessionStorage.currenteditaddress = JSON.stringify(item);
		},
		setDefaultAddress: function(item) {
			var that = this;
			var postData = {
				api_token: that.userId,
				"is_default": '1',
				"_method": "put"
			};
			that.Axios.post('api/address/' + item.id, postData).then(function(r) {
				console.log(r);
				mui.back();
			}).catch(function(error) {
				console.log(error);
			});
		}
	},
	mounted: function() {
		var that = this;
		document.getElementById('startLoading').style.display='none';
		mui.init({
			swipeBack: false
		});
		//初始化单页view
		var viewApi = mui('#app').view({
			defaultPage: '#setting'
		});
		that.Axios = axios.create({
			baseURL: 'http://demo2.ruanwe.com/',
			timeout: 5000,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		});
		//获取默认地址
		that.Axios.get('api/address', {
			params: {
				api_token: that.userId
			}
		}).then(function(r) {
			console.log(r);
			that.addressList = r.data.data;
			console.log(that.addressList)
		}).catch(function(error) {
			console.log(error);
			if(error.response.status === 404) {
				that.addressList = [];
			}
		});
	}
});