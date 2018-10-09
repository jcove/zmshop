new Vue({
	el: '#shopingCart',
	data: {
		language:localStorage.choose,
		cartNum:null,
		userId: sessionStorage.user,
		pageTitle:['购物车','Cart','ショッピングカート'],
		editButton: [['管理','control','かんり'], ['完成','finish','かんせい']],
		checkAll: ['全选','checkAll','全テーマ検索'],
		pay: ['结算','balance','決算'],
		sum: ['合计','total','合計する'],
		totalNumber: 0,
		totalPrice: 0,
		standBy: ['立减','standby','立减'],
		standByNum: 0,
		chinaUnit: ['元','$','円'],
		unit: ['￥','$','￥'],
		clear: ['清理','clear','片づける'],
		moveToFavorite: ['移入收藏夹','Move to favorites','お気に入りに移動する'],
		del: ['删除','delete','ちょうはいする'],
		isEdit: false,
		stores: [],
		/*stores: [{
			name: '木子大药房1',
			link: '#',
			trades: [{
				name: '时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟',
				img: 'img/trade.png',
				remark: '500ml*4瓶时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟',
				price: 20,
				is_check: false,
				num: 1,
				id: '1'
			}]
		}, {
			name: '木子大药房2',
			link: '#',
			trades: [{
				name: '时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟',
				img: 'img/trade.png',
				remark: '500ml*4瓶',
				price: 20,
				is_check: true,
				id: '2',
				num: 1
			}]
		}],*/
		checkedLists: [
			//		{
			//			name: '时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟',
			//			img: 'img/trade.png',
			//			remark: '500ml*4瓶',
			//			price: 20,
			//			is_check: true,
			//			id: '2',
			//			num: 1
			//		}
		],
		allTrades: [],
		//		checkAllButton: false,
		login: ['登录','login','ログイン'],
		empty: {
			tip1: ['购物车还很空','The shopping cart is still empty','買い物車はまだ空っぽだ'],
			tip2: ['登录后即可同步您的购物车商品','Once logged in, you can synchronize your shopping cart items','登录したら、お买い物の商品を同期します']
		},
		count: 0,
		trades: [],
		monthSale: ['月销','monthSale','月販売'],
		pcs: ['笔','time','ど'],
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
		Axios: null,
		contentrefresh:['正在加载...','loading...','ローディング中...'],
		contentnomore:['没有更多数据了',"There's no more data",'もっとデータがない。'],
		checkbefore:['请先选择商品','Please select the item first','商品を先に選んでください。'],
		toastTips1:['您还没有选择宝贝哦',"You haven't chosen baby yet",'あなたはまだ宝物を選んでいませんよ。'],
		toastTips2:['删除失败，请稍后再试','Deletion failed. Please try again later','削除に失敗しますので、後ほど試してください。'],
		featured:['精选商品','Featured','商品を精選する']
	},
	computed: {
		getCheckedNumber: function() {
			var that = this;
			var checkedNumber = 0;
			that.totalNumber = 0;
			that.allTrades = [];
			that.checkedLists = [];
			this.stores.forEach(function(item0) {
				item0.trades.forEach(function(item1) {
					item1.is_check && (checkedNumber++,
						that.checkedLists.push(item1)
					);
					that.allTrades.push(item1);
					that.totalNumber++;
				});
			});
			//console.log(that.checkedLists)
			//获取促销信息
			this.getPromotion();
			return checkedNumber;
		},
		getCartNumber: function() {
			return this.totalNumber ? this.pageTitle + '(' + this.totalNumber + ')' : this.pageTitle;
		},
		checkAllButton: function() {
			return this.checkedLists.length === this.stores.length;
		}
	},
	mounted: function() {
		var that = this;
		document.getElementById('startLoading').style.display='none';
		mui.init({
			swipeBack: false,
			pullRefresh: {
				container: '#pullrefresh',
				up: { //下拉刷新
					contentrefresh: that.contentrefresh[that.language],
					contentnomore:that.contentnomore[that.language],
					callback: that.pullupRefresh
				}
			}
		});
		that.Axios = axios.create({
			baseURL: 'http://demo2.ruanwe.com/',
			timeout: 5000,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		});
		//购物车/列表
		that.getCartList();
		//精选商品
		this.Axios.get('api/goods', {
			is_recommend: '1'
		}).then(function(r) {
			console.log(r);
			r.data.data.forEach(function(item) {
				that.trades.push({
					title: item.name,
					img: item.cover,
					price: item.price,
					link: 'details.html?id=' + item.id,
					//					monthSale: 1234
				});
			});
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
		linkToLogin: function() {
			mui.openWindow({
				url: 'login.html',
				id: 'login'
			});
		},
		linkToDetails: function(href) {
			mui.openWindow({
				url: href
			});
		},
		getCartList: function() {
			var that = this;
			that.Axios.get('api/cart', {
				headers: {
					'accept': 'application/json'
				},
				params: {
					api_token: that.userId
				}
			}).then(function(r) {
				console.log(r);
				that.stores = [];
				that.checkedLists = [];
				if(r.data.data) {
					for(var key in r.data.data) {
						var item = r.data.data[key];
						if(item.is_check) {
							that.checkedLists.push(item);
						}
						that.stores.push({
							name: item.goods_id,
							link: '#',
							item_id: item.id,
							trades: [{
								name: item.goods_name,
								img: item.cover,
								id: item.id,
								is_check: item.is_check,
								remark: '*' + item.num,
								price: item.price,
								num: item.num,
								goodsId: item.goods_id
							}]
						});
					}
				}
			}).catch(function(error) {
				console.log(error);
			});
		},
		linkToOrderpay: function() {
			var that=this;
			if(this.checkedLists.length) {
				mui.openWindow({
					url: 'order-pay.html',
					id: 'orderpay'
				});
			} else {
				mui.toast(that.checkbefore[that.language]);
			}
		},
		getPromotion: function() {
			var that = this;
			that.totalPrice = 0;
			that.standByNum = 0;
			that.checkedLists.length && (
				this.Axios.post('shop/promotion/products', {
					products: that.checkedLists
				}).then(function(r) {
					//console.log(r);
					r.data[0].products.forEach(function(item) {
						that.totalPrice += (item.num * item.final_price);
						that.standByNum += (item.price - item.final_price);
					});
				}).catch(function(error) {
					console.log(error);
				})
			);
		},
		toggleEdit: function() { //管理
			this.isEdit = !this.isEdit;
			this.editButton.reverse();
		},
		postCheckOption: function(id) {
			console.log(id)
			var that = this;
			this.Axios.post('api/cart/check/' + id, {
				api_token: that.userId
			}).then(function(r) {
				console.log(r);
			}).catch(function(error) {
				console.log(error);
			});
		},
		minusNum: function(index, i, item) {
			var that = this;
			this.stores[index].trades[i].num--;
			//添加至购物车
			this.Axios.post('api/cart', {
				api_token: that.userId,
				goods_id: item.name,
				item_id: item.item_id,
				num: -1
			}).then(function(r) {
				console.log(r);
			}).catch(function(error) {
				console.log(error);
			});
			this.getPromotion();
		},
		plusNum: function(index, i, item) {
			var that = this;
			this.stores[index].trades[i].num++;
			//添加至购物车
			this.Axios.post('api/cart', {
				api_token: that.userId,
				goods_id: item.name,
				item_id: item.item_id,
				num: 1
			}).then(function(r) {
				console.log(r);
			}).catch(function(error) {
				console.log(error);
			});
			this.getPromotion();
		},
		moveToCollection: function() { //移至收藏夹
			var that=this;
			if(this.checkedLists.length) {
				console.log(this.checkedLists);
				this.checkedLists.forEach(function(item) {
					item.is_check && (
						that.Axios.post('api/collection/cartTo', {
							api_token: that.userId,
							goods_id:item.goodsId
						}).then(function(r) {
							console.log(r);
							that.getCartList();
						}).catch(function(error) {
							console.log(error);
						})
					);
				});
			} else {
				mui.toast(that.toastTips1[that.language]);
			}
		},
		deleteTrade: function() {
			var that = this;
			if(this.checkedLists.length) {
				console.log(this.checkedLists);
				this.Axios.post('api/cart/checked', {
					api_token: that.userId,
					_method: 'delete'
				}).then(function(r) {
					console.log(r);
					if(r.data.message === 'success') {
						that.getCartList();
					} else {
						mui.toast(that.toastTips2[that.language]);
					}
				}).catch(function(error) {
					console.log(error);
				});
			} else {
				mui.toast(that.toastTips1[that.language]);
			}
		},
		checkAllTrades: function() {
			var that = this;
			if(!this.checkAllButton) {
				this.stores.forEach(function(item0) {
					item0.trades.forEach(function(item1) {
						!item1.is_check && (item1.is_check = true, that.postCheckOption(item1.id));
					});
				});
			} else {
				this.stores.forEach(function(item0) {
					item0.trades.forEach(function(item1) {
						item1.is_check && (item1.is_check = false, that.postCheckOption(item1.id));
					});
				});
			}
		},
		pullupRefresh: function() { //下拉刷新
			mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
		}
	}
});