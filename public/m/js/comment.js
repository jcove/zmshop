new Vue({
	el: '#comment',
	data: {
		language: localStorage.choose,
		userId: sessionStorage.user,
		goods:sessionStorage.goods,
		pageTitle: ['发表评价', 'comment', '評価を発表する'],
		submitbutton: ['发布', 'publish', '敷いた'],
		placeholder: ['写点感受吧', 'Feel it', '感想を書きなさい'],
		myComment: '',
		starName: [
			['', '', ''],
			['非常差', 'Very Bad', '非常に悪い'],
			['差', 'Bad', 'ごさ'],
			['一般', 'General', 'いっぱん'],
			['好', 'good', 'ハオ'],
			['非常好', 'very good', 'じょうじょうだ']
		],
		stars: [{
			name: ['快递包装', 'express packaging', '宅配包装'],
			starNum: 0,
			star: [0, 0, 0, 0, 0]
		}, {
			name: ['发货速度', 'delivery speed', '出荷ペース'],
			starNum: 0,
			star: [0, 0, 0, 0, 0]
		}, {
			name: ['卖家服务态度', 'Seller service attitude', '売家サービス'],
			starNum: 0,
			star: [0, 0, 0, 0, 0]
		}],
		Axios: null,
		imagesArr: [],
		imgPathArr: [],
		addImg: ['添加图片', 'add Image', '写真を追加する'],
		tips1: ['温馨提示', 'warm prompt', '暖かいヒント'],
		tips2: ['评价成功', 'Success Evaluation', '評価に成功する'],
		del: ['删除', 'delete', 'ちょうはいする'],
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
			timeout: 5000,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		});
	},
	methods: {
		updateHeadImage: function() {
			var that = this;
			var file = document.getElementById('upload_file').files[0];
			r = new FileReader(); //本地预览;
			r.readAsDataURL(file); //调用readAsDataURL方法来读取选中的图像文件 
			r.onload = function(e) {
				var base64URL = this.result;
				var blob = that.convertBase64UrlToBlob(base64URL, 'image/jpeg');
				var formData = new FormData();
				formData.append('file', blob);
				that.Axios.post('api/file', formData).then(function(r) {
					console.log(r);
					that.imgPathArr.push({
						path: r.data.path
					});
				}).catch(function(r) {
					console.log(r);
				});
			}
		},
		convertBase64UrlToBlob: function(urlData, filetype) {
			//去掉url的头，并转换为byte
			var bytes = window.atob(urlData.split(',')[1]);
			//处理异常,将ascii码小于0的转换为大于0
			var ab = new ArrayBuffer(bytes.length);
			var ia = new Int8Array(ab);
			var i;
			for(i = 0; i < bytes.length; i++) {
				ia[i] = bytes.charCodeAt(i);
			}
			return new Blob([ab], {
				type: filetype
			});
		},
		submitComment: function() {
			var that = this;
			var postData=[];
			that.goods.forEach(function (item) {
				postData.push({
					"goods_id": item.goodsId,
					"content": that.myComment,
					"images": that.imgPathArr,
					"express_rank": this.stars[0].starNum,
					"goods_rank": this.stars[1].starNum,
					"service_rank": this.stars[2].starNum
				});
			});
			this.Axios.post('api/comment/', {
				api_token: that.userId,
				order_id: location.search.split('orderid=')[1],
				comments: postData
			}).then(function(r) {
				console.log(r);
				mui.alert(that.tips1[that.language], that.tips2[that.language], function() {
					that.myComment = '';
					that.stars[0].starNum = 0;
					that.stars[1].starNum = 0;
					that.stars[2].starNum = 0;
					that.stars[0].star = [0, 0, 0, 0, 0];
					that.stars[1].star = [0, 0, 0, 0, 0];
					that.stars[2].star = [0, 0, 0, 0, 0];
					mui.back();
				});
			}).catch(function(error) {
				console.log(error);
			});
		},
		rating: function(id, index) {
			var total = this.stars[id].star.length; //星星总数
			var idx = index + 1; //这代表选的第idx颗星-也代表应该显示的星星数量
			//进入if说明页面为初始状态
			if(this.stars[id].starNum == 0) {
				this.stars[id].starNum = idx;
				for(var i = 0; i < idx; i++) {
					this.stars[id].star[i] = 1;
				}
			} else {
				//如果再次点击当前选中的星级-仅取消掉当前星级，保留之前的。
				if(idx == this.stars[id].starNum) {
					for(var i = index; i < total; i++) {
						this.stars[id].star[i] = 0;
					}
				}
				//如果小于当前最高星级，则直接保留当前星级
				if(idx < this.stars[id].starNum) {
					for(var i = idx; i < this.stars[id].starNum; i++) {
						this.stars[id].star[i] = 0;
					}
				}
				//如果大于当前星级，则直接选到该星级
				if(idx > this.stars[id].starNum) {
					for(var i = 0; i < idx; i++) {
						this.stars[id].star[i] = 1;
					}
				}
				var count = 0; //计数器-统计当前有几颗星
				for(var i = 0; i < total; i++) {
					if(this.stars[id].star[i]) {
						count++;
					}
				}
				this.stars[id].starNum = count;
			}
		}
	}
});