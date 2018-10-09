new Vue({
	el: '#search',
	data: {
		language: localStorage.choose,
		pageTitle: ['搜索页','Search page','検索ページ'],
		cancel: ['取消','cancel','やめる'],
		searhForm: {
			placeholder: ['请输入内容', 'Please enter content', '内容を入力してください'],
			value: decodeURI(location.search.split('name=')[1])|| '',
			active: false
		},
		kinds: [{
			title: '',
			id: '',
			active: true
		}],
		sort: [{
			title: '',
			name: 'synthesized',
			active: true
		}, {
			title: '',
			name: 'sales',
			active: false
		}],
		allKind:['全部','All','悉く'],
		defaultSort:['综合排序','synthesize','総合順位'],
		saleSort:['销量','Sales','うれあし'],
		selectedKindIndex: 0,
		selectedSortIndex: 0,
		trades: [],
		unit: ['￥','$','￥'],
		nothing: ['此商品不存在','The commodity does not exist','この商品は存在しません。'],
		loading: false,
		showSearch: true,
		scrollTop: 0,
		isThrottling: false,
		Axios: null,
		page: 1,
		totalPage: 1,
		contentrefresh:['正在加载...','loading...','ローディング中...'],
		contentnomore:['没有更多数据了',"There's no more data",'もっとデータがない。'],
	},
	computed: {
		selectedKind: function() {
			return this.kinds.filter(function(item) {
				return item.active;
			})[0];
		},
		selectedSort: function() {
			return this.sort.filter(function(item) {
				return item.active;
			})[0];
		}
	},
	mounted: function() {
		var that = this;
		document.getElementById('startLoading').style.display='none';
		mui.init({
			swipeBack: false,
			pullRefresh: {
				container: '#pullrefresh',
				up: {
					contentrefresh: that.contentrefresh[that.language],
					contentnomore:that.contentnomore[that.language],
					callback: this.pullupRefresh
				}
			}
		});
		this.Axios = axios.create({
			baseURL: 'http://demo2.ruanwe.com/',
			timeout: 5000,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		});
		that.loading = true;
		//获取所有商品
		this.Axios.get('api/goods', {
			params: {
				category_id: that.kinds[that.selectedKindIndex].id,
				sort: that.sort[that.selectedSortIndex].name,
				q: that.searhForm.value,
				page: 1
			}
		}).then(function(r) {
			console.log(r);
			that.page = r.data.current_page;
			that.totalPage = r.data.last_page;
			r.data.data.forEach(function(item) {
				that.trades.push({
					title: item.name,
					img: item.cover,
					price: item.price,
					market_price: item.market_price,
					link: 'details.html?id=' + item.id
				})
			});
			that.loading = false;
		}).catch(function(error) {
			console.log(error);
			that.loading = false;
		});

		//获取所有分类
		that.kinds[0].title=that.allKind[that.language];
		that.sort[0].title=that.defaultSort[that.language];
		that.sort[1].title=that.saleSort[that.language];
		this.Axios.get('api/goodsCategory/tree', {
			params: {
				is_show: '1',
				all: '1'
			}
		}).then(function(r) {
			console.log(r);
			that.page = r.data.current_page;
			that.totalPage = r.data.last_page;
			r.data.forEach(function(item) {
				that.kinds.push({
					title: item.name,
					id: item.id,
					active: false
				});
			});
		}).catch(function(error) {
			console.log(error);

		});
	},
	methods: {
		linkToDetails: function(link) {
			mui.openWindow({
				url: link,
				id: 'details'
			})
		},
		changeFormStyle: function() {
			this.searhForm.active = true;
		},
		resetFormStyle: function() {
			this.searhForm.active = false;
		},
		blurSearchForm: function() {
			this.$refs.searchForm.blur();
		},
		submitSearchContent: function(e) {
			e.target.blur();
			var that = this;
			that.page = 1;
			that.loading = true;
			that.selectedKindIndex = 0;
			that.selectedSortIndex = 0
			//搜索
			that.Axios.get('api/goods', {
				params: {
					page: 1,
					q: that.searhForm.value,
					category_id: that.kinds[that.selectedKindIndex].id,
					sort: that.sort[that.selectedSortIndex].name
				}
			}).then(function(r) {
				console.log(r);
				that.page = r.data.current_page;
				that.totalPage = r.data.last_page;
				that.trades = [];
				r.data.data.forEach(function(item) {
					that.trades.push({
						title: item.name,
						img: item.cover,
						price: item.price,
						market_price: item.market_price,
						link: 'details.html?id=' + item.id
					})
				});
				that.loading = false;
			}).catch(function(error) {
				console.log(error);
				that.loading = false;
			});
		},
		selectKind: function(index) {
			var that = this;
			for(var i = 0, len = this.kinds.length; i < len; i++) {
				if(this.kinds[i].active) {
					this.kinds[i].active = false;
					break;
				}
			}
			this.kinds[index].active = true;
			that.selectedKindIndex = index;
			mui('#leftPopover').popover('hide');
			//选择分类
			that.loading = true;
			setTimeout(function() {
				that.trades = [];
				that.Axios.get('api/goods', {
					params: {
						page: 1,
						q: that.searhForm.value,
						category_id: that.kinds[that.selectedKindIndex].id,
						sort: that.sort[that.selectedSortIndex].name
					}
				}).then(function(r) {
					console.log(r);
					that.page = r.data.current_page;
					that.totalPage = r.data.last_page;
					r.data.data.forEach(function(item) {
						that.trades.push({
							title: item.name,
							img: item.cover,
							price: item.price,
							market_price: item.market_price,
							link: 'details.html?id=' + item.id
						})
					});
					that.loading = false;
				}).catch(function(error) {
					console.log(error);
					that.loading = false;
				});
				that.loading = false;
			}, 1000);
		},
		selectSort: function(index) {
			var that = this;
			for(var i = 0, len = this.sort.length; i < len; i++) {
				if(this.sort[i].active) {
					this.sort[i].active = false;
					break;
				}
			}
			this.sort[index].active = true;
			that.selectedSortIndex = index;
			mui('#middlePopover').popover('hide');
			//排序
			that.loading = true;
			that.Axios.get('api/goods', {
				params: {
					page: 1,
					q: that.searhForm.value,
					category_id: that.kinds[that.selectedKindIndex].id,
					sort: that.sort[that.selectedSortIndex].name
				}
			}).then(function(r) {
				console.log(r);
				that.page = r.data.current_page;
				that.totalPage = r.data.last_page;
				that.trades = [];
				r.data.data.forEach(function(item) {
					that.trades.push({
						title: item.name,
						img: item.cover,
						price: item.price,
						market_price: item.market_price,
						link: 'details.html?id=' + item.id
					})
				});
				that.loading = false;
			}).catch(function(error) {
				console.log(error);
				that.loading = false;
			});
		},
		hideSearchBar: function() {
			this.showSearch = false;
		},
		showSearchBar: function() {
			this.showSearch = true;
		},
		toggleSearchBar: function(e) {
			var that = this;
			if(!that.isThrottling) {
				that.isThrottling = true;
				e.target.scrollTop - this.scrollTop > 0 ? (this.showSearch = false) : (this.showSearch = true);
				this.scrollTop = e.target.scrollTop;
				setTimeout(function() {
					that.isThrottling = false;
				}, 600);
			}
		},
		/* 上拉加载具体业务实现 */
		pullupRefresh: function() {
			var that = this;
			setTimeout(function() {
				mui('#pullrefresh').pullRefresh().endPullupToRefresh((that.page === that.totalPage)); //参数为true代表没有更多数据了。
				//获取所有分类
				that.Axios.get('api/goods', {
					params: {
						page: that.page + 1,
						q: that.searhForm.value,
						category_id: that.kinds[that.selectedKindIndex].id,
						sort: that.sort[that.selectedSortIndex].name
					}
				}).then(function(r) {
					console.log(r);
					that.page = r.data.current_page;
					that.totalPage = r.data.last_page;
					r.data.data.forEach(function(item) {
						that.trades.push({
							title: item.name,
							img: item.cover,
							price: item.price,
							market_price: item.market_price,
							link: 'details.html?id=' + item.id
						})
					});
				}).catch(function(error) {
					console.log(error);
				});
			}, 1500);
		}
	}
});