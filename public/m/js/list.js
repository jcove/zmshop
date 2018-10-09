Vue.config.debug = true;
Vue.config.devtools = true;
new Vue({
	el: '#list',
	data: {
		language:localStorage.choose,
		cartNum:null,
		searchButton: ['搜索','search','名·他サ'],
		searchPlaceholder:  ['请输入内容','Please enter content','内容を入力してください'],
		activeIndex: null,
		isLoading: false,
		list: [],
		menu: [{
			title: ['首页','Index','しょおもて'],
			iconfont: 'icon-index',
			link: 'main.html'
		}, {
			title: ['分类','Category','分類する'],
			iconfont: 'icon-list',
			link: 'list.html'
		}, {
			title: ['购物车','Cart','ショッピングカート'],
			iconfont: 'icon-cart',
			link: 'shopingcart.html'
		}, {
			title: ['我的','My','私の物'],
			iconfont: 'icon-person',
			link: 'my-center.html'
		}]
	},
	mounted: function() {
		var that = this;
		document.getElementById('startLoading').style.display='none';
		mui.init({
			swipeBack: false
		});
		this.Axios = axios.create({
			baseURL: 'http://demo2.ruanwe.com/',
			timeout: 5000,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		});
		that.Axios.get('api/goodsCategory/tree', {
			params: {
				is_show: '1',
				all: '1'
			}
		}).then(function(r) {
			console.log(r);
			that.list = [];
			r.data.forEach(function(i,index) {
				var classify = [];
				i.child.forEach(function(j) {
					var stores = [];
					j.child.forEach(function(k) {
						stores.push({
							link: 'details.html?id=' + k.id,
							img: 'url(' + k.cover + ')',
							title: k.name
						});
					});
					classify.push({
						id: j.id,
						title: j.name,
						stores: stores
					});
				});
				(i.id.toString()===location.search.split('id=')[1])&&(that.activeIndex=index);
				that.list.push({
					id: i.id,
					title: i.name,
					classify: classify
				});
			});
			!that.activeIndex&&(that.activeIndex=0);
		}).catch(function(error) {
			console.log(error);
		});
		if(sessionStorage.user){			
			that.getCartNum();
		}
	},
	methods: {
		getCartNum: function() {
			var that = this;
			this.Axios.get('api/cart', {
				params: {
					api_token: sessionStorage.user
				}
			}).then(function(r) {
				console.log(r);
				that.cartNum = r.data.total;
			}).catch(function(error) {
				console.log(error);
			});
		},
		toSearch: function() {
			mui.openWindow({
				url: 'search.html?name=',
				id: 'search'
			});
		},
		changeTab: function(index) {
			var that = this;
			that.isLoading = true;
			setTimeout(function() {
				that.isLoading = false;
			}, 500);
		}
	}
});