<template>
  <div class="loading"></div>
</template>
<script>
export default {
  name: "loading"
};
</script>
<style lang="less" scoped>
.loading {
  overflow: hidden;
  position: fixed;
  left: 0;
  top: 0;
  background: url('https://goplastic.oss-cn-beijing.aliyuncs.com/goplasticCommon/loading.gif')
    center center no-repeat rgba(0, 0, 0, 0.5);
  background-size: 10%;
  width: 100%;
  height: 100%;
  z-index: 1000;
}
</style>