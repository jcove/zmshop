<template>
    <div class="" v-if="specifications.length > 0">

        <template v-for="item in specifications">
            <div class="specification info-item">
                <span class="name">{{item.specification_name}}:</span>
                <el-radio-group v-model="spec[item.id]" @change="change">
                    <template v-for="value in item.specification_value">
                        <el-radio-button :label="value">{{value}}</el-radio-button>
                    </template>

                </el-radio-group>
            </div>
        </template>
    </div>

</template>

<script>
    export default {
        name: "specification",
        data() {
            return {
                spec: []
            }
        },
        props: {
            specifications: Array
        },
        mounted() {
            if (this.specifications.length > 0) {
                const that = this;
                console.log(this.spec)
                this.specifications.forEach(function (item) {
                    that.$set(that.spec, item.id, '')
                });
                console.log(this.spec)
            }
        },
        methods:{
            change(value){
                console.log(value);
            }
        }
    }
</script>

<style scoped lang="scss">
    .specification {
        padding-left: 50px;
    }

</style>