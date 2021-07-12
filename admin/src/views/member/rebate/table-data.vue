<template>
  <div class="relative">
    <lett-loading :visible="loading" @click="getIndex" />

    <Table :columns="columns" :data="items" :height="300 | table_height" border>
      <table-user :data="row" slot="nickname" slot-scope="{row}" />

      <template slot="lotto_play_type" slot-scope="{row}">{{row.details[0].title}}</template>

      <Tooltip
        :content="row.created_at"
        placement="top"
        slot="createdAt"
        slot-scope="{row}"
        v-if="row.created_at"
      >
        <Time :interval="1" :time="row.created_at" />
      </Tooltip>

      <div slot="action" slot-scope="{row, index }">
        <Button @click="$store.dispatch('onMemberInfo', row.id)" size="small" type="primary">详情</Button>
      </div>
    </Table>
    <lett-page :page="page" @on-change="onPage" />
  </div>
</template>

<script>
import JsMixins from '@/components-ue/table/table.js'
export default {
  mixins: [JsMixins],
  data () {
    return {
      dataware: {
        index: {
          api: 'lotto/bet-log/index'
        }
      },
      columns: [
        {
          title: 'ID',
          width: 120,
          key: 'id'
        },
        {
          title: '用户ID',
          key: 'user_id',

        },
        {
          title: '彩种',
          key: 'lotto_title'
        },

        {
          title: '玩法',
          slot: 'lotto_play_type'
        },
        {
          title: '期号',
          key: 'short_id'
        },

        {
          title: '投注金额',
          key: 'total'
        },
        {
          title: '中奖金额',
          key: 'bonus'
        },
        {
          title: '投注时间',
          key: 'created_at',
          slot: 'createdAt',
          align: 'center'
        },
        {
          title: '操作',
          slot: 'action',
          width: 140,
          align: 'center'
        }
      ]
    }
  }
}
</script>