<template>
    <div>
        <userHeader title="联系QQ" />
        <div class="base-wrap pt-40">
            <van-field :border="false" class="mb-40 round" placeholder="请输入QQ" size="large" v-model="form.contact_qq" />
            <div class="base-wrap">
                <van-button @click="onSave" block round type="danger">保存</van-button>

                <!-- <div class="base-intro-single mt-40">请填写您的真实可联系到的QQ。</div> -->
            </div>
        </div>
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
            form: {
                contact_qq: this.$store.state.user.info.contact_qq,
                method: 'contactQqUpdate',
            },
        }
    },

    methods: {
        onSave() {
            this.$post('/user/update', this.form).then((res) => {
                if (res.code === 200) {
                    this.$store.state.user.info.contact_qq = res.data.contact_qq
                    this.$router.go(-1)
                }
            })
        },
    },
}
</script>
