<template>
    <van-popup @close="updateStore" @open="setForm" class="lotto-setting" position="bottom" v-model="show">
        <section-content label="设置如无特殊说明，将对所有游戏生效" subscript="OPTION" title="游戏设置">
            <van-cell-group :border="true">
                <van-cell center label="打开后下注界面将已聊天模式展示" title="聊天模式">
                    <van-switch active-color="#09c195" size="24" slot="right-icon" v-model="form.bet_chat" />
                </van-cell>
            </van-cell-group>
        </section-content>
    </van-popup>
</template>

<script>
export default {
    data() {
        return {
            show: false,
            form: {
                bet_chat: false,
            },
        }
    },

    methods: {
        onUpdate() {
            this.form.method = 'optionUpdate'
            this.$post('user/update', this.form, false, false).then((res) => {})
        },

        setForm() {
            this.form = JSON.parse(JSON.stringify(this.$store.state.user.option))
        },

        updateStore() {
            this.$parent.$refs.action.show = false
            this.$store.state.user.option = this.form
            this.$store.dispatch('lottoScrollBottom')
            this.onUpdate()
        },
    },
}
</script>

<style lang="less" scoped>
.lotto-setting {
    height: 60%;
}
</style>