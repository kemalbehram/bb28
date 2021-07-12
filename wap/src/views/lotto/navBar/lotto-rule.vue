<template>
    <van-popup @close="$parent.$refs.action.show = false" class="nav-bar-action" position="bottom" v-model="show">
        <div class="article-content">
            <h5>游戏/返水规则</h5>
            <div class="article-content--html" v-html="getRule"></div>
        </div>
    </van-popup>
</template>

<script>
export default {
    props: {
        data: Object,
    },
    data() {
        return {
            show: false,
        }
    },
    computed: {
        getRule() {
            const room = 'room' + this.$route.params.room_id
            return this.$store.state.config.play_types[room].rules
        },
    },
}
</script>


<style lang="less" scoped>
.nav-bar-action {
    color: @black;
    height: 78%;
}

.section-header {
    border-bottom: 1px solid @bg;
}

/deep/.article-content {
    padding: 0px 9px 8px;
    font-size: 13px;
    text-align: justify;
    line-height: 16px;

    position: absolute;
    top: 0;
    overflow: scroll;
    overflow-x: hidden;
    height: 100%;
    width: 100%;
    h5 {
        text-align: center;
    }
    img {
        max-width: 100%;
        display: block;
        margin-bottom: 8px;
        width: auto;
        height: auto;
    }

    &::-webkit-scrollbar {
        display: none;
    }
    p {
        width: 96%;
    }
}
</style>