<template>
  <div :style="style" @click="onClick" class="default-page">
    <div class="default-page-content">
      <van-image :src="image" height="60" width="60" />
      <div class="message" v-html="message" />
    </div>
  </div>
</template>

<script>

export default {
  props: {
    message: '',
    top: 0,
    height: {
      default: 240,
      type: [String, Number]
    }
  },

  computed: {
    image () {
      return 'https://le-share.oss-cn-shanghai.aliyuncs.com/common/defalut-error.png'
    },

    style () {
      return 'top:' + this.top + 'px; height:' + this.height + 'px'
    }
  },

  methods: {
    onClick () {
      return this.$emit('click')
    }
  }
}
</script>

<style lang="less" scoped>
.default-page {
  height: 240px;
  width: 100%;
  background: @white;
  position: absolute;
  top: 0;
  text-align: center;
  align-items: center;
  display: flex;
  justify-content: center;

  &-content {
    margin-top: -10px;
  }

  .message {
    color: @sub;
    font-size: 14px;
    margin-top: 14px;
  }
}
</style>