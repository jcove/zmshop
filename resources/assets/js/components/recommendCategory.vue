<template>
    <div class="recommend-category-bar container">
        <floor-nav :categories="categories">

        </floor-nav>
        <div class="category" v-loading="cateLoading">
            <ul>
                <template v-for="(item,index) in list">
                    <template v-if="index < 6">
                        <li @mouseenter="mouseOver(index)" v-bind:class="{ active:item.active }">
                            <p>{{item.name}}{{$t('index.category_box.recommend')}}</p>
                        </li>
                    </template>


                </template>

            </ul>
        </div>
        <div class="category-goods">
            <ul v-loading="goodsLoading">
                <template v-for="(item,index) in goods">
                    <li v-if="index < 6">
                        <a :href="'goods/'+item.id" target="_blank">
                            <div class="cover">
                                <img class="img-responsive" :src="item.cover"/>
                            </div>
                            <p class="name">{{item.name}}</p>
                            <p class="price">{{$t('goods.$')}}{{item.price}}/{{item.unit}}</p>
                        </a>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>

<script>
    import goodsCategoryApi from '../api/goodsCategory';
    import goodsApi from '../api/goods';

    export default {
        name: "recommend-category",
        data() {
            return {
                list: [
                    {name: '推荐分类', active: true}
                ],
                goods: [],
                goodsGroup: [],
                cateLoading: true,
                goodsLoading: true
            }
        },
        props: {
            categories: {
                type: Array,
                default(){
                    return []
                }
            }
        },
        created() {
            this.getRecommendCategory();
        },
        methods: {
            getRecommendCategory() {
                const that = this;
                that.cateLoading = true;
                goodsCategoryApi.list({
                    is_show: 1,
                    is_recommend: 1
                }).then(function (response) {
                    that.list = response.data;
                    for (var i = 0; i < that.list.length; i++) {
                        const item = that.list[i];
                        if (i === 0) {
                            item.active = true;
                            that.goodsLoading = false;
                            goodsApi.list({
                                category_id: that.list[i].id
                            }).then(response => {
                                that.goods = response.data
                                that.goodsLoading = false;
                                that.goodsGroup[i] = that.goods;
                            });

                        } else {
                            item.active = false;
                        }
                        that.$set(that.list, i, item);
                        that.cateLoading = false;
                    }

                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            mouseOver(index) {
                for (var i = 0; i < this.list.length; i++) {
                    const item = this.list[i];
                    item.active = false;
                    this.$set(this.list, i, item)
                }
                this.$set(this.list[index], 'active', true);
                if (this.goodsGroup[index]) {
                    this.goods = this.goodsGroup[index]
                } else {
                    if (!this.goodsLoading) {
                        const that = this;
                        that.goodsLoading = true;
                        goodsApi.list({
                            category_id: that.list[index].id
                        }).then(response => {
                            that.goods = response.data;
                            that.goodsGroup[index] = that.goods;
                            that.goodsLoading = false;
                        });
                    }

                }

            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../sass/variables";
    .recommend-category-bar{
        position: relative;
        .category {
            overflow: hidden;
            li {
                float: left;
                font-size: 18px;
                border-bottom: 5px solid $border-default;
                padding: 20px 0;
                text-align: center;
                cursor: pointer;
                width: 190px;
                &.active {
                    border-bottom: 5px solid #ff8629;
                    position: relative;
                    &:before {
                        content: '';
                        width: 0;
                        height: 0;
                        border-width: 0 10px 10px;
                        border-style: solid;
                        border-color: transparent transparent #ff8629;
                        position: absolute;
                        bottom: 0;
                    }
                }
                p {
                    margin: 0;
                    text-align: center;
                }
            }
        }

        .category-goods {
            overflow: hidden;
            border-style: solid;
            border-width: 1px;
            border-top: 0;
            border-color: #e8e8e8;
            width: 1134px;
            height: 218px;
            ul {
                height: 206px;
                li {
                    width: 189px;
                    float: left;
                    .cover {
                        img {
                            height: 140px;
                            width: 140px;
                            display: block;
                            margin-left: auto;
                            transition: all 0.4s ease-out 0s;
                            -ms-transition: all 0.4s ease-out 0s;
                            -webkit-transition: all 0.4s ease-out 0s;

                        }
                    }
                    p {
                        text-align: center;
                        font-size: 16px;
                    }
                    .price {
                        color: #ff7c07;
                        line-height: 40px;
                    }
                    .name {
                        overflow: hidden;
                        font-size: 12px;
                        height: 34px;
                        padding: 0 5px;
                    }
                    :hover{
                        .cover{
                            img{
                                transform:scale(1.03);
                                -ms-transform:scale(1.03);
                                -webkit-transform:scale(1.03);
                            }
                        }
                    }
                }

            }
        }
    }
</style>