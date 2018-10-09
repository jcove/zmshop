new Vue({
	el: '#myCenter',
	data: {
		language:localStorage.choose,
		cartNum: null,
		pageTitle: ['个人中心','My Center','個人の中心'],
		userId: sessionStorage.user,
		userName: sessionStorage.nike,
		head: {
			img: 'img/trade.png',
			login: ['登录','Login','ログイン'],
			loginLink: 'login.html',
			regist: ['注册', 'register', 'きにゅう'],
			registLink: 'register.html'
		},
		order: {
			title: ['我的订单','My Order','私の注文'],
			link: 'order.html',
			states: [{
				img: 'img/付款.png',
				title: ['待付款','obligation','支払いをする'],
				link: 'order.html#1'
			}, {
				img: 'img/发货.png',
				title: ['待发货','unshipped','未出荷'],
				link: 'order.html#2'
			}, {
				img: 'img/收货.png',
				title: ['待收货','wait for receiving','おそろい'],
				link: 'order.html#3'
			}, {
				img: 'img/评论.png',
				title: ['待评论','To comment on','コメントをする'],
				link: 'order.html#4'
			}]
		},
		menuBox: [{
			link: 'my-collect.html',
			img: 'img/collect.png',
			title: ['我的收藏','my collection','私のコレクション']
		}, {
			link: 'address-list.html',
			img: 'img/address.png',
			title: ['收货地址','My address','受け取り先']
		}, {
			link: 'logistics-center.html',
			img: 'img/物流信息.png',
			title: ['物流信息','logistics','物流情報']
		}, {
			link: sessionStorage.user ? 'personal-center.html' : 'login.html',
			img: 'img/个人信息.png',
			title: ['个人信息','Personal Information','個人情報']
		}, {
			link: 'changing-or-refunding.html',
			img: 'img/换货.png',
			title: ['换货','replacing goods','物品を取り換える']
		}, {
			link: 'my-comments.html',
			img: 'img/comment.png',
			title: ['我的评论','My comments','私の評論']
		}],
		opinion: {
			label: ['意见反馈','Feedback','意見のフィードバック'],
			link: ''
		},
		contactUs: {
			label: ['联系我们','Contact Us','我々に連絡する'],
			link: ''
		},
		aboutUs: {
			label: ['关于我们','About Us','私たちについて'],
			link: ''
		},
		set: ['设置','Set','備え付け'],
		reset: ['重置','Reset','リセット'],
		save: {
			text: ['保存','Save','ほぞん'],
			disable: false
		},
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
		}],
		loginout:['退出','Quit','おもいたつ'],
		toastTips8: ['两次输入的密码不一致', 'The two passwords differ', '2回のパスワードが一致しない。'],
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
		loginOut: function() {
			sessionStorage.user = '';
			mui.openWindow({
				url: 'login.html'
			});
		},
		updateHeadImage: function() {
			var imgSrc = 'img/trade.png';
			this.head.value = imgSrc;
		},
		updateName: function() {
			var that = this;
			this.save.disable = true;
			setTimeout(function() {
				that.save.disable = false;
				mui.back();
			}, 1000);
		},
		updatePhoneNumber: function(e) {
			var that = this;
			this.save.disable = true;
			setTimeout(function() {
				that.save.disable = false;
				mui.back();
			}, 1000);
		},
		updatePassword: function() {
			var that = this;
			if(this.password.value && this.password.value === this.password.value2) {
				this.save.disable = true;
				setTimeout(function() {
					that.save.disable = false;
					mui.back();
				}, 1000);
			} else {
				mui.alert(that.toastTips8[language]);
			}
		}
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
		if(sessionStorage.user) {
			this.Axios.get('api/user/my', {
				params: {
					api_token: sessionStorage.user
				}
			}).then(function(r) {
				console.log(r);
				that.head.img = r.data.data.avatar;
				sessionStorage.avatar = r.data.data.avatar;
			}).catch(function(error) {
				console.log(error);
			});
			that.getCartNum();
		}
		//初始化单页view
		var viewApi = mui('#app').view({
			defaultPage: '#setting'
		});
		var view = viewApi.view;
		(function($) {
			//处理view的后退与webview后退
			var oldBack = $.back;
			$.back = function() {
				if(viewApi.canBack()) { //如果view可以后退，则执行view的后退
					viewApi.back();
				} else { //执行webview后退
					oldBack();
				}
			};
			//监听页面切换事件方案1,通过view元素监听所有页面切换事件，目前提供pageBeforeShow|pageShow|pageBeforeBack|pageBack四种事件(before事件为动画开始前触发)
			//第一个参数为事件名称，第二个参数为事件回调，其中e.detail.page为当前页面的html对象
			view.addEventListener('pageBeforeShow', function(e) {
				//				console.log(e.detail.page.id + ' beforeShow');
			});
			view.addEventListener('pageShow', function(e) {
				//				console.log(e.detail.page.id + ' show');
			});
			view.addEventListener('pageBeforeBack', function(e) {
				//				console.log(e.detail.page.id + ' beforeBack');
			});
			view.addEventListener('pageBack', function(e) {
				//				console.log(e.detail.page.id + ' back');
			});
		})(mui);
	}
});