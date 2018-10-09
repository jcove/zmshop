<template>
    <div class="floor-nav" v-if="show">
        <div class="title">

        </div>
        <ul>
            <li v-for="(category,index) in categories" @click="click(category.id)"
                :class="{active:category.id === active}">
                <a href="javascript:void(0)" :title="category.name">
                    {{index+1}}F
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "floor-nav",
        data() {
            return {
                active: '',
                show: false
            }

        },
        props: {
            categories: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        methods: {
            click(index) {
                this.active = index
                var scroll_offset=$("#"+index).offset(); //site为目标位置的ID
                $("html,body").animate({scrollTop:scroll_offset.top},500);
            }
        },
        created() {
            const that = this
            window.addEventListener('scroll', function () {
                let top = document.body.scrollTop || document.documentElement.scrollTop;
                if (top >= 400) {
                    that.show = true
                }else {
                    that.show =false
                }
            });


        }

    }
</script>

<style scoped lang="scss">
    .floor-nav {
        position: fixed;
        width: 50px;
        top: 220px;
        left: 50%;
        margin-left: -630px;
        z-index: 1000;
        ul {
            li {
                float: left;
                background-color: #dddddd;
                width: 30px;
                height: 30px;

                padding: 10px;
                a {
                    color: white;
                    font-size: 24px;
                }
                &.active {
                    background-color: #74b2c6;
                }
            }
        }
    }
</style>