<template>
    <div class="nav-bar">
        <div class="container">
            <ul>
                <li class="nav-item" @mouseenter="enter()" @mouseleave="leave()">
                    <a>
                        <i class="iconfont icon-menu"></i>
                        <span>
                            {{$t('common.all_goods')}}
                        </span>
                    </a>
                </li>
                <li class="nav-item" v-for="item in navs">
                    <a :href="item.url" target="_blank">{{item.name}}</a>
                </li>
            </ul>
            <nav-category-tree :data="categories" :show="show"></nav-category-tree>
        </div>

    </div>
</template>

<script>
    export default {
        name: "nav-bar",
        data() {
            return {
                navs: [],
                show: false
            }
        },
        props: {
            categories: {
                type: Array,
                default: []
            },
            data: {
                type: Array,
                default: []
            },
            showCategory: false
        },
        created() {
            this.show = this.showCategory;
            this.navs = this.data;
        },
        methods: {
            enter() {
                this.show = true
            },
            leave() {
                this.show = false
                console.log('ll')
            }
        }
    }
</script>

<style scoped>

</style>