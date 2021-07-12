<template>
    <keep-alive>
        <div class="relative">
            <div class="lett-tab-header">
                <h3 class="title">{{title}}</h3>
                <Tabs @on-click="onClick">
                    <TabPane :key="index" :label="item" :name="index" v-for="(item,index) in tab" />
                </Tabs>

                <div class="right">
                    <slot name="right"></slot>
                </div>
            </div>

            <div class="relative">
                <keep-alive>
                    <slot></slot>
                </keep-alive>
            </div>
        </div>
    </keep-alive>
</template>

<script>
export default {
    props: {
        title: String,
        tab: Object,
        value: ''
    },

    model: {
        prop: 'value',
        event: 'update'
    },

    methods: {
        onClick(name) {
            this.$emit('update', name)
            this.$emit('click', name)
        }
    }
}
</script>


<style lang="less" scope>
@border-color-base : #eaeced;
@border-color-split : #eeeff1;
.lett-tab-header {
    border: 1px solid @border-color-split;
    border-radius: 2px;
    margin-bottom: 16px;
    background: #fff;
    position: relative;
}

.lett-tab-header {
    .title {
        border-bottom: 1px solid @border-color-base;
        padding: 14px 16px 14px 16px;
    }

    .right {
        position: absolute;
        right: 16px;
        top: 14px;
    }
    .ivu-tabs {
        overflow: hidden;
    }
    .ivu-tabs-bar {
        margin: 0;
        border: 0;
        padding-left: 16px;
    }
    .ivu-tabs-ink-bar {
        display: none;
    }
    .ivu-tabs-tab {
        padding: 12px 0 !important;
        margin-right: 32px !important;
    }
    .ivu-tabs-nav-wrap {
        margin-bottom: 0;
    }
}
</style>


