<template>
    <div class="list">
        <div class="edit">
            <el-form ref="form" :model="form" label-width="80px" size="mini">

                <el-form-item :label="$t('user.region')">
                    <el-select v-model="form.country" placeholder="" style="width: 100px" @change="countryChange">
                        <el-option :label="country.name" :value="country.name" :key="country.id" v-for="country in countries">

                        </el-option>
                    </el-select>
                    <el-select v-model="form.province" placeholder="" style="width:100px" :style="{display:regionShow}"
                               @change="provinceChange">
                        <template v-for="item in province">
                            <el-option :label="item.name" :value="item.name"></el-option>
                        </template>

                    </el-select>
                    <el-select v-model="form.city" placeholder="" style="width: 160px" :style="{display:regionShow}"
                               @change="cityChange">
                        <template v-for="item in city">
                            <el-option :label="item.name" :value="item.name"></el-option>
                        </template>

                    </el-select>
                    <el-select v-model="form.district" placeholder="" style="width: 160px"
                               :style="{display:regionShow}">
                        <template v-for="item in district">
                            <el-option :label="item.name" :value="item.name"></el-option>
                        </template>
                    </el-select>

                </el-form-item>
                <el-form-item :label="$t('user.address')">
                    <el-input v-model="form.address" style="width: 360px"></el-input>
                </el-form-item>
                <el-form-item :label="$t('user.consignee')">
                    <el-input v-model="form.consignee" style="width: 160px"></el-input>
                </el-form-item>
                <el-form-item :label="$t('user.phone')">
                    <el-input v-model="form.phone" style="width: 160px"></el-input>
                </el-form-item>

                <el-form-item size="large" label="" label-width="160px">
                    <el-button type="primary" @click="onSubmit">{{$t('common.save')}}</el-button>
                </el-form-item>
            </el-form>
        </div>

        <el-table
                :data="addresses"
                border
                style="width: 100%">
            <el-table-column
                    prop="consignee"
                    :label="$t('user.consignee')"
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="region"
                    :label="$t('user.region')"
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="address"
                    :label="$t('user.address')">
            </el-table-column>
            <el-table-column
                    prop="phone"
                    :label="$t('user.phone')">
            </el-table-column>
            <el-table-column
                    fixed="right"
                    :label="$t('common.operate')"
                    width="100">
                <template slot-scope="scope">
                    <el-button @click="handleEdit(scope.$index,scope.row)" type="text" size="small">{{$t('operate.edit')}}</el-button>
                    <el-button @click="handleDelete(scope.$index,scope.row)" type="text" size="small">{{$t('operate.delete')}}</el-button>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    import addressApi from '../api/address';
    import regionApi from '../api/region';
    import countryApi from "../api/country";

    export default {
        name: "user-address",
        props: {
            list: {
                type: Array,
                default: []
            }
        },
        data() {
            return {
                addresses: [],
                form: {
                    consignee: '',
                    country: '',
                    province: '',
                    city: '',
                    district: '',
                    address: '',
                    phone: '',
                    is_default: 1
                },
                countries:[],
                index:-1,
                regionShow: 'display',
                province: [],
                city: [],
                district: []
            }
        },

        created() {
            this.addresses = this.list;
            const that = this;

            this.getCountry();
            this.addresses.forEach(function (item) {
                var index = that.addresses.indexOf(item);
                item.region = item.country+item.province+item.city+item.district;
                that.$set(that.addresses,index,item)
            });
        },
        methods: {
            onSubmit() {
                const that = this;
                addressApi.save(this.form).then(response => {
                    if(response){
                        this.$message({
                            message: that.$t('common.success'),
                            type: 'success'
                        });
                        that.initForm();
                        var address = response.data;
                        address.region = address.country+address.province+address.city+address.district;

                        if(this.index >= 0){
                            that.$set(that.addresses,that.index,address);
                            that.index = 0;
                        }else {

                            that.addresses.push(address)

                        }
                    }
                })
            },
            getCountry(){
                countryApi.list( {all:1}).then(response => {
                    this.countries = response.data
                })
            },
            countryChange(value) {
                if (value === this.countries[0].name) {
                    this.regionShow= 'inline-block';
                    const that = this;
                    regionApi.children(0).then(response => {
                        that.province = response.data;
                    });
                }else {
                    this.regionShow= 'none';
                }
            },
            provinceChange(value) {
                const that = this;
                regionApi.children(value).then(response => {
                    that.city = response.data;
                });

            },
            cityChange(value) {
                const that = this;
                regionApi.children(value).then(response => {
                    that.district = response.data;
                });

            },
            setForm(form){
                const that = this
                if (form != null) {
                    const keys = Object.keys(form);
                    keys.forEach(function (key) {
                        that.form[key] = form[key];
                    })
                    if (form.country === this.$t('user.china')) {
                        const that = this;
                        regionApi.children(0).then(response => {
                            that.province = response.data;
                        });
                        that.regionShow = 'inline-block'
                    }else {
                        this.regionShow= 'none';
                    }
                }else {
                    this.initForm();
                }

            },
            initForm(){
                this.form=  {
                    consignee: '',
                    country: '',
                    province: '',
                    city: '',
                    district: '',
                    address: '',
                    phone: '',
                    is_default: 1
                }
            },
            handleEdit(index,item){
                this.setForm(item);
                this.index = index;
            },
            handleDelete(index,item){
                const that= this;
                addressApi.delete(item.id).then(response=>{
                   that.addresses.splice(index,1);
                   console.log(that.addresses);
                    this.$message({
                        message: that.$t('common.success'),
                        type: 'success'
                    });
                });
            }
        }

    }
</script>

<style scoped lang="scss">
    .list {
        padding: 20px;
        .edit {
            border: 1px solid #a0d3ff;
            padding-top: 15px;
        }
    }
</style>