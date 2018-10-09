<template>
    <div class="category-children" v-loading="categoryLoading">
        <ul>
            <template v-if="categories">
                <template  v-for="(item ,index) in categories">
                    <li v-if="index < 10">

                        <a :href="getCategoryRoute(item.id)">
                            {{item.name}}
                        </a>

                    </li>
                </template>
            </template>

            <template v-if="brands">
                <template v-for="(item ,index) in brands">
                    <li v-if="index < 10" >
                        <a :href="'/brand/'+item.id">
                            <img :src="item.logo">
                        </a>
                    </li>
                </template>
            </template>


        </ul>
    </div>
</template>

<script>
    import goodsCategoryApi from "../api/goodsCategory";
    import brandApi from "../api/brand";
    import config from "../config";

    export default {
        name: "brand-category",
        data(){
            return {
                categories:[],
                categoryLoading:false,
                brands:[]
            }
        },
        props:{
            brand:Object
        },
        created(){
          this.getBrand();
          this.getCategory();

        },
        methods:{
            getCategory(){
                const that                      =   this;
                this.categoryLoading            =   true;
                goodsCategoryApi.brand(this.brand.id).then(response => {
                   if(response){
                       that.categories          =   response;
                   }
                   that.categoryLoading         =   false;
                });
            },
            getBrand(){
                if(this.brand){
                    const that = this;
                    brandApi.list({category_id:this.brand.category_id}).then(response=>{
                        that.brands              =   response.data;
                    });
                }
            },
            getCategoryRoute(id){
                return config.baseApi+'/goods?category_id='+id;
            }
        }
    }
</script>

<style scoped lang="scss">
    .category-children{
        height: 410px;
        float: left;
        width:200px;
        ul{
            width: 200px;
            overflow: hidden;
            border:1px solid #e8e8e8;
            li{
                width: 99px;
                float: left;
                border-bottom:1px solid #e8e8e8;
                border-right:1px solid #e8e8e8;
                text-align:center;
                line-height: 40px;
                font-size: 14px;
                font-weight: 600;
                &:nth-child(even){
                    border-right: 0;
                }
                img{
                    height: 40px;
                    width: 90px;
                    display: block;
                    margin: auto;
                }
            }
        }
    }


</style>