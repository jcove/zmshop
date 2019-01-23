<template>
    <el-form ref="form" :model="form" :rules="rules" label-width="80px" style="margin-top: 30px">
        <el-form-item>
            <el-upload style=""
                       class="avatar-uploader"
                       :action="fileUrl"
                       :headers="headers"
                       :show-file-list="false"
                       :on-success="handleAvatarSuccess"
                       :before-upload="beforeAvatarUpload">
                <img v-if="form.avatar" :src="form.avatar" class="avatar">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>

        </el-form-item>
        <el-form-item :label="$t('user.nick')">
            <el-input v-model="form.nick"></el-input>
        </el-form-item>
        <el-form-item :label="$t('user.gender')">
            <el-select v-model="form.gender" :placeholder="$t('common.please_select')">
                <el-option
                        :key="1"
                        :label="$t('common.man')"
                        :value="1">
                </el-option>
                <el-option
                        :key="2"
                        :label="$t('common.woman')"
                        :value="2">
                </el-option>
            </el-select>
        </el-form-item>
        <el-form-item :label="$t('user.birthday')">
            <el-date-picker
                    v-model="form.birthday"
                    type="date"
                    :placeholder="$t('common.please_select')">
            </el-date-picker>
        </el-form-item>
        <el-form-item :label="$t('user.name')">
            <el-input v-model="form.name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('user.email')" prop="email">
            <el-input v-model="form.email"></el-input>
        </el-form-item>
        <el-form-item :label="$t('user.mobile')">
            <el-input v-model="form.mobile" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="onSubmit">{{$t('common.save')}}</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
    import userApi from '../api/user';
    import config from "../config";

    export default {
        name: "user-base",
        props: {
            user: Object
        },
        data() {
            return {
                form: {
                    nick: '',
                    mobile: '',
                    avatar: '',
                    email: '',
                    gender: '',
                    birthday: '',
                    name: ''
                },
                fileUrl: config.fileApi,
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                rules: {
                    email: [
                        { required: true, message: this.$t('validation.please_input_email'), trigger: 'blur' },
                        { type: 'email', message: this.$t('validation.please_input_right_email'), trigger: ['blur', 'change'] }
                    ]
                }
            }
        },
        created() {
            this.setForm(this.user)
        },
        methods: {
            setForm(form){
              this.form.nick = form.nick;
                this.form.mobile = form.mobile;
                this.form.avatar = form.avatar;
                this.form.email = form.email;
                this.form.gender = form.gender
                this.form.birthday = form.birthday;
                this.form.name = form.name;
            },
            handleAvatarSuccess(res, file) {
                this.form.avatar = URL.createObjectURL(file.raw);
                if (res) {
                    this.form.avatar = config.cdnHost + '/' + res.path
                }
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            },
            onSubmit() {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        const that = this
                        userApi.save(this.form).then(response => {
                            if (response) {
                                that.$message({
                                    message: that.$t('common.save_success'),
                                    type: 'success'
                                });
                              //  location.reload();
                            }
                        })
                    } else {
                        return false;
                    }
                });

            }
        }
    }
</script>

<style>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }

    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 90px;
        height: 90px;
        line-height: 90px;
        text-align: center;
    }

    .avatar {
        width: 90px;
        height: 90px;
        display: block;
    }
</style>