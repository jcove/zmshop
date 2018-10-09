new Vue({
	el: '#order',
	data: {
		language: localStorage.choose,
		userId: sessionStorage.user,
		pageTitle: ['我的订单', 'My orders', '私の注文'],
		unit: ['￥', '$', '￥'],
		tradeText: [
			['共', 'common', '共に'],
			['件商品', 'piece goods', 'へんしょうひん'],
			['合计', 'total', 'ごうけい']
		],
		activeState: Number(location.hash.split('#')[1]) || 0,
		buttons: [
			[{
				name: ['已取消', 'canceled', 'とりけし']
			}],
			[{
				name: ['取消订单', 'cancel order', '注文を取り消す']
			}, {
				name: ['付款', 'pay', 'はらいもどす']
			}],
			[{
				name: ['取消订单', 'cancel order', '注文を取り消す']
			}],
			[{
				name: ['查看物流', 'check logistics', '物流を調べる']
			}, {
				name: ['确认收货', 'confirm receipt', '受取を確認する']
			}],
			[{
				name: ['点击评论', 'to comment', 'ろんじる']
			}],
			[{
				name: ['已评论', 'commented', 'すでにコメント']
			}]
		],
		returnBack: ['售后', 'after sale service', 'アフターケア'],
		orderTabs: {
			classify: [{
				id: 'all',
				title: ['全部', 'All', 'ぜんぶ'],
				stores: []
			}, {
				id: 'unPayment',
				title: ['待付款', 'unPayment', '支払いをする'],
				stores: []
			}, {
				id: 'unShipped',
				title: ['待发货', 'unShipped', 'せっしょく'],
				stores: []
			}, {
				id: 'unReceive',
				title: ['待收货', 'unReceive', 'おそろい'],
				stores: []
			}, {
				id: 'unReview',
				title: ['待评价', 'unReview', '評価を受ける'],
				stores: []
			}]
		},
		confirm: ['确定', 'confirm', '落し着ける'],
		cancel: ['取消', 'cancel', 'とりけし']
	},
	mounted: function() {
		var that = this;
		document.getElementById('startLoading').style.display = 'none';
		mui.init({
			swipeBack: false
		});
		mui('.mui-scroll-wrapper').scroll({
			indicators: true //是否显示滚动条
		});
		that.Axios = axios.create({
			baseURL: 'http://demo2.ruanwe.com/',
			timeout: 12000,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'accept': 'application/json'
			}
		});
		//获取订单
		that.getOrder();
	},
	methods: {
		getOrder: function() {
			var btnType = {
				'0': 1,
				'2': 2,
				'4': 3,
				'6': 4,
				'8': 5,
				'-1': 0
			};
			var that = this;
			this.Axios.get('api/order', {
				params: {
					api_token: that.userId
				}
			}).then(function(r) {
				console.log(r);
				that.orderTabs.classify[0].stores = [];
				that.orderTabs.classify[1].stores = [];
				that.orderTabs.classify[2].stores = [];
				that.orderTabs.classify[3].stores = [];
				that.orderTabs.classify[4].stores = [];
				r.data.data.forEach(function(item,index) {
					that.orderTabs.classify[0].stores.push({
						name: item.order_sn,
						addressId: item.id,
						express_code: item.express_code,
						express_sn: item.express_sn,
						pay_code: item.pay_code,
						trades: [],
						totalPrice: item.total_amount,
						buttonIndex: btnType[item.order_status],
						numbers:item.order_goods.length
					});
					if(btnType[item.order_status] < 5 && btnType[item.order_status]) {
						that.orderTabs.classify[btnType[item.order_status]].stores.push({
							name: item.order_sn,
							addressId: item.id,
							pay_code: item.pay_code,
							express_code: item.express_code,
							express_sn: item.express_sn,
							trades: [],
							totalPrice: item.total_amount,
							buttonIndex: btnType[item.order_status],
							numbers:item.order_goods.length
						});
					}
					item.order_goods.forEach(function(j, jindex) {
						that.orderTabs.classify[0].stores[index].trades.push({
							goodsId: j.goods_id,
							orderId: j.order_id,
							title: j.goods_name,
							img: j.cover,
							unitprice: j.final_price,
							numbers: j.num,
							goods_spec_item_id:j.goods_spec_item_id
						});
						if(btnType[item.order_status] < 5 && btnType[item.order_status]) {
							that.orderTabs.classify[btnType[item.order_status]].stores[index].trades.push({
								goodsId: j.goods_id,
								orderId: j.order_id,
								title: j.goods_name,
								img: j.cover,
								unitprice: j.final_price,
								numbers: j.num,
								goods_spec_item_id:j.goods_spec_item_id
							});
						}
					});
				});
				console.log(that.orderTabs)
			}).catch(function(error) {
				console.log(error);
			});
		},
		tapButton: function(name,itemin,goodindex) {
			var that = this;
			//console.log(JSON.stringify(itemin))
			switch(name) {
				case that.buttons[1][0].name[that.language]: //取消订单
					mui.confirm(that.confirm[that.language] + name + '?', ' ', [that.confirm[that.language], that.cancel[that.language]], function(e) {
						if(e.index === 0) {
							that.Axios.post('api/order/cancel/' + itemin.trades[0].orderId, {
								api_token: that.userId,
								address_id: itemin.addressId,
								pay_code: itemin.pay_code
							}).then(function(r) {
								console.log(r);
								that.getOrder();
							}).catch(function() {
								console.log(r);
							});
						}
					});
					break;
				case that.buttons[3][1].name[that.language]: //确认收货
					mui.confirm(that.confirm[that.language] + name + '?', ' ', [that.confirm[that.language], that.cancel[that.language]], function(e) {
						if(e.index === 0) {
							that.Axios.post('api/order/confirm/' + itemin.trades[0].orderId, {
								api_token: that.userId,
								address_id: itemin.addressId,
								pay_code: itemin.pay_code
							}).then(function(r) {
								console.log(r);
								that.getOrder();
							}).catch(function() {
								console.log(r);
							});
						}
					});
					break;
				case that.buttons[1][1].name[that.language]: //付款
					that.linkToPay(itemin.trades[0].orderId);
					break;
				case that.buttons[3][0].name[that.language]: //物流
					that.getExpress(itemin);
					break;
				case '售后': //售后
				    sessionStorage.ordergoods=itemin;
					mui.openWindow({
						url: 'changing-or-refunding-edit.html?orderid=' + itemin.trades[0].orderId+'&index='+goodindex,
						id: 'changing'
					});
					
					break;
				case that.buttons[4][1].name[that.language]: //评论
				    sessionStorage.goods=itemin.trades;
					mui.openWindow({
						url: 'comment.html?orderid=' + itemin.trades[0].orderId,
						id: 'comment'
					});
					break;
				default:
					break;
			}
		},
		changeTab: function(event) {
			var that = this;
			var datas;
			var index = event.detail.slideNumber;
			var currentContent = that.orderTabs.classify[index].stores;
			that.orderTabs.classify[index].stores = null;
			that.activeState = index;
			setTimeout(function() {
				//datas = JSON.parse('[{"name":"木子大药房","link":"#","trades":[{"title":"时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟","img":"img/trade.png","unitprice":20,"numbers":1},{"title":"时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟","img":"img/trade.png","unitprice":20,"numbers":1}],"numbers":2,"totalprice":40},{"name":"木子大药房","link":"#","trades":[{"title":"时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟","img":"img/trade.png","unitprice":20,"numbers":1},{"title":"时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟时代峰峻卡进口国画的跟","img":"img/trade.png","unitprice":20,"numbers":1}],"numbers":2,"totalprice":40}]');
				that.orderTabs.classify[index].stores = currentContent;
			}, 500);
		},
		linkToPay: function(id) {
			var that = this;
			that.Axios.get('api/order/pay/' + id, {
				params: {
					api_token: that.userId
				}
			}).then(function(r) {
				console.log(r);
				mui.openWindow({
					url: r.data.pay_url
				});
			}).catch(function(error) {
				console.log(error);
			});
		},
		getExpress: function(itemin) {
			sessionStorage.orderitem = JSON.stringify(itemin);
			mui.openWindow({
				url: 'logistics-msg.html'
			});
		}
	}
});