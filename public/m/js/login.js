//登录、注册、找回密码页
if(document.getElementById('login')) {
	new Vue({
		el: '#login',
		data: {
			language:localStorage.choose,
			title: ['登录','Login','ログイン'],
			tab: [{
				title: ['动态登录密码','Dynamic login password','動的ログインパスワード'],
				label1: ['手机号','Phone number','携帯電話番号'],
				label2: ['动态密码','Security code','動的暗号'],
				placeholder1: ['请输入手机号','enter your phone number','携帯電話番号を入力する'],
				placeholder2: ['请输入动态密码','Dynamic Password','動的暗号'],
				phoneNumber: sessionStorage.phoneNumber || '',
				receiveCode: '',
				active: true
			}, {
				title: ['用户密码登录','User password login','パスワード登録'],
				label1: ['手机号','Phone number','携帯電話番号'],
				label2: ['输入密码','Password','パスワード'],
				placeholder1: ['请输入手机号','enter your phone number','携帯電話番号を入力する'],
				placeholder2: ['请输入密码','Please enter password','パスワードを入力する'],
				phoneNumber: sessionStorage.phoneNumber || '',
				receiveCode: '',
				active: false
			}],
			getPassword: ['点击获取','get','あたまにくる'],
			disabledGet: false,
			loginButton:  ['登录','Login','ログイン'],
			button1: ['立即注册','Reg Now','即座に登録する'],
			button2: ['忘记密码？','forget password','パスワードを忘れる'],
//			thirdPartyLogin: '第三方登录',
//			loginButton1: 'QQ',
//			loginButton2: '微信',
//			loginButton3: '支付宝',
			Axios: null,
			canGetcode: true,
			toastTips1:['请稍后再试','Please try again later','後ほど試してください'],
			toastTips2:['手机号码有误','Wrong cell phone number','電話番号が間違っている'],
			toastTips3:['账号或密码错误','Wrong account or password','アカウントや暗証番号が間違っています。'],
			toastTips4:['账号或验证码错误','Error in account or verification code','アカウントまたは検証コードエラー。'],
			getCode:['获取验证码','get code','検証符号を取得する']
			
		},
		mounted: function() {
			mui.init();
			document.getElementById('startLoading').style.display='none';
			var mobile = localStorage.mobile;
			//console.log(mobile);
			this.tab[0].phoneNumber = mobile;
			this.tab[1].phoneNumber = mobile;
			this.tab[0].active = false;
			this.tab[1].active = true;

			this.Axios = axios.create({
				baseURL: 'http://demo2.ruanwe.com/',
				timeout: 5000,
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				}
			});
		},
		methods: {
			getPhoneCode: function(e) { //获取验证码
				var that = this;
				var activeIndex = this.getTabIndex();
				if(this.canGetcode) {
					that.disabledGet = true;
					this.canGetcode = false;
					this.Axios.get('api/sms/send', {
						params: {
							mobile: that.tab[activeIndex].phoneNumber
						}
					}).then(function(r) {
						console.log(r);
					}).catch(function(error) {
						console.log(error);
					});
					this.resetTime(60, e.target, function() {
						that.canGetcode = true;
						that.disabledGet = false;
					});
				} else {
					mui.toast(that.toastTips1[that.language]);
				}
			},
			getTabIndex: function() {
				var activeIndex;
				this.tab.forEach(function(item, index) {
					if(item.active) {
						activeIndex = index
					}
				});
				return activeIndex;
			},
			logIn: function() {
				var that = this;
				var activeIndex = this.getTabIndex();
				if(!this.isPoneAvailable(this.tab[activeIndex].phoneNumber)) {
					mui.toast(that.toastTips2[that.language]);
					return;
				}
				if(activeIndex) {
					this.Axios.post('api/user/login', {
						mobile: that.tab[activeIndex].phoneNumber,
						password: that.tab[activeIndex].receiveCode,
						password_confirmation: that.tab[activeIndex].receiveCode
					}).then(function(r) {
						console.log(r);
						sessionStorage.nike = r.data.nick;
						sessionStorage.user = r.data.api_token;
						sessionStorage.phoneNumber = that.tab[activeIndex].phoneNumber;
						mui.openWindow({
							url: 'main.html',
							id: 'index'
						});
					}).catch(function(error) {
						mui.toast(that.toastTips3[that.language]);
					});
				} else {
					this.Axios.post('api/user/login', {
						mobile: that.tab[activeIndex].phoneNumber,
						code: that.tab[activeIndex].receiveCode
					}).then(function(r) {
						console.log(r);
						sessionStorage.user = r.data.api_token;
						sessionStorage.phoneNumber = that.tab[activeIndex].phoneNumber;
					}).catch(function(error) {
						mui.toast(that.toastTips4[that.language]);
					});
				}
			},
			changeTab: function(index) {
				if(index) {
					this.tab[1].active = true;
					this.tab[0].active = false;
				} else {
					this.tab[0].active = true;
					this.tab[1].active = false;
				}
			},
			linkToRegister: function() {
				mui.openWindow({
					url: 'register.html',
					id: 'register'
				});
			},
			linkToResetStep1: function() {
				mui.openWindow({
					url: 'reset-step1.html',
					id: 'reset-step1'
				});
			},
			isPoneAvailable: function(str) { //手机号码验证
				var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
				if(!myreg.test(str)) {
					return false;
				} else {
					return true;
				}
			},
			resetTime: function(time, el, fn) {
				var that=this;
				el.innerHTML = time + 's'
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
					s !== '00' ? (el.innerHTML = s + 's') : (el.innerHTML = that.getCode[that.language], fn());
				}
				timer = setInterval(countDown, 1000);
			}
		}
	});
	var loginPageSlide = new Swiper('.pageslide', {
		initialSlide: 1,
		allowTouchMove: false,
	});
}