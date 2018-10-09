Vue.config.debug = true;
Vue.config.devtools = true;
//注册页
new Vue({
	el: '#login',
	data: {
		language:localStorage.choose,
		title: ['注册', 'register', 'きにゅう'],
		form: [{
			label: ['昵称', 'Nickname', 'ぎごう'],
			type: 'text',
			rowClass: 'mui-input-row',
			inputClass: 'mui-input-clear',
			value: ''
		}, {
			label: ['手机号', 'Phone', '携帯電話番号'],
			type: 'number',
			rowClass: 'mui-input-row',
			inputClass: 'mui-input-clear',
			value: ''
		}, {
			label: ['动态密码', 'dynamic cipher', '動的暗号'],
			type: 'text',
			rowClass: 'mui-input-row',
			inputClass: '',
			value: ''
		}, {
			label: ['输入密码', 'Password', 'パスワード'],
			type: 'password',
			rowClass: 'mui-input-row mui-password',
			inputClass: 'mui-input-password',
			value: ''
		}, {
			label: ['确认密码', 'Confirm Pass', 'パスワード'],
			type: 'password',
			rowClass: 'mui-input-row mui-password',
			inputClass: 'mui-input-password',
			value: ''
		}],
		getCode: ['获取验证码', 'Get Code', 'あたまにくる'],
		canGetcode: true,
		button1: ['返回登陆', 'Return to login', '上陸に戻る'],
		button2: ['联系客服', 'Contact Customer Service', '旅客に連絡する'],
		isReadDeal: false,
		loginButton: ['确认', 'Submit', '応答する'],
		agree: ['同意', 'Agree', 'どうい'],
		dealLink: ['《国药用户注册协议》', 'National drug user registration agreement', '[オピニオン]国薬加入者登録協定。'],
		Axios: null,
		toastTips1: ['请稍后再试', 'Please try again later', '後ほど試してください'],
		toastTips2: ['手机号码有误', 'Wrong cell phone number', '電話番号が間違っている'],
		toastTips3: ['请输入昵称', 'Please enter nickname', 'ニックネームをつけてください'],
		toastTips4: ['请输入验证码', 'Please enter the verification code', '検証コードを入力してください。'],
		toastTips5: ['请输入密码', 'Please enter your password', '暗証番号を入力してください'],
		toastTips6: ['请再次输入密码', 'Please enter your password again', 'パスワードを再度入力してください。'],
		toastTips7: ['密码 的最小长度为 6 字符', 'The minimum length of the password is 6 characters', 'パスワードの最小長は6文字。'],
		toastTips8: ['两次输入的密码不一致', 'The two passwords differ', '2回のパスワードが一致しない。'],
		toastTips9: ['请阅读并同意注册协议', 'Please read and agree to the registration agreement', '登録プロトコルを読んで承認して下さい。'],
		toastTips10: ['loading', 'loading', '走り回る']
	},
	mounted: function() {
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
	},
	methods: {
		reg: function() { //注册验证
			var that = this;
			if(!this.form[0].value) {
				mui.toast(that.toastTips3[that.language]);
				return;
			}
			if(!this.isPoneAvailable(this.form[1].value)) {
				mui.toast(that.toastTips2[that.language]);
				return;
			}
			if(!this.form[2].value) {
				mui.toast(that.toastTips4[that.language]);
				return;
			}
			if(!this.form[3].value) {
				mui.toast(that.toastTips5[that.language]);
				return;
			}
			if(!this.form[4].value) {
				mui.toast(that.toastTips6[that.language]);
				return;
			}
			if(this.form[3].value.length < 6) {
				mui.toast(that.toastTips7[that.language]);
				return;
			}
			if(!(this.form[4].value === this.form[3].value)) {
				mui.toast(that.toastTips8[that.language]);
				return;
			}
			if(!this.isReadDeal) {
				mui.toast(that.toastTips9[that.language]);
				return;
			}
			this.Axios.post('api/user/register', {
				nick: this.form[0].value,
				mobile: this.form[1].value,
				code: this.form[2].value,
				password: this.form[3].value,
				password_confirmation: this.form[4].value
			}).then(function(r) {
				//console.log(r);
				that.loginButton = that.toastTips10[that.language];
				setTimeout(function() {
					mui.openWindow({
						url: 'login.html',
						id: 'login'
					});
					sessionStorage.setItem('mobile', that.form[1].value);
				}, 1000);
			}).catch(function(error) {
				console.log(error);
			});
		},
		getPhoneCode: function(e) { //获取验证码
			if(this.canGetcode) {
				var that = this;
				this.canGetcode = false;
				this.Axios.get('api/sms/send', {
					params: {
						mobile: that.form[1].value
					}
				}).then(function(r) {
					console.log(r);
				}).catch(function(error) {
					console.log(error);
				});
				this.resetTime(60, e.target, function() {
					that.canGetcode = true;
				});
			} else {
				mui.toast(that.toastTips1[that.language]);
			}
		},
		linkToLogin: function() { //跳转登录页
			location.href = 'login.html';
		},
		linkToQQ: function() { //跳转客服

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
				s !== '00' ? (el.innerHTML = s + 's') : (el.innerHTML =that.getCode[that.language], fn());
			}
			timer = setInterval(countDown, 1000);
		}
	}
});