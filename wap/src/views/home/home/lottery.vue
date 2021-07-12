<template>
    <div class="lotto">
        <div class="lotto-content">
            <div :key="index" @click="toLotto(item.name)" class="item" v-for="(item,index) in items">
                <img :src="item.bg" alt />
                <div>
                    <span>{{item.subtitle}}</span>
                    <br />
                    <span class="font-12">
                        有
                        <b class="color-red">{{item.play_now}}</b>人在玩
                    </span>
                </div>
                <van-icon color="#64ABFA" name="star" />
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            list: [],
            stop: false,
            loading: true,
        }
    },

    computed: {
        items() {
            // console.log(Object.values(this.$store.state.config.lotto_items))
            return Object.values(this.$store.state.config.lotto_items)
        },
    },

    methods: {
        toLotto(name) {
            if (name === 'default') {
                return this.$router.push({
                    name: 'lottoComing',
                })
            }
            return this.$router.push({
                name: 'lottoRoom',
                params: {
                    name: name,
                },
            })
        },
    },
}
</script>
<style lang="less" scoped>
.lotto {
    // background: #f3f3f6;
    padding-top: 10px;
    .lotto-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        width: 94%;
        margin: 0 auto;

        // justify-content: center;
        .item {
            width: 48.5%;
            display: flex;
            background: white;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 6px 13px -7px #0205131a;
            position: relative;
            img {
                display: inline-block;
                width: 45px;
                height: 45px;
                padding: 10px;
            }
            div {
                padding-top: 13px;
            }
            /deep/.van-icon {
                position: absolute;
                right: 5%;
                top: 10%;
            }
        }
    }
}
</style>