//登录页
new Vue({
	el: '#index',
	data: {
		language: localStorage.choose,
		cartNum: null,
		searchPlaceholder: ['请输入内容', 'Please enter content', '内容を入力してください'],
		searchButton: ['搜索', 'search', '名·他サ'],
		searchValue: '',
		signs: [{
			text: ['自营正品', 'NUSKIN', '自業自得'],
			link: 'img/自营.png'
		}, {
			text: ['产品授权', 'Authorize', '製品の許可'],
			link: 'img/授权.png'
		}, {
			text: ['隐私包装', 'Privacy', 'くらがりほうそう'],
			link: 'img/服务.png'
		}],
		swiper: [{
			img: 'img/index-bg1.png',
			link: '#'
		}, {
			img: 'img/index-bg2.png',
			link: '#'
		}, {
			img: 'img/index-bg3.png',
			link: '#'
		}],
		nav: [{
			img: 'img/menu0.png',
			title: ['商品分类', 'Category', '商品分類'],
			link: 'list.html'
		}, {
			img: 'img/menu1.png',
			title: ['购物车', 'Cart', 'ショッピングカート'],
			link: 'shopingcart.html'
		}, {
			img: 'img/menu2.png',
			title: ['在线客服', 'service', 'コール'],
			link: '#'
		}, {
			img: 'img/menu3.png',
			title: ['品牌专区', 'Brand', 'ブランド特別区'],
			link: 'search.html?name='
		}, {
			img: 'img/menu4.png',
			title: ['我的订单', 'My Order', '私の注文'],
			link: 'order.html'
		}, {
			img: 'img/menu5.png',
			title: ['产地信息', 'Origin', '産地情報'],
			link: 'search.html?name='
		}],
		tradeTitle1: ['热门推荐', 'Popular recommend', '人気のお勧め'],
		trades1: [
			/*{
						img: 'img/trade.png',
						title: '买一送好礼',
						link: 'details.html',
						price: 106,
						oldPrice: 118,
						unit: '￥'
					}*/
		],
		tradeTitle2: ['产地信息', 'Origin information', '産地情報'],
		trades2: [
			/*{
						img: 'img/origin0.png',
						title: '1F 民族国药',
						link: 'search.html',
						trades: [{
							img: 'img/trade.png',
							title: '买一送好礼',
							link: 'details.html',
							price: 106,
							oldPrice: 118,
							unit: '￥'
						}]
					}, {
						img: 'img/origin1.png',
						title: '2F 日本馆',
						link: 'search.html',
						trades: []
					}, {
						img: 'img/origin2.png',
						title: '3F 香港馆',
						link: 'search.html',
						trades: []
					}, {
						img: 'img/origin3.png',
						title: '4F 台湾馆',
						link: 'search.html',
						trades: []
					}, {
						img: 'img/origin4.png',
						title: '5F 印度馆',
						link: 'search.html',
						trades: []
					}*/
		],
		tradeTitle3: ['品牌专区', 'Brand', 'ブランド特別区'],
		tradeTitle3Img: 'img/part-title1.png',
		trades3: [
			//		{
			//			img: 'img/trade.png',
			//			title: '买一送好礼',
			//			link: 'details.html',
			//			price: 106,
			//			oldPrice: 118,
			//			unit: '￥'
			//		}
		],
		tradeTitle4: ['健康资讯', 'Health Info', '健康情報'],
		tradeTitle4Link: 'health-info.html',
		readMore: ['查看更多', 'More', '多くを見る'],
		trades4: [{
			img: 'img/trade.png',
			title: '买一送好礼',
			time: '2018-2-25',
			link: 'health-info-details.html',
			content: '健康管理产业属于健康产业的四大基本产业群体之一，由三个大的基本服务模块构成，即健康检测与监测、健康评估与指导由三个大的基本服务模块构成，即健康检测与监测、健康评估与指导'
		}],
		menu: [{
			title: ['首页', 'Index', 'しょおもて'],
			iconfont: 'icon-index',
			link: 'main.html'
		}, {
			title: ['分类', 'Category', '分類する'],
			iconfont: 'icon-list',
			link: 'list.html'
		}, {
			title: ['购物车', 'Cart', 'ショッピングカート'],
			iconfont: 'icon-cart',
			link: 'shopingcart.html'
		}, {
			title: ['我的', 'My', '私の物'],
			iconfont: 'icon-person',
			link: 'my-center.html'
		}],
		banners: [{
			img: '',
			link: ''
		}, {
			img: '',
			link: ''
		}, {
			img: '',
			link: ''
		}],
		Axios: null,
		toastTips: ['请先登录', 'Please login first', '先に登録してください'],
		unit: ['￥', '$', '￥'],
	},
	mounted: function() {
		document.getElementById('startLoading').style.display = 'none';
		var that = this;
		this.Axios = axios.create({
			baseURL: 'http://demo2.ruanwe.com/',
			timeout: 5000,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		});
		//热门推荐
		this.Axios.get('api/goods', {
			params: {
				is_recommend: '1'
			}
		}).then(function(r) {
			//console.log(r);
			that.trades1 = [];
			for(var i = 0; i < 6; i++) {
				var item = r.data.data[i];
				that.trades1.push({
					img: item.cover,
					title: item.name,
					link: 'details.html?id=' + item.id,
					price: item.price,
					oldPrice: item.market_price,
					unit: that.unit[that.language]
				});
			}
		}).catch(function(error) {
			console.log(error);
		});
		//产地信息
		that.Axios.get('api/goodsCategory/children/0', {
			params: {
				is_show: '1'
			}
		}).then(function(r) {
			var datas = r.data;
			that.trades2 = [];
			datas.forEach(function(item, index) {
				that.trades2.push({
					link: 'list.html?id=' + item.id,
					title: item.name,
					img: '',
					trades: []
				});
				that.getCateImg('mobile_index_category_cover_'+item.id,index);
				that.getTrade(item.id, index);
			});
			//console.log(r);
		}).catch(function(error) {
			console.log(error);
		});
		//品牌专区-商品列表
		that.Axios.get('api/goods', {
			params: {
				brand: '同仁堂',
				category_id: '1'
			}
		}).then(function(r) {
			//console.log(r);
			that.trades3 = [];
			for(var i = 0; i < 6; i++) {
				var item = r.data.data[i];
				that.trades3.push({
					img: item.cover,
					title: item.name,
					link: 'details.html?id=' + item.id,
					instruction: item.instruction
				});
			}
		}).catch(function(error) {
			console.log(error);
		});
		//广告位
		that.getBannerImg('mobile_index_banner_1', 0);
		that.getBannerImg('mobile_index_banner_2', 1);
		that.getBannerImg('mobile_index_banner_3', 2);
		//swipers轮播
		that.Axios.get('common/ad', {
			headers: {
				accept: 'application/json'
			},
			params: {
				position: 'mobile_index_slide'
			}
		}).then(function(r) {
			console.log(r);
			var cateList = r.data.data;
			that.swiper = [];
			cateList.forEach(function(item) {
				that.swiper.push({
					img: item.code,
					link: 'details.html?id=' + item.id
				})
			});
		}).catch(function(error) {
			console.log(error);
		});
		//品牌专区-头部
		that.Axios.get('common/ad', {
			headers: {
				accept: 'application/json'
			},
			params: {
				position: 'mobile_index_brand_banner'
			}
		}).then(function(r) {
			console.log(r);
			that.tradeTitle3Img = r.data.data[0].code;
		}).catch(function(error) {
			console.log(error);
		});

		//健康资讯
		this.Axios.get('api/article').then(function(r) {
			//console.log(r);
			that.trades4 = [];
			for(var i = 0; i < 6; i++) {
				var item = r.data.data[i];
				that.trades4.push({
					img: item.cover,
					title: item.title,
					time: item.created_at,
					link: 'health-info-details.html?id=' + item.id,
					content: item.content
				});
			}
		}).catch(function(error) {
			console.log(error);
		});
		if(sessionStorage.user) {
			that.getCartNum();
		}
	},
	methods: {
		getCateImg:function(postData,index){
			var that = this;
			this.Axios.get('common/ad', {
				headers: {
					accept: 'application/json'
				},
				params: {
					position: postData
				}
			}).then(function(r) {
				console.log(r);
				that.trades2[index].img=r.data.data[0].code;
			}).catch(function(r) {
				console.log(r)
			});
		},
		getBannerImg: function(postData, index) {
			var that = this;
			this.Axios.get('common/ad', {
				headers: {
					accept: 'application/json'
				},
				params: {
					position: postData
				}
			}).then(function(r) {
				console.log(r);
				that.banners[index].img = r.data.data[0].code;
				that.banners[index].link = 'details.html?id=' + r.data.data[0].id;
			}).catch(function(r) {
				console.log(r)
			});
		},
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
		toCommented: function() {
			var that = this;
			if(sessionStorage.user) {
				mui.openWindow({
					url: 'commented.html',
					id: 'commented'
				});
			} else {
				mui.toast(that.toastTips[that.language]);
			}
		},
		changeTab: function() {
			this.login.tab.forEach(function(item) {
				item.active = !item.active;
			});
		},
		toSearch: function() {
			mui.openWindow({
				url: 'search.html?name=' + encodeURI(this.searchValue),
				id: 'search'
			});
		},
		getTrade: function(id, index) {
			var that = this;
			that.Axios.get('api/goods', {
				params: {
					category_id: id
				}
			}).then(function(r) {
				that.trades2[index].trades = [];
				r.data.data.forEach(function(item) {
					that.trades2[index].trades.push({
						img: item.cover,
						title: item.name,
						link: 'details.html?id=' + item.id,
						price: item.price,
						oldPrice: item.market_price,
						unit: that.unit[that.language]
					});
				});
			}).catch(function(error) {
				console.log(error);
			});
		}
	}
});
var indexSlide = new Swiper('.indexslide', {
	loop: true,
	autoplay: {
		delay: 3000,
		disableOnInteraction: false,
	}
});