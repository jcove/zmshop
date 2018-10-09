new Vue({
	el: '#personalCenter',
	data: {
		language: localStorage.choose,
		user_id: '',
		userId: sessionStorage.user,
		pageTitle: ['个人中心', 'personal center', '個人の中心'],
		head: {
			label: ['头像', 'ChatHead', '变身ミ偶像'],
			value: ''
		},
		nike: {
			label: ['昵称', 'nickname', 'ぎごう'],
			value: sessionStorage.nike,
			placeHolder: ['请输入昵称', 'Please enter nickname', 'ニックネームをつけてください'],
		},
		/*name: {
			label: '真实姓名',
			value: '',
			noSetValue: '未设置',
			placeHolder: '请输入真实姓名'
		},*/
		phoneNumber: {
			label: ['手机号码', 'Phone', '携帯電話番号'],
			value: sessionStorage.phoneNumber,
			noSetValue: ['未设置', 'Not Set', '未設定'],
			placeHolder: ['请输入手机号', 'enter your phone number', '携帯電話番号を入力する'],
		},
		password: {
			label: ['密码', 'password', 'パスワード'],
			oldvalue: '',
			value: '',
			value2: '',
			oldplaceHolder: ['请输入原密码', 'UNIX password', 'パスワードを入力する'],
			placeHolder: ['请输入新密码', 'New Password', '新しいパスワード'],
			placeHolder2: ['请再次输入新密码', 'Retype New Password', '新しいパスワードを再入力します'],
			reset: ['修改密码', 'confirm', '落し着ける']
		},
		set: ['设置', 'set', '仕懸ける'],
		reset: ['重置', 'reset', 'リセット'],
		save: {
			text: ['保存', 'save', '保存する'],
			disable: false
		},
		Axios: null,
		loginout: ['退出', 'Quit', 'おもいたつ']
	},
	mounted: function() {
		var that = this;
		document.getElementById('startLoading').style.display = 'none';
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
				that.password.oldvalue = '';
				that.password.value = '';
				that.password.value2 = '';
			});
			view.addEventListener('pageShow', function(e) {
				//				console.log(e.detail.page.id + ' show');
			});
			view.addEventListener('pageBeforeBack', function(e) {
				//				console.log(e.detail.page.id + ' beforeBack');
			});
			view.addEventListener('pageBack', function(e) {
				//				console.log(e.detail.page.id + ' back');
				that.initValue();
			});
		})(mui);
		this.initValue();
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
					that.postHeaderimage(r.data.path);
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
		updateNike: function() {
			var that = this;
			this.save.disable = true;
			setTimeout(function() {
				that.save.disable = false;
				that.Axios.post('api/user/' + that.userId, {
					api_token: that.userId,
					nick: that.nike.value,
					_method: 'put'
				}).then(function(r) {
					console.log(r);
					mui.back();
				}).catch(function(r) {
					console.log(r);
				});
			}, 1000);
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
					that.Axios.post('api/password/modify', {
						api_token: sessionStorage.user,
						"old_password": that.password.oldvalue,
						"password": that.password.value,
						"password_confirmation": that.password.value2
					}).then(function(r) {
						console.log(r);
						mui.back();
					}).catch(function(error) {
						console.log(error);
					});
				}, 1000);
			} else {
				mui.alert('您2次输入的密码不一致！');
			}
		},
		postHeaderimage: function(path) {
			var that=this;
			that.Axios.post('api/user/'+that.user_id, {
				api_token: sessionStorage.user,
				'nick':that.nike.value,
				'avatar': path,
				"_method": 'put'
			}).then(function(r) {
				console.log(r);
				that.initValue();
			}).catch(function(error) {
				console.log(error);
			});
		},
		initValue: function() {
			var that = this;
			this.Axios.get('api/user/my', {
				params: {
					api_token: sessionStorage.user
				}
			}).then(function(r) {
				console.log(r);
				that.user_id = r.data.data.id;
				that.head.value = r.data.data.avatar;
				that.nike.value = r.data.data.nick;
				that.phoneNumber.value = r.data.data.mobile;
			}).catch(function(error) {
				console.log(error);
			});
		},
		loginOut: function() {
			mui.openWindow({
				url: 'login.html'
			});
		}
	}
});