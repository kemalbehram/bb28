<template>
    <div class="lett-card-header">
        <div class="header-title" v-html="title" v-if="title" />

        <slot></slot>

        <Input @on-click="onQuery" @on-enter="onQuery" @on-search="onQuery" class="lett-search" search v-if="queryField" v-model="form.data">
            <Select slot="prepend" v-model="form.field">
                <Option :key="item.value" :value="item.value" v-for="item in queryField">{{ item.label }}</Option>
            </Select>

            <Button @click="onQuery" icon="ios-search" slot="append"></Button>
        </Input>
    </div>
</template>

<script>
export default {
    props: {
        queryField: Array,
        title: String
    },
    data() {
        return {
            form: {
                data: '',
                field: ''
            }
        }
    },

    created() {
        if (this.queryField) {
            var array = this.queryField
            for (var i = 0; i < array.length; i++) {
                var temp = array[0].value
                if (array[i].default == true) {
                    temp = array[i].value
                }
            }
            this.form.field = temp
        }
    },

    methods: {
        onQuery() {
            var query = new Object()
            this.form.data && (query[this.form.field] = this.form.data)
            this.$emit('on-query', query)
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



