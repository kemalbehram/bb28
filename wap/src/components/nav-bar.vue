<template>
    <div class="nav-bar">
        <van-nav-bar :border="show_border" :placeholder="has_show" :title="title" @click-left="$store.dispatch('toBack')" fixed left-arrow>
            <van-icon @click="show = true" name="bars" size="22" slot="right" />
        </van-nav-bar>
        <van-action-sheet :actions="actions" :round="false" @select="onSelect" cancel-text="关闭" v-model="show" />
    </div>
</template>

<script>
export default {
    props: {
        title: {
            default: '链接标题',
            type: String,
        },
        has_show: {
            default: true,
            type: Boolean,
        },
        show_border: {
            default: false,
            type: Boolean,
        },
    },

    data() {
        return {
            show: false,
        }
    },

    computed: {
        actions() {
            return [
                {
                    name: '在线客服',
                    action: 'route',
                    route: { name: 'service' },
                    className: 'van-hairline--bottom',
                },
                {
                    name: '刷新网页',
                    action: 'reload',
                    // className: 'van-hairline--bottom',
                },
            ]
        },
    },
    methods: {
        onSelect(data) {
            this.show = false
            this.$nextTick((fn) => {
                if (data.action === 'route') {
                    this.$router.push(data.route)
                }

                if (data.action === 'reload') {
                    location.reload()
                }
            })
        },
    },
}
</script>
