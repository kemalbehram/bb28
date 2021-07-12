<template>
    <div class="lett-card-header">
        <div class="header-title" v-html="title" v-if="title" />

        <slot></slot>

        <Input @on-click="onSearch" @on-enter="onSearch" @on-search="onSearch" class="lett-search" search v-if="config" v-model="config.select.value">
            <Select slot="prepend" v-model="config.select.field">
                <Option :key="item.value" :value="item.value" v-for="item in config.fields">{{ item.label }}</Option>
            </Select>

            <Button @click="onSearch" icon="ios-search" slot="append"></Button>
        </Input>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        config: Object
    },
    data() {
        return {
            form: {
                data: '',
                field: ''
            }
        }
    },

    methods: {
        onSearch() {
            this.$emit('on-search')
        }
    }
}
</script>

<style lang="less" scoped>
.lett-card-header {
    margin-bottom: 18px;
    position: relative;
}
.lett-card-header {
    .header-title {
        margin-bottom: 8px;
        color: #17233d;
        font-size: 16px;
        line-height: 22px;
    }
    .ivu-select {
        width: auto;
        min-width: 100px;
    }
    .ivu-card-head {
        border-bottom: 0px;
        padding-bottom: 0px !important;
    }
}
.lett-search {
    width: 260px;
    float: right;
    position: absolute;
    right: 0;
    top: 2px;
}

.lett-search .ivu-select {
    width: auto;
    min-width: auto;
}
</style>

<style lang="less">
.lett-search .ivu-input-group-append,
.lett-search .ivu-input-group-prepend {
    background: #fff !important;
}
</style>



