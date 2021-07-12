<template>
    <div>
        <userHeader title="修改姓名" />
        <!-- <form-header class="mb-40" title="修改昵称" /> -->
        <div class="base-wrap pt-40">
            <van-field :border="false" class="mb-40 round" placeholder="请输入姓名" size="large" v-model="form.real_name" />
            <van-button @click="onSave" block class type="danger">保存</van-button>
        </div>
    </div>
</template>

<script>
import userHeader from '@/components/user-header'
export default {
    data() {
        return {
            form: {
                real_name: this.$store.state.user.info.real_name,
                method: 'realNameUpdate',
            },
        }
    },
    components: {
        userHeader,
    },
    methods: {
        onSave() {
            this.$post('/user/update', this.form).then((res) => {
                if (res.code === 200) {
                    this.$store.state.user.info.real_name = res.data.real_name
                    this.$router.go(-1)
                }
            })
        },
    },
}
</script>
<style lang="less" scoped>
/deep/.van-button {
    width: 90%;
    margin: 0 auto;
    border-radius: 10px;
}
</style>