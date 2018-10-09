new Vue({
	el: '#details',
	data: {
		language: localStorage.choose,
		userId: '',
		goodsId: location.search.split('id=')[1],
		collectId: '',
		windowWidth: window.innerWidth + 'px',
		pageTitle: ['购物车', 'Cart', 'ショッピングカート'],
		chinaUnit: ['元', '$', '円'],
		unit: ['￥', '$', '￥'],
		allTrades: [],
		trades: [
			//		{
			//			title: '第一个选项卡子项第一个选项卡子项第一个选项卡子项',
			//			img: 'img/trade.png',
			//			price: 100.50,
			//			link: '#',
			//			monthSale: 1234
			//		}
		],
		monthSale: ['月销', 'monthSale', '月販売'],
		pcs: ['笔', 'time', 'ど'],
		barTabs: [{
			title: ['客服', 'service', 'コール'],
			num: null
		}, {
			title: '',
			num: null
		}, {
			title: ['购物车', 'Cart', 'ショッピングカート'],
			num: null
		}, {
			title: ['加入购物车', 'Add to Cart', 'カートに入れる'],
			num: null
		}],
		tradeDetail: {
			slides: [{
				img: '',
				link: ''
			}],
			id: '',
			price: '65-808',
			title: 'GNC健安喜正品美国Performix SST蓝魔胶 囊黑魔黄魔灰魔 60粒120粒',
			tips: ['快递：免运费', 264654, '山东青岛'],
			content: '',
			instruction: '',
			specifications: null,
			specification_prices: null,
			filter: [0, 0],
			num: 1,
		},
		detailTabs: [
			['商品详情', 'details of goods', '商品の詳細'],
			['说明书', 'Leaflet', 'しよう'],
			['全部评论', 'Comments', 'そうまくりする']
		],
		detailTip: ['详情','Details','しさい'],
		comments: {
			title: ['宝贝评价','evaluation','宝物評価'],
			totalPage: 20,
			pageNum: 10,
			data: [{
				img: '',
				name: '',
				content: '',
				time: ''
			}]
		},
		store: ['库存','inventory','在庫ストック'],
		check: ['请选择','Please Select','選択してください'],
		checked: ['已选','selected','すでに選挙'],
		quantity: ['购买数量','Quantity','購入数'],
		addCartButton: ['加入购物车', 'Add to Cart', 'カートに入れる'],
		collected: false,
		throttleTime: 2,
		contentrefresh:['正在加载...','loading...','ローディング中...'],
		contentnomore:['没有更多数据了',"There's no more data",'もっとデータがない。'],
		Views:['浏览量','views','閲覧数'],
		Sold:['已售','Sold','すでに売'],
		Collecting:['收藏','Collect','預け'],
		Collected:['已收藏','Collected','収蔵'],
		tips1:['请选择商品规格','Please select product specification','商品仕様を選んでください'],
		tips2:['您还没有选择宝贝哦',"You haven't chosen baby yet",'あなたはまだ宝物を選んでいませんよ'],
		featured:['精选商品','Featured','商品を精選する']
	},
	computed: {
		getCartNumber: function() {
			return this.totalNumber ? this.pageTitle + '(' + this.totalNumber + ')' : this.pageTitle;
		}
	},
	mounted: function() {
		var that = this;
		document.getElementById('startLoading').style.display='none';
		that.barTabs[1].title = that.Collecting[that.language];
		mui.init({
			swipeBack: false,
			pullRefresh: {
				container: '#item3',
				up: { //上拉加载
					contentrefresh: that.contentrefresh[that.language],
					contentnomore:that.contentnomore[that.language],
					callback: that.pullupRefresh
				}
			}
		});
		mui.previewImage();
		var that = this;
		this.Axios = axios.create({
			baseURL: 'http://demo2.ruanwe.com/',
			timeout: 12000,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'accept': 'application/json'
			}
		});
		//商品商品详情
		this.Axios.get('api/goods/' + that.goodsId).then(function(r) {
			console.log(r);
			var data = r.data.data;
			that.tradeDetail = {
				id: data.id,
				price: data.market_price,
				title: data.name,
				slides: [{
					img: data.cover,
					link: ''
				}],
				tips: [that.Views[that.language]+'：' + data.view, that.Sold[that.language]+'：' + data.sale_num, that.store[that.language]+'：' + data.store],
				store: data.store,
				content: data.content,
				instruction: data.instruction,
				num: 1,
				specifications: data.specifications,
				specification_prices: data.specification_prices,
				filter: [0, 0]
			}
		}).catch(function(error) {
			console.log(error);
		});
		//商品评论
		this.Axios.get('api/comment', {
			params: {
				goods_id: that.goodsId,
				page: 1
			}
		}).then(function(r) {
			console.log(r);
			that.comments.totalPage = r.data.last_page;
			that.comments.page = r.data.current_page;
			that.comments.data = r.data.data;

		}).catch(function(error) {
			console.log(error);
		});
		if(sessionStorage.user) {
			//购物车(获取购物车中商品数量)
			that.getCartNum();
			//收藏(获取商品收藏状态)
			this.Axios.get('api/collection/check/' + that.goodsId, {
				params: {
					api_token: sessionStorage.user
				}
			}).then(function(r) {
				console.log(r);
				that.collectId = r.data.data.id;
				that.collected = true;
				that.barTabs[1].title = that.Collected[that.language];
			}).catch(function(error) {
				console.log(error);
			});
		}
		//精选商品
		this.Axios.get('api/goods/relation/' + that.goodsId).then(function(r) {
			console.log(r);
			r.data.data.forEach(function(item) {
				that.trades.push({
					title: item.goods_name,
					img: item.cover,
					price: item.price,
					link: 'details.html?id=' + item.id
				});
			});
		}).catch(function(error) {
			console.log(error);
		});
	},
	methods: {
		linkToShopingCart: function() {
			mui.openWindow({
				url: 'shopingcart.html',
				id: 'shopingcart'
			});
		},
		linkToDetails: function(href) {
			mui.openWindow({
				url: href
			});
		},
		checkRadio: function(index, i) {
			//console.log(e.target.parentNode.classList);
			this.tradeDetail.filter[index] = this.tradeDetail.specifications[index].items[i].id;
		},
		toggleCollect: function(e) {
			var that = this;
			if(!sessionStorage.user) {
				location.href = 'login.html';
				return;
			}
			if(that.barTabs[1].title ===  that.Collecting[that.language]) { //收藏
				this.Axios.post('api/collection', {
					api_token: sessionStorage.user,
					goods_id: this.tradeDetail.id
				}).then(function(r) {
					console.log(r);
					that.collected = true;
					that.barTabs[1].title =  that.Collected[that.language]
					that.collectId = r.data.data.id; ///////////////////////////////
				}).catch(function(error) {
					console.log(error);
				});
			} else { //取消收藏
				this.Axios.post('api/collection/' + that.collectId, {
					api_token: sessionStorage.user,
					_method: 'delete'
				}).then(function(r) {
					console.log(r);
					that.collected = false;
					that.barTabs[1].title = that.Collecting[that.language]
				}).catch(function(error) {
					console.log(error);
				});
			}

		},
		addToCart: function() {
			var that = this;
			if(!sessionStorage.user) {
				location.href = 'login.html';
				return;
			}
			if(this.tradeDetail.specifications && this.tradeDetail.specifications.length) {
				if(this.tradeDetail.specification_prices[(this.tradeDetail.filter[0] + '_' + this.tradeDetail.filter[1])]) {
					//添加至购物车
					this.Axios.post('api/cart', {
						api_token: sessionStorage.user,
						goods_id: this.tradeDetail.id,
						item_id: this.tradeDetail.specification_prices[(this.tradeDetail.filter[0] + '_' + this.tradeDetail.filter[1])].item_id,
						num: this.tradeDetail.num
					}).then(function(r) {
						console.log(r);
						that.getCartNum();
						mui('#tabbar-with-market').popover('hide');
					}).catch(function(error) {
						console.log(error);
					});
				} else {
					mui.toast(that.tips1[that.language]);
				}
			} else {
				//添加至购物车
				this.Axios.post('api/cart', {
					api_token: sessionStorage.user,
					goods_id: this.tradeDetail.id,
					num: this.tradeDetail.num
				}).then(function(r) {
					console.log(r);
					that.getCartNum();
					mui('#tabbar-with-market').popover('hide');
				}).catch(function(error) {
					console.log(error);
				});
			}
		},
		getCartNum: function() {
			var that = this;
			this.Axios.get('api/cart', {
				params: {
					api_token: sessionStorage.user
				}
			}).then(function(r) {
				console.log(r);
				that.barTabs[2].num = r.data.total;
			}).catch(function(error) {
				console.log(error);
			});
		},
		minusNum: function(index, i) {
			this.stores[index].trades[i].numbers--;
		},
		plusNum: function(index, i) {
			this.stores[index].trades[i].numbers++;
		},
		moveToCollection: function() { //移至收藏夹
			if(this.checkedLists.length) {
				console.log(this.checkedLists);
			} else {
				mui.toast(that.tips2[that.language]);
			}
		},
		deleteTrade: function() {
			if(this.checkedLists.length) {
				console.log(this.checkedLists);
			} else {
				mui.toast(that.tips2[that.language]);
			}
		},
		pullupRefresh: function() { //下拉刷新
			var that = this;
			setTimeout(function() {
				mui('#item3').pullRefresh().endPullupToRefresh((that.page === that.totalPage)); //参数为true代表没有更多数据了。
				//获取所有评论
				if(that.page !== that.totalPage) {
					this.Axios.get('api/comment', {
						params: {
							goods_id: location.search.split('id=')[1],
							page: that.page
						}
					}).then(function(r) {
						console.log(r);
						that.comments.totalPage = last_page;
						that.comments.page = current_page;
						that.comments.data = [];
					}).catch(function(error) {
						console.log(error);
					});
				}
			}, 1500);
		},
		resetTime: function(time, fn) {
			var timer = null;
			var t = time;
			var m = 0;
			var s = 0;
			m = Math.floor(t / 60 % 60);
			m < 10 && (m = '0' + m);
			s = Math.floor(t % 60);

			function countDown() {
				s--;
				s < 10 && (s = '0' + s);
				if(s.length >= 3) {
					s = 59;
					m = "0" + (Number(m) - 1);
				}
				if(m.length >= 3) {
					m = '00';
					s = '00';
					clearInterval(timer);
				}
				s === '00' && fn();
			}
			timer = setInterval(countDown, 1000);
		}
	}
});