<template>

    <el-tabs v-model="activeName">
        <el-tab-pane key="1"
                :label="$t('user.login_password')"
                name="password">
            <div class="login-form">
                <el-form>
                    <el-form-item  label-width="0px" :rules="[
                  { required: true, message: '请输入手机号', trigger: 'blur' },
                  { type: 'tel', message: '请输入正确的手机号', trigger: ['blur', 'change'] }
                ]">
                        <el-input v-model="form.mobile" style="width:300px" :placeholder="$t('user.mobile')" type="tel"    @keyup.enter.native="login">
                            <i slot="prefix" class="iconfont icon-seeuser"></i>
                        </el-input>
                    </el-form-item>
                    <el-form-item  label-width="0px" >
                        <el-input v-model="form.password" style="width:300px" :placeholder="$t('user.password')" type="password" @keyup.enter.native="login">
                            <i slot="prefix" class="iconfont icon-lock"></i>
                        </el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button style="width:300px" @click="login" type="primary">
                         {{$t('user.login')}}
                        </el-button>
                    </el-form-item>
                </el-form>
                <div class="tools">
                    <a :href="registerUrl" class="register float-left">{{$t('user.register')}}</a>
                    <a :href="forgetPasswordUrl" class="forget-password float-right">{{$t('user.forget_password')}}</a>
                </div>
                <div class="third-party">

                </div>

            </div>
        </el-tab-pane>
        <el-tab-pane key="2"
                     :label="$t('user.login_sms_code')"
                     name="smsCode">
            <div class="login-form">
                <el-form>
                    <el-form-item label="" label-width="0px" :rules="[
                  { required: true, message: '请输入手机号', trigger: 'blur' },
                  { type: 'tel', message: '请输入正确的手机号', trigger: ['blur', 'change'] }
                ]">
                        <el-input v-model="form.mobile" style="width:300px " type="tel"  :placeholder="$t('user.mobile')" @keyup.enter.native="login">
                            <i slot="prefix" class="iconfont icon-seeuser"></i>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="" label-width="0px">
                        <el-input v-model="form.code" style="width:150px;float:left" :placeholder="$t('user.sms_code')" @keyup.enter.native="login">
                            <i slot="prefix" class="iconfont icon-lock"></i>
                        </el-input>
                        <el-button type="success" :disabled="sendCodeButtonDisabled" @click="sendSms" style="width: 150px;">{{sendCodeLabel}}</el-button>

                    </el-form-item>
                    <el-form-item>
                        <el-button style="width:300px" @click="login" type="primary">
                            {{$t('user.login')}}
                        </el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-tab-pane>
    </el-tabs>

</template>

<script>
    import verifyCodeApi from '../api/verifyCode';
    import userApi from '../api/user';
    import config from "../config";

    export default {
        name: "login-form",
        data(){
            const that = this;
            return {
                form:{
                    mobile:'',
                    password:'',
                    code:''
                },
                sendCodeButtonDisabled:false,
                sendCodeLabel:that.$t('user.get_sms_code'),
                activeName:'password',
                registerUrl:config.baseApi+'/user/register',
                forgetPasswordUrl:config.baseApi+'/password/forget'
            }
        },
        props:{
            redirect_url:''
        },
        methods:{
            sendSms() {
                const that = this;
                verifyCodeApi.send(this.form.mobile).then(response => {
                    this.$message({
                        message: that.$t('common.sms_send_success'),
                        type: 'success'
                    });
                    that.sendCodeButtonDisabled = true;
                    var time = 60;
                    const timer= setInterval(function () {
                        time--;
                        that.sendCodeLabel = '('+time+')';
                        if(time===0){
                            clearInterval(timer);
                            that.sendCodeLabel = that.$t('user.get_sms_code');
                            that.sendCodeButtonDisabled = false;
                        }
                    },1000)
                })
            },
            login(){
                const that = this;
                userApi.login(this.form).then(response => {
                    if(response){

                        if (this.redirect_url && this.redirect_url !== '') {
                            location.href = this.redirect_url;
                        }else {
                            location.href = '/';
                        }
                    }
                })
            }
        }
    }
</script>

<style  lang="scss">
    .user-login{
        .el-tabs{
            width:400px;
            background-color:white;
            margin-top: 130px;
            .el-tabs__item {
                padding: 0!important;
                width: 200px;
                text-align: center;
            }
        }

        .login-form{
            width: 300px;

            padding:40px 50px;
        }
        .register,.forget-password{
            color: #028dd6;
        }
    }

</style>