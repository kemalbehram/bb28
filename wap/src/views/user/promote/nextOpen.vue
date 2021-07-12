<template>
    <div class="relative">
        <userHeader title="下线开户" />
        <form @submit="onSubmit">
            <van-field :rules="[{ required: true, message: '请填写用户名' }]" label="下级用户名" name="用户名" placeholder="用户名" v-model="username" />
            <van-field :rules="[{ required: true, message: '请填写密码' }]" label="下级密码" name="密码" placeholder="密码" type="password" v-model="password" />
            <van-field label="账户类型" name="radio">
                <template #input>
                    <van-radio-group direction="horizontal" v-model="type">
                        <van-radio name="1">普通账户</van-radio>
                        <van-radio name="2">代理</van-radio>
                    </van-radio-group>
                </template>
            </van-field>

            <br />
            <van-cell-group>
                <van-cell title="默认方案" value="点击修改" />
            </van-cell-group>
            <br />
            <van-cell-group>
                <van-cell label="描述信息" title="默认方案" value="编辑" />
            </van-cell-group>
            <br />
            <van-cell-group>
                <van-cell :to="{ name: 'addPlan'}" icon="add-o" title="新增方案" />
            </van-cell-group>
            <div style="margin: 16px;">
                <van-button block native-type="submit" round type="info">确认开户</van-button>
            </div>
        </form>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
    },
    data() {
        return {
            username: '',
            password: '',
            type: 1,
        }
    },
    methods: {
        onSubmit() {},
    },
}
</script>
<style lang="less" scoped>
</style>