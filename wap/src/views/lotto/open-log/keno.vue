<template>
    <div :class="lotto.chat_open_log ? 'lotto-win-table chat-open-log' : 'lotto-win-table'">
        <table>
            <thead>
                <tr>
                    <th>期号</th>
                    <th>开奖时间</th>
                    <th width="186px">开奖结果</th>
                </tr>
            </thead>

            <tbody>
                <div class="pt-10"></div>
                <template v-for="item in items">
                    <tr :key="item.id" align="center">
                        <td>{{item.short_id}}</td>
                        <td>{{item.lotto_at | time_lint}}</td>
                        <td>
                            <div class="win-code-extend" v-if="item.win_extend">
                                <span class="win-code-item color-white bg-blue" v-html="item.win_extend.code_arr[0]" />
                                <span>+</span>
                                <span class="win-code-item color-white bg-blue" v-html="item.win_extend.code_arr[1]" />
                                <span>+</span>
                                <span class="win-code-item color-white bg-blue" v-html="item.win_extend.code_arr[2]" />
                                <span>=</span>
                                <span class="win-code-item color-white bg-red">{{item.win_extend.code_he}}</span>
                                <span>{{item.win_extend.code_bos}}{{item.win_extend.code_sod}}</span>
                            </div>
                            <div v-else>开奖中</div>
                        </td>
                        <!-- <td class v-if="item.win_extend">{{item.win_extend.code_he}}</td>
                        <td v-if="item.win_extend">{{item.win_extend.code_bos}}</td>
                        <td v-if="item.win_extend">{{item.win_extend.code_sod}}</td>-->
                    </tr>
                </template>
                <div class="pb-10"></div>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        lotto: Object,
        loading: Boolean,
    },

    computed: {
        items() {
            return this.lotto.open_last
        },

        items_data() {
            if (this.items.length > 0) return this.items
            let cache = 'lotto_table_data_default:keno'
            let local = sessionStorage.getItem(cache)
            if (local) return JSON.parse(local)
            let temp_1 = {
                bet_count_down: 100,
                bet_stats: {},
                float_odds: null,
                lotto_at: '2020-05-20 00:00:00',
                short_id: '0000000',
                status: 1,
                win_extend: null,
            }

            let temp_2 = {
                bet_count_down: -1,
                bet_stats: {},
                float_odds: {
                    bet_people: '200',
                    bet_total: '1000000.000',
                },
                lotto_at: '2020-05-20 00:00:00',
                short_id: '0000000',
                status: 2,
                win_extend: {
                    code_arr: [0, 0, 0],
                    code_bos: '小',
                    code_he: '0',
                    code_sod: '双',
                },
            }

            let result = []
            for (let index = 0; index < 15; index++) {
                if (index >= 5) {
                    result.push(temp_2)
                } else {
                    result.push(temp_1)
                }
            }

            if (result.length > 0) {
                sessionStorage.setItem(cache, JSON.stringify(result))
            }

            return result
        },
    },
}
</script>

<style lang="less" scoped>
.win-code-content span {
    margin-right: 2px;
}
.win-code-item {
    // margin-right: 10px;
}
span {
    margin-left: 4px;
    display: inline-block;
}
.win-code-item {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    // background: red;
    display: inline-block;
    line-height: 18px;

    text-align: center;
}

thead {
    background: #f3f3f6;
}
tbody {
    border-top: 1px solid rgb(226, 226, 226);
}
</style>