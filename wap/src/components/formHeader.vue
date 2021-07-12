<template>
 <div class="form-header">
  <h1 class="form-header-i">
    <van-icon :name="icon" @click="toBack" size="24" />
  </h1>
  <div class="form-header-title">{{title}}</div>
  </div>
</template>

<script>
export default {
  props: {
    icon: {
      default: 'play-circle',
      type: String
    },
    title: {
      default: '',
      type: String
    }
  },

  methods: {
    toBack () {
      this.$router.back(-1)
    }
  }
}
</script>


<style lang="less" scoped>
.form-header {
    // display: flex;
    // align-items: center;
    .form-header-i {
      position: relative;
      font-size: 20px;
      line-height: 24px;
      .van-icon {
        transform: rotate(180deg);
        vertical-align: sub;
        padding-left: 6px;
      }
    }
    .form-header-title {
        width:100%;
        text-align: center;
        font-size: 24px;
        
    }
}
</style>