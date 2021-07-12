<template>
  <div>
    <userHeader title="修改昵称" />
    <!-- <form-header class="mb-40" title="修改昵称" /> -->
    <van-field
      :border="false"
      class="mb-40 round"
      size="large"
      v-model="form.nickname"
      placeholder="请输入新昵称"
    />
    <van-button @click="onSave" block type="danger" class>保存</van-button>
  </div>
</template>

<script>
import userHeader from '@/components/user-header'
export default {
  data () {
    return {
      form: {
        nickname: this.$store.state.user.info.nickname,
        method: 'nicknameUpdate'
      }
    }
  },
  components: {
    userHeader
  },
  methods: {
    onSave () {
      this.$post('/user/update', this.form).then(res => {
        if (res.code === 200) {
          this.$store.state.user.info.nickname = res.data.nickname
          this.$router.go(-1)
        }
      })
    }
  }
}
</script>
<style lang="less" scoped>
/deep/.van-button {
  width: 90%;
  margin: 0 auto;
  border-radius: 10px;
}
</style>