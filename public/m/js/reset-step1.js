new Vue({
	el: '#login',
	data: {
		language:localStorage.choose,
		title: ['找回密码','Retrieve Password','パスワードを取り戻す'],
		form: [{
			label: ['已注册手机号','Registered mobile phone number','携帯番号を登録しました。'],
			placeholder:  ['请输入手机号','enter your phone number','携帯電話番号を入力する'],
			value: ''
		}, {
			label: ['验证码','Verification code','コード検証'],
			placeholder: ['请输入验证码','dynamic cipher', '動的暗号'],
			value: ''
		}],
		disabledGet: false,
		canGetcode: true,
		loginButton: ['下一步','next step','次へ'],
		getPassword: ['点击获取','get','あたまにくる'],
		button1: ['返回登陆', 'Return to login', '上陸に戻る'],
		button2: ['立即注册','Reg Now','即座に登録する'],
		toastTips1: ['请稍后再试', 'Please try again later', '後ほど試してください'],
		toastTips2: ['手机号码有误', 'Wrong cell phone number', '電話番号が間違っている'],
		toastTips4: ['请输入验证码', 'Please enter the verification code', '検証コードを入力してください。'],
		getCode: ['获取验证码', 'Get Code', 'あたまにくる'],
//		thirdPartyLogin: '第三方登录',
//		loginButton1: 'QQ',
//		loginButton2: '微信',
//		loginButton3: '支付宝'
	},
	mounted: function() {
		document.getElementById('startLoading').style.display='none';
		mui.init();
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
			var that=this;
			if(this.canGetcode) {
				var that = this;
				that.disabledGet = true;
				this.canGetcode = false;
				this.Axios.get('api/sms/send', {
					mobile: that.form[0].value
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
		linkToLogin: function() {
			location.href = 'login.html';
		},
		linkToNextStep: function() {
			var that = this;
			if(!this.isPoneAvailable(this.form[0].value)) {
				mui.toast(that.toastTips2[that.language]);
				return;
			}
			if(!this.form[1].value) {
				mui.toast(that.toastTips4[that.language]);
				return;
			}
			sessionStorage.setItem('mobile',that.form[0].value);
			sessionStorage.setItem('code',that.form[1].value);
			location.href = 'reset-step2.html';
		},
		linkToRegister: function() {
			mui.openWindow({
				url: 'register.html',
				id: 'register'
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
				s !== '00' ? (el.innerHTML = s + 's') : (el.innerHTML = mui.toast(that.getCode[that.language]), fn());
			}
			timer = setInterval(countDown, 1000);
		}
	}
});