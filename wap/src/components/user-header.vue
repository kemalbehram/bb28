<template>
    <div :class="fixed?'fixed':''" class="header">
        <van-icon @click="toBack()" color="white" name="arrow-left" size="22px" />
        <h2 v-if="title">{{title}}</h2>
        <span class="slot">
            <slot></slot>
        </span>
    </div>
</template>
<script>
export default {
    props: {
        icon: {
            default: 'play-circle',
            type: String,
        },
        title: {
            default: '',
            type: String,
        },
        close: {
            default: false,
            type: Boolean,
        },
        fixed: {
            default: false,
            type: Boolean,
        },
    },

    methods: {
        toBack() {
            if (this.close) {
                this.$store.state.config.show_pop = false
                return
            }
            this.$router.back(-1)
        },
    },
}
</script>
<style lang="less" scoped>
.header {
    // width: 100%;
    height: 6vh;
    // background: linear-gradient(90deg, #4f8dfe, #34bcfe);
    background: #ff6a00;
    text-align: center;
    position: relative;
    color: @black;
    z-index: 20;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0px 15px;
    /deep/.van-icon {
        // display: inline-block;

        // position: absolute;
        // top: 26%;
        // left: 3%;
    }
    h2 {
        // display: inline-block;

        color: @white;
    }
    .slot {
        // display: inline-block;
        // position: absolute;
        // right: 3%;
        * {
            font-size: 13px;

            color: @white;
        }
    }
    // border-bottom: 1px solid #ebedf0;
}
.fixed {
    position: fixed;
    top: 0;
}
</style>