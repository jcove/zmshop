<template>
    <div class="container">
        <div class="step-box">
            <el-steps :active="active">
                <el-step title=""></el-step>
                <el-step title=""></el-step>
                <el-step title=""></el-step>
            </el-steps>
        </div>

        <el-form label-width="180px" :model="form" ref="form" :style="{display:formDisplay}">
            <el-form-item label="手机号" label-width="120px" :rules="[
      { required: true, message: '请输入手机号', trigger: 'blur' },
      { type: 'tel', message: '请输入正确的手机号', trigger: ['blur', 'change'] }
    ]">
                <el-input v-model="form.mobile" style="width:220px " type="tel"
                          :disabled="fields.mobile.disabled"></el-input>
            </el-form-item>
            <el-form-item label="验证码" label-width="120px" :style="{display:fields.captcha.display}">
                <el-input v-model="form.captcha" style="width:110px;float:left" @blur="captchaBlur"></el-input>
                <div class="captch" style="float:left">
                    <img class="thumbnail captcha" src="/captcha/flat" onclick="this.src='/captcha/flat?'+Math.random()"
                         style="width:110px;height:40px">
                </div>

            </el-form-item>
            <el-form-item label="短信验证码" label-width="120px" :style="{display:fields.smsCode.display}">
                <el-input v-model="form.sms_code" style="width:220px "></el-input>
            </el-form-item>
            <el-form-item :label="$t('user.nick')" label-width="120px" :style="{display:fields.nick.display}">
                <el-input v-model="form.nick" style="width:220px "></el-input>
            </el-form-item>

            <el-form-item :label="$t('user.password')" label-width="120px" :style="{display:fields.password.display}">
                <el-input v-model="form.password" style="width:220px " type="password"></el-input>
            </el-form-item>
            <el-form-item :label="$t('user.password_confirmation')" label-width="120px"
                          :style="{display:fields.confirmPassword.display}">
                <el-input v-model="form.password_confirmation" style="width:220px " type="password"></el-input>
            </el-form-item>

            <el-form-item label="" label-width="120px" :style="{display:fields.agreement.display}">
                <el-checkbox :label="$t('user.read_and_agree')" v-model="form.agree"></el-checkbox>
                {{$t('user.user_register_agreement')}}
            </el-form-item>
            <el-form-item>
                <el-input v-model="form._token" type="hidden"></el-input>
                <el-button type="primary" style="width: 120px" @click="verifyCode"
                           :style="{display:fields.verifyCodeButton.display}">{{$t('common.confirm')}}
                </el-button>
                <el-button type="primary" style="width: 120px" @click="submit"
                           :style="{display:fields.submitButton.display}">{{$t('common.confirm')}}
                </el-button>
            </el-form-item>
        </el-form>
        <div class="registered" :style="{display: registeredDisplay}">

            <p><i class="iconfont icon-right1"></i><i>{{$t('user.registered')}}</i></p>
            <p>
                <el-button type="primary" style="width: 120px;margin: auto" @click="finish"
                           :style="{display:fields.finishButton.display}">{{$t('common.confirm')}}
                </el-button>
            </p>
        </div>
    </div>
</template>

<script>

    import captchaApi from '../api/captcha';
    import verifyCodeApi from '../api/verifyCode';
    import userApi from '../api/user';

    export default {
        name: "register-form",
        data() {
            const that = this;
            return {
                active: 1,
                formDisplay: 'block',
                registeredDisplay: 'none',
                form: {
                    mobile: '',
                    captcha: '',
                    sms_code: '',
                    agree: false,
                    nick: '',
                    password: '',
                    password_confirmation: '',
                    _token: that.t
                },
                fields: {
                    mobile: {
                        disabled: false,
                        display: true,
                    },
                    captcha: {
                        disabled: false,
                        display: true,
                    },
                    smsCode: {
                        disabled: false,
                        display: true
                    },
                    nick: {
                        disabled: false,
                        display: 'none'
                    },
                    password: {
                        disabled: false,
                        display: 'none'
                    },
                    confirmPassword: {
                        disabled: false,
                        display: 'none'
                    },
                    verifyCodeButton: {
                        disabled: false,
                        display: false
                    },
                    submitButton: {
                        disabled: false,
                        display: 'none'
                    },
                    finishButton: {
                        disabled: false,
                        display: 'none'
                    },
                    agreement: {
                        disabled: false,
                        display: 'block'
                    }
                },
                requestting: false
            }
        },
        props: {
            t: '',
            redirect_url: ''
        },
        methods: {
            captchaBlur(event) {
                const value = event.target.value;
                if (event.target.value.length !== 6) {
                    this.$message.error('验证码长度为6位');
                }
                if (event.target.value.length >= 6) {

                    const that = this;
                    if (!this.requestting) {
                        this.requestting = true;
                        captchaApi.verify(value).then(response => {
                                that.requestting = false;
                                that.sendSms();
                            },
                            error => {
                                that.requestting = false;
                            })
                    }

                }
            },
            verifyCode() {
                if (this.form.mobile !== null && this.form.mobile.length > 0) {
                    if (this.form.agree) {
                        const that = this;
                        verifyCodeApi.verify(this.form.mobile, this.form.sms_code).then(response => {
                            that.fields.captcha.display = 'none';
                            that.fields.mobile.disabled = true;
                            that.fields.smsCode.display = 'none';
                            that.fields.agreement.display='none';
                            that.fields.nick.display = 'block';
                            that.fields.password.display = 'block';
                            that.fields.confirmPassword.display = 'block';
                            that.fields.submitButton.display = 'block';
                            that.fields.verifyCodeButton.display = 'none';
                            that.active = 2;

                        });
                    } else {
                        this.$message.error(this.$t('user.must_agree_agreement'));
                    }

                } else {
                    this.$message.error(this.$t('user.mobile_error'));
                }
            },

            sendSms() {
                const that = this;
                verifyCodeApi.send(this.form.mobile).then(response => {
                    this.$message({
                        message: that.$t('common.sms_send_success'),
                        type: 'success'
                    });
                    that.active = 2;
                })
            },
            submit() {
                const that = this;
                userApi.register(this.form).then(response => {
                    if(response){
                        that.$message({
                            message: that.$t('user.registered'),
                            type: 'success'
                        });
                        that.active = 3;
                        that.fields.finishButton.display = 'block';
                        that.fields.submitButton.display = 'none';
                        that.registeredDisplay = 'block';
                        that.formDisplay = 'none'
                    }

                })
            },
            finish() {
                if (this.redirect_url && this.redirect_url !== '') {
                    location.href = this.redirect_url;
                }else {
                 location.href = '/';
                }

            }
        }
    }
</script>

<style scoped lang="scss">
    .step-box {
        height: 60px;
        margin-top: 50px;
    }
    form{
        min-height: 600px;
        width: 500px;
        padding-top: 30px;
        margin: auto;
    }

    .registered {
        padding-top: 200px;
        min-height: 400px;
        .iconfont {
            color: #35a0fc;
            font-size: 32px;
        }
        p {
            text-align: center;
            i {
                font-style: normal;
                padding: 10px;
            }
            padding: 20px;
        }
    }

    .el-step__icon-inner {
        font-size: 32px;
    }

    .el-step__icon {
        width: 60px;
        height: 60px;
    }

    .el-step.is-horizontal .el-step__line {
        top: 30px;
    }

    .el-step__head.is-wait {
        color: #c0c4cc;
        border-color: #c0c4cc;
    }

    .el-step__head.is-process {
        color: #2b8fff;
        border-color: #2b8fff;
    }
</style>