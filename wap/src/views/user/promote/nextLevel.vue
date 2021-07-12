<template>
  <div class="relative">
    <userHeader title="我的下线" />
    <dataDefault :top="40" message="您暂无下级用户 再接再厉哦" v-if="items.length === 0" />
    <div v-if="items.length !== 0" class="next-list">
      <van-cell v-for="(item,index) in items" :key="index">
        <!-- <van-cell
        is-link
        v-for="(item,index) in items"
        :key="index"
        :to="{ name: 'userPromoteNextLevelDetail', params: {user_id:item.user_id}}"
        >-->
        <!-- 使用 title 插槽来自定义标题 -->
        <template #title>
          <van-image :src="item.user.avatar" round width="46" height="46" />
          <span class="name">{{item.user.nickname}}</span>
        </template>
        <template #default>
          <span class="back-value">
            {{item.bet_total}}
            <span class="font-12">元</span>
          </span>
          <span class="back-title font-12">投注总额</span>
        </template>
      </van-cell>
    </div>
  </div>
</template>

<script>
import userHeader from '@/components/user-header'
import dataDefault from '@/components/data-default.vue'
export default {
  name: 'nextLevel',
  data () {
    return {
      items: [],
      loading: false,
      message: null
    }
  },
  components: {
    dataDefault,
    userHeader
  },


  created () {
    this.getData()
  },

  methods: {
    getData () {
      this.loading = true
      this.$get('user/reference/level').then(res => {
        if (res.code !== 200) {
          return (this.loading = res.message)
          this.message = res.message
          return false
        }
        this.items = res.data.items

        if (res.data.items.length === 0) {
          return (this.loading = '您暂无下级用户 再接再厉哦')
        }
        this.loading = false
      })
    },

  }
}
</script>

<style lang="less" scoped>
.next-list {
  .name {
    vertical-align: top;
    display: inline-block;
    margin-left: 10px;
    padding-top: 14px;
  }
  /deep/.van-image {
    margin-top: 5px;
  }
  .back-value {
    display: block;
    font-size: 20px;
    color: #ff5742;
    padding-right: 6px;
    line-height: 12px;
    margin-top: 12px;
  }
  .back-title {
    display: block;
    padding-right: 6px;
    color: #a0a0a0;
  }
  /deep/.van-icon {
    margin-top: 18px;
  }
}
</style>