new Vue({
	el: '#addressEdit',
	data: {
		userId: sessionStorage.user,
		language: localStorage.choose,
		pageTitle: '',
		editAddress: ['编辑收货地址', 'Edit harvest address', '収穫場所を編集する'],
		addAddress: ['添加收货地址', 'Add shipping address', '商品を追加します'],
		consignee: ['收货人', 'Consignee', '荷受け人'],
		phone: ['手机号', 'Phone number', '携帯電話番号'],
		setDefault:['设置默认地址','Set as default address','既定の住所に設定する'],
		delDefault:['删除默认地址','Delete default address','黙認の住所を削除する'],
		options: {
			"consignee": "",
			"country": "中国大陆",
			"province": "",
			"city": "",
			"district": "",
			"address": "",
			"phone": "",
			"is_default": 0
		},
		provinceValue: '',
		cityValue: '',
		districtValue: '',
		submitbutton: ['完成', 'finish', '成り合う'],
		placeholder: ['详细地址：如道路、门牌号、小区、楼栋号、单元室等', 'Detailed address: such as road, house number, community, building, unit room', '詳細住所:道路、番地、アパート、棟棟、単位室など。'],
		isEdit: false,
		saveCompelete:['保存成功','save successfully','成功を保存する'],
		delCompelete:['删除成功','deleted successfully','削除に成功する']
	},
	mounted: function() {
		var that = this;
		document.getElementById('startLoading').style.display='none';
		mui.init({
			swipeBack: false
		});
		mui('.mui-scroll-wrapper').scroll({
			indicators: true //是否显示滚动条
		});
		that.Axios = axios.create({
			baseURL: 'http://demo2.ruanwe.com/',
			timeout: 5000,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		});
		if(sessionStorage.currenteditaddress) {
			that.pageTitle = that.editAddress[that.language];
			//获取地址信息
			that.isEdit = true;
			that.options = JSON.parse(sessionStorage.currenteditaddress);
			console.log(that.options);
		} else {
			that.pageTitle = that.addAddress[that.language];
			that.isEdit = false;
		}
		if(!that.language) {
			/*获取城市select*/
			(function($, doc) {
				var _getParam = function(obj, param) {
					return obj[param] || '';
				};
				var cityPicker3 = new $.PopPicker({
					layer: 3
				});
				cityPicker3.setData(cityData3);
				//设置默认值
				that.computeSelectValue();
				// 设定省初始值
				cityPicker3.pickers[0].setSelectedIndex(that.provinceValue);
				// 设定市初始值
				cityPicker3.pickers[1].setSelectedIndex(that.cityValue);
				// 设定区初始值
				cityPicker3.pickers[2].setSelectedIndex(that.districtValue);
				var showCityPickerButton = doc.getElementById('showCityPicker3');
				var cityResult3 = doc.getElementById('cityResult3');
				showCityPickerButton.addEventListener('tap', function(event) {
					cityPicker3.show(function(items) {
						that.options.province = _getParam(items[0], 'text');
						that.options.city = _getParam(items[1], 'text');
						that.options.district = _getParam(items[2], 'text');
						showCityPickerButton.value = _getParam(items[0], 'text') + " " + _getParam(items[1], 'text') + " " + _getParam(items[2], 'text');
						//返回 false 可以阻止选择框的关闭
						//return false;
					});
				}, false);
			})(mui, document);
		}
	},
	methods: {
		computeSelectValue: function() {
			var that = this;
			if(that.options.province) {
				for(var i = 0, len = cityData3.length; i < len; i++) {
					if(cityData3[i].text === that.options.province) {
						that.provinceValue = i;
						if(that.options.city) {
							for(var j = 0, jlen = cityData3[i].children.length; j < jlen; j++) {
								if(cityData3[i].children[j].text === that.options.city) {
									that.cityValue = j;
									if(that.options.district) {
										for(var k = 0, klen = cityData3[i].children[j].children.length; k < klen; k++) {
											if(cityData3[i].children[j].children[k].text === that.options.district) {
												that.districtValue = k;
												continue;
											}
										}
									}
									continue;
								}
							}
						}
						break;
					}
				}
			}
		},
		editSubmit: function() {
			var that = this;
			var postUrl = that.isEdit ? 'api/address/' + that.options.id : 'api/address';
			var postData = that.isEdit ? {
				api_token: that.userId,
				"consignee": that.options.consignee,
				"country": that.options.country,
				"province": that.options.province,
				"city": that.options.city,
				"district": that.options.district,
				"address": that.options.address,
				"phone": that.options.phone,
				"is_default": that.options.is_default + '',
				"_method": "put"
			} : {
				api_token: that.userId,
				"consignee": that.options.consignee,
				"country": that.options.country,
				"province": that.options.province,
				"city": that.options.city,
				"district": that.options.district,
				"address": that.options.address,
				"phone": that.options.phone,
				"is_default": that.options.is_default + ''
			};
			//获取默认地址
			that.Axios.post(postUrl, postData).then(function(r) {
				console.log(r);
				mui.toast(that.saveCompelete[that.language]);
				setTimeout(function() {
					mui.back();
				}, 1000);
			}).catch(function(error) {
				console.log(error);
			});
		},
		toggleDefault: function() {
			var that = this;
			that.options.is_default = that.options.is_default ? 0 : 1;
		},
		deleteAddress: function() {
			var that = this;
			if(that.isEdit) {
				var postUrl = 'api/address/' + that.options.id;
				that.Axios.post(postUrl, {
					"_method": "delete"
				}).then(function(r) {
					console.log(r);
					mui.toast(that.delCompelete[that.language]);
					setTimeout(function() {
						mui.back();
					}, 1000);
				}).catch(function(error) {
					console.log(error);
				});
			} else {
				mui.back();
			}
		}
	}
});