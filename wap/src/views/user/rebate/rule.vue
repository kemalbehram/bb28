<template>
    <div class="rule">
        <div :key="index" class="rule-item" v-for="(item,index) in  rebate_list">
            <div class="font-16 pt-6">{{item.title}}</div>

            <span :key="index1" class="item" v-for="(item1,index1) in item.value">
                <p class="rate">{{item1}}</p>
                <p>{{index1 == 'lhb' ? '龙虎豹' : index1 == 'combo' ? '组合' : index1 == 'other' ? '其他' : index1 == 'number' ? '数字' :'单注' }}</p>
            </span>
            <div style="clear:both"></div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        child: Boolean,
    },
    data() {
        return {
            list: [
                {
                    title: '28游戏-玩法1(3.69/4.69)',
                },

                {
                    title: '28游戏-玩法2(4.2/4.6)',
                },
            ],
        }
    },

    computed: {
        rebate_list() {
            this.list.forEach((item, index) => {
                let config_list = []
                if (this.$store.state.user.info.is_agent || this.child) {
                    config_list = Object.values(this.$store.state.config.rebate_config[0])
                } else {
                    config_list = Object.values(this.$store.state.config.rebate_config[0])
                }

                item['value'] = config_list[index]
            })

            // this.$store.state.config.rebate_config
            return this.list
        },
    },
}
</script>
<style lang="less" scoped>
.rule {
    .rule-item {
        background: white;
        padding: 0 5%;
        border-bottom: 10px solid #f0eff4;
        text-align: center;
        .item {
            float: left;
            width: 20%;
            .rate {
                color: #f93c03;
            }
        }
        .line {
            width: 100%;
            height: 10px;
        }
    }
}
</style>
