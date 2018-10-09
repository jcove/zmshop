//重置密码页
new Vue({
	el: '#login',
	data: {
		language:localStorage.choose,
		title: ['找回密码','Retrieve Password','パスワードを取り戻す'],
		form: [{
			label: ['新密码','new password','新しいパスワード'],
			placeholder: ['请输入新密码','new password','新しいパスワード'],
			value: ''
		}, {
			label: ['再次输入密码','Confirm Pass', 'パスワード'],
			placeholder: ['请再次输入密码','Confirm Pass', 'パスワード'],
			value: ''
		}],
		loginButton: ['确认', 'Submit', '応答する'],
		toastTips1:['请输入新密码','new password','新しいパスワード'],
		toastTips8: ['两次输入的密码不一致', 'The two passwords differ', '2回のパスワードが一致しない。']
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
		resetPassword: function() {
			var that=this;
			if(!this.form[0].value) {
				mui.toast(that.toastTips1[language]);
				return;
			}
			if(this.form[0].value!==this.form[1].value) {
				mui.toast(that.toastTips8[language]);
				return;
			}
			this.Axios.post('api/password/reset', {
				mobile: sessionStorage.mobile,
				code: sessionStorage.code,
				password: this.form[0].value,
				password_confirmation: this.form[1].value
			}).then(function(r) {
				console.log(r)
			}).catch(function(error) {
				//mui.toast('服务器繁忙，请稍后再试！');
			});
		}
	}
});