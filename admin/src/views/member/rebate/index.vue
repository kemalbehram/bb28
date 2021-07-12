<template>
    <Card dis-hover>
        <table-header-new title="会员返水">
            <Date-picker :value="value1" @on-change="handleChange" class="ml-6" format="yyyy-MM-dd" placeholder="选择日期" style="width: 200px" type="date"></Date-picker>
            <Button @click="onRebate" class="ml-6" size="small" type="primary">查询</Button>
            <Button @click="onAllConfirm" class="ml-6" size="small" type="success">全部反水</Button>
            <Button @click="$store.dispatch('onRebateConfig')" class="ml-40 agent" size="small" type="success">反水配置</Button>
        </table-header-new>
        <lett-loading :visible="slect_loading" @click="hiddenLoading" />
        <i-table :columns="columns" :data="list">
            <!-- <table-user :data="row" slot="nickname" slot-scope="{row}" /> -->
            <template slot="nickname" slot-scope="{row}">
                <Avatar :src="row.user_avatar" class="mr-10" shape="circle" size="22" v-if="row.user_avatar" />
                <span class="mr-6 font-14">{{row.user_name}}</span>
            </template>

            <template slot="room1" slot-scope="{row}">
                <span>{{row.rebate_list.room1.profit}}</span>
                <!-- <Poptip placement="right" transfer width="450">
                    <i-button type="dashed">{{(row.rebate_list.room1.single+row.rebate_list.room1.combo+row.rebate_list.room1.lhb+row.rebate_list.room1.number+row.rebate_list.room1.other).toFixed(4)}}</i-button>
                    <div class="api" slot="content">
                        <table style="text-align:center" width="400">
                            <thead>
                                <tr>
                                    <th>分类(返水率)</th>
                                    <th>注数</th>
                                    <th>{{rebate_config['user_rebate']['room1']['mode'] == 1 ? '流水' :'负盈利'}}</th>
                                    <th>反水总额</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>大小单双({{rebate_config['user_rebate']['room1']['single']}})</td>
                                    <td>{{row.detail.room1.single.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room1']['mode'] == 1 ? row.detail.room1.single.amount :row.detail.room1.single.profit}}</td>
                                    <td>{{row.rebate_list.room1.single}}</td>
                                </tr>
                                <tr>
                                    <td>组合({{rebate_config['user_rebate']['room1']['combo']}})</td>
                                    <td>{{row.detail.room1.combo.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room1']['mode'] == 1 ? row.detail.room1.combo.amount :row.detail.room1.combo.profit}}</td>
                                    <td>{{row.rebate_list.room1.combo}}</td>
                                </tr>
                                <tr>
                                    <td>龙虎豹({{rebate_config['user_rebate']['room1']['lhb']}})</td>
                                    <td>{{row.detail.room1.lhb.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room1']['mode'] == 1 ? row.detail.room1.lhb.amount :row.detail.room1.lhb.profit}}</td>
                                    <td>{{row.rebate_list.room1.lhb}}</td>
                                </tr>
                                <tr>
                                    <td>数字({{rebate_config['user_rebate']['room1']['number']}})</td>
                                    <td>{{row.detail.room1.number.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room1']['mode'] == 1 ? row.detail.room1.number.amount :row.detail.room1.number.profit}}</td>
                                    <td>{{row.rebate_list.room1.number}}</td>
                                </tr>
                                <tr>
                                    <td>其他({{rebate_config['user_rebate']['room1']['other']}})</td>
                                    <td>{{row.detail.room1.other.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room1']['mode'] == 1 ? row.detail.room1.other.amount :row.detail.room1.other.profit}}</td>
                                    <td>{{row.rebate_list.room1.other}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="color-red" style="word-break: break-all;white-space:normal;" v-if="row.detail.room1.other.amount != 0 || row.detail.room1.number.amount!=0 || row.detail.room1.lhb.amount!=0 || row.detail.room1.single.amount || row.detail.room1.combo.amount !=0">{{row.rebate_list.room1.log}}</div>
                    </div>
                </Poptip>-->
            </template>

            <template slot="room1_profit" slot-scope="{row}">
                <span>{{row['bonus_room1']}}</span>
            </template>
            <template slot="room3_profit" slot-scope="{row}">
                <span>{{row['bonus_room3']}}</span>
            </template>

            <!-- <template slot="room2" slot-scope="{row}">
                <Poptip placement="right" transfer width="450">
                    <i-button type="dashed">{{(row.rebate_list.room2.single+row.rebate_list.room2.combo+row.rebate_list.room2.lhb+row.rebate_list.room2.number+row.rebate_list.room2.other).toFixed(4)}}</i-button>
                    <div class="api" slot="content">
                        <table style="text-align:center" width="400">
                            <thead>
                                <tr>
                                    <th>分类(返水率)</th>
                                    <th>注数</th>
                                    <th>金额</th>
                                    <th>反水总额</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>大小单双(({{rebate_config['user_rebate']['room2']['single']}}))</td>
                                    <td>{{row.detail.room2.single.count}}</td>
                                    <td>{{row.detail.room2.single.amount}}</td>
                                    <td>{{row.rebate_list.room2.single}}</td>
                                </tr>
                                <tr>
                                    <td>组合(({{rebate_config['user_rebate']['room2']['combo']}}))</td>
                                    <td>{{row.detail.room2.combo.count}}</td>
                                    <td>{{row.detail.room2.combo.amount}}</td>
                                    <td>{{row.rebate_list.room2.combo}}</td>
                                </tr>
                                <tr>
                                    <td>龙虎豹(({{rebate_config['user_rebate']['room2']['lhb']}}))</td>
                                    <td>{{row.detail.room2.lhb.count}}</td>
                                    <td>{{row.detail.room2.lhb.amount}}</td>
                                    <td>{{row.rebate_list.room2.lhb}}</td>
                                </tr>
                                <tr>
                                    <td>数字(({{rebate_config['user_rebate']['room2']['number']}}))</td>
                                    <td>{{row.detail.room2.number.count}}</td>
                                    <td>{{row.detail.room2.number.amount}}</td>
                                    <td>{{row.rebate_list.room2.number}}</td>
                                </tr>
                                <tr>
                                    <td>其他(({{rebate_config['user_rebate']['room2']['other']}}))</td>
                                    <td>{{row.detail.room2.other.count}}</td>
                                    <td>{{row.detail.room2.other.amount}}</td>
                                    <td>{{row.rebate_list.room2.other}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="color-red" style="word-break: break-all;white-space:normal;" v-if="row.detail.room2.other.amount != 0 || row.detail.room2.number.amount!=0 || row.detail.room2.lhb.amount!=0 || row.detail.room2.single.amount || row.detail.room2.combo.amount !=0">{{row.rebate_list.room2.log}}</div>
                    </div>
                </Poptip>
            </template>-->

            <template slot="room3" slot-scope="{row}">
                <span>{{row.rebate_list.room3.profit}}</span>
                <!-- <Poptip placement="right" transfer width="450">
                    <i-button type="dashed">{{(row.rebate_list.room3.single+row.rebate_list.room3.combo+row.rebate_list.room3.lhb+row.rebate_list.room3.number+row.rebate_list.room3.other).toFixed(4)}}</i-button>
                    <div class="api" slot="content">
                        <table style="text-align:center" width="400">
                            <thead>
                                <tr>
                                    <th>分类(返水率)</th>
                                    <th>注数</th>
                                    <th>{{rebate_config['user_rebate']['room3']['mode'] == 1 ? '流水' :'负盈利'}}</th>
                                  
                                    <th>反水总额</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>大小单双({{rebate_config['user_rebate']['room3']['single']}})</td>
                                    <td>{{row.detail.room3.single.count}}</td>
                                   <td>{{rebate_config['user_rebate']['room3']['mode'] == 1 ? row.detail.room3.single.amount :row.detail.room3.single.profit}}</td>

                                    <td>{{row.rebate_list.room3.single}}</td>
                                </tr>
                                <tr>
                                    <td>组合({{rebate_config['user_rebate']['room3']['combo']}})</td>
                                    <td>{{row.detail.room3.combo.count}}</td>
                                   <td>{{rebate_config['user_rebate']['room3']['mode'] == 1 ? row.detail.room3.combo.amount :row.detail.room3.combo.profit}}</td>
                                    <td>{{row.rebate_list.room3.combo}}</td>
                                </tr>
                                <tr>
                                    <td>龙虎豹({{rebate_config['user_rebate']['room3']['lhb']}})</td>
                                    <td>{{row.detail.room3.lhb.count}}</td>
                                   <td>{{rebate_config['user_rebate']['room3']['mode'] == 1 ? row.detail.room3.lhb.amount :row.detail.room3.lhb.profit}}</td>
                                    <td>{{row.rebate_list.room3.lhb}}</td>
                                </tr>
                                <tr>
                                    <td>数字({{rebate_config['user_rebate']['room3']['number']}})</td>
                                    <td>{{row.detail.room3.number.count}}</td>
                                   <td>{{rebate_config['user_rebate']['room3']['mode'] == 1 ? row.detail.room3.number.amount :row.detail.room3.number.profit}}</td>
                                    <td>{{row.rebate_list.room3.number}}</td>
                                </tr>
                                <tr>
                                    <td>其他({{rebate_config['user_rebate']['room3']['other']}})</td>
                                    <td>{{row.detail.room3.other.count}}</td>
                                   <td>{{rebate_config['user_rebate']['room3']['mode'] == 1 ? row.detail.room3.other.amount :row.detail.room3.other.profit}}</td>
                                    <td>{{row.rebate_list.room3.other}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="color-red" style="word-break: break-all;white-space:normal;" v-if="row.detail.room3.other.amount != 0 || row.detail.room3.number.amount!=0 || row.detail.room3.lhb.amount!=0 || row.detail.room3.single.amount || row.detail.room3.combo.amount !=0">{{row.rebate_list.room3.log}}</div>
                    </div>
                </Poptip>-->
            </template>

            <template slot="room4" slot-scope="{row}">
                <span>{{row.rebate_list.room4.profit}}</span>
                <!-- <Poptip placement="right" transfer width="450">
                    <i-button type="dashed">{{(row.rebate_list.room4.single+row.rebate_list.room4.combo+row.rebate_list.room4.lhb+row.rebate_list.room4.number+row.rebate_list.room4.other).toFixed(4)}}</i-button>
                    <div class="api" slot="content">
                        <table style="text-align:center" width="400">
                            <thead>
                                <tr>
                                    <th>分类(返水率)</th>
                                    <th>下注期数</th>
                                    <th>{{rebate_config['user_rebate']['room4']['mode'] == 1 ? '流水' :'负盈利'}}</th>
                                    <th>反水总额</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>大小单双(2.75%)</td>
                                    <td>{{row.detail.room4.single.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room4']['mode'] == 1 ? row.detail.room4.single.amount :row.detail.room4.single.profit}}</td>
                                    <td>{{row.rebate_list.room4.single}}</td>
                                </tr>
                                <tr>
                                    <td>组合(2.75%)</td>
                                    <td>{{row.detail.room4.combo.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room4']['mode'] == 1 ? row.detail.room4.combo.amount :row.detail.room4.combo.profit}}</td>
                                    <td>{{row.rebate_list.room4.combo}}</td>
                                </tr>
                                <tr>
                                    <td>龙虎豹(1.35%)</td>
                                    <td>{{row.detail.room4.lhb.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room4']['mode'] == 1 ? row.detail.room4.lhb.amount :row.detail.room4.lhb.profit}}</td>
                                    <td>{{row.rebate_list.room4.lhb}}</td>
                                </tr>
                                <tr>
                                    <td>数字(4.84%)</td>
                                    <td>{{row.detail.room4.number.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room4']['mode'] == 1 ? row.detail.room4.number.amount :row.detail.room4.number.profit}}</td>
                                    <td>{{row.rebate_list.room4.number}}</td>
                                </tr>
                                <tr>
                                    <td>其他(6.45%)</td>
                                    <td>{{row.detail.room4.other.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room4']['mode'] == 1 ? row.detail.room4.other.amount :row.detail.room4.other.profit}}</td>
                                    <td>{{row.rebate_list.room4.other}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="color-red" style="word-break: break-all;white-space:normal;" v-if="row.detail.room4.other.amount != 0 || row.detail.room4.number.amount!=0 || row.detail.room4.lhb.amount!=0 || row.detail.room4.single.amount || row.detail.room4.combo.amount !=0">{{row.rebate_list.room4.log}}</div>
                    </div>
                </Poptip>-->
            </template>

            <template slot="room5" slot-scope="{row}">
                <span>{{row.rebate_list.room5.profit}}</span>
                <!-- <Poptip placement="right" transfer width="450">
                    <i-button type="dashed">{{(row.rebate_list.room5.single+row.rebate_list.room5.combo+row.rebate_list.room5.lhb+row.rebate_list.room5.number+row.rebate_list.room5.other).toFixed(4)}}</i-button>
                    <div class="api" slot="content">
                        <table style="text-align:center" width="400">
                            <thead>
                                <tr>
                                    <th>分类(返水率)</th>
                                    <th>注数</th>
                                    <th>{{rebate_config['user_rebate']['room5']['mode'] == 1 ? '流水' :'负盈利'}}</th>
                                    <th>反水总额</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>大小单双({{rebate_config['user_rebate']['room5']['single']}})</td>
                                    <td>{{row.detail.room5.single.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room5']['mode'] == 1 ? row.detail.room5.single.amount :row.detail.room5.single.profit}}</td>
                                    
                                    <td>{{row.rebate_list.room5.single}}</td>
                                </tr>
                                <tr>
                                    <td>组合({{rebate_config['user_rebate']['room5']['combo']}})</td>
                                    <td>{{row.detail.room5.combo.count}}</td>
                                    <>{{rebate_config['user_rebate']['room5']['mode'] == 1 ? row.detail.room5.combo.amount :row.detail.room5.combo.profit}}</td>
   
                                    <td>{{row.rebate_list.room5.combo}}</td>
                                </tr>
                                <tr>
                                    <td>龙虎豹(({{rebate_config['user_rebate']['room5']['lhb']}}))</td>
                                    <td>{{row.detail.room5.lhb.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room5']['mode'] == 1 ? row.detail.room5.lhb.amount :row.detail.room5.lhb.profit}}</td>
          
                                    <td>{{row.rebate_list.room5.lhb}}</td>
                                </tr>
                                <tr>
                                    <td>数字({{rebate_config['user_rebate']['room5']['number']}})</td>
                                    <td>{{row.detail.room5.number.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room5']['mode'] == 1 ? row.detail.room5.other.amount :row.detail.room5.other.profit}}</td>
                                    
                                    <td>{{row.rebate_list.room5.number}}</td>
                                </tr>
                                <tr>
                                    <td>其他({{rebate_config['user_rebate']['room5']['other']}})</td>
                                    <td>{{row.detail.room5.other.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room5']['mode'] == 1 ? row.detail.room5.other.amount :row.detail.room5.other.profit}}</td>
                                   
                                    <td>{{row.rebate_list.room5.other}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="color-red" style="word-break: break-all;white-space:normal;" v-if="row.detail.room5.other.amount != 0 || row.detail.room5.number.amount!=0 || row.detail.room5.lhb.amount!=0 || row.detail.room5.single.amount || row.detail.room5.combo.amount !=0">{{row.rebate_list.room5.log}}</div>
                    </div>
                </Poptip>-->
            </template>

            <template slot="room6" slot-scope="{row}">
                <span>{{row.rebate_list.room6.profit}}</span>
                <!-- <Poptip placement="right" transfer width="450">
                    <i-button type="dashed">{{(row.rebate_list.room6.single+row.rebate_list.room6.combo+row.rebate_list.room6.lhb+row.rebate_list.room6.number+row.rebate_list.room6.other).toFixed(4)}}</i-button>
                    <div class="api" slot="content">
                        <table style="text-align:center" width="400">
                            <thead>
                                <tr>
                                    <th>分类(返水率)</th>
                                    <th>注数</th>
                                    <th>{{rebate_config['user_rebate']['room6']['mode'] == 1 ? '流水' :'负盈利'}}</th>

                                    <th>反水总额</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>大小单双({{rebate_config['user_rebate']['room6']['single']}})</td>
                                    <td>{{row.detail.room6.single.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room6']['mode'] == 1 ? row.detail.room6.single.amount :row.detail.room6.single.profit}}</td>

                                    <td>{{row.rebate_list.room6.single}}</td>
                                </tr>
                                <tr>
                                    <td>组合({{rebate_config['user_rebate']['room6']['combo']}})</td>
                                    <td>{{row.detail.room6.combo.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room6']['mode'] == 1 ? row.detail.room6.combo.amount :row.detail.room6.combo.profit}}</td>

                                    <td>{{row.rebate_list.room6.combo}}</td>
                                </tr>
                                <tr>
                                    <td>龙虎豹({{rebate_config['user_rebate']['room6']['lhb']}})</td>
                                    <td>{{row.detail.room6.lhb.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room6']['mode'] == 1 ? row.detail.room6.lhb.amount :row.detail.room6.lhb.profit}}</td>

                                    <td>{{row.rebate_list.room6.lhb}}</td>
                                </tr>
                                <tr>
                                    <td>数字({{rebate_config['user_rebate']['room6']['number']}})</td>
                                    <td>{{row.detail.room6.number.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room6']['mode'] == 1 ? row.detail.room6.number.amount :row.detail.room6.number.profit}}</td>

                                    <td>{{row.rebate_list.room6.number}}</td>
                                </tr>
                                <tr>
                                    <td>其他({{rebate_config['user_rebate']['room6']['other']}})</td>
                                    <td>{{row.detail.room6.other.count}}</td>
                                    <td>{{rebate_config['user_rebate']['room6']['mode'] == 1 ? row.detail.room6.other.amount :row.detail.room6.other.profit}}</td>

                                    <td>{{row.rebate_list.room6.other}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="color-red" style="word-break: break-all;white-space:normal;" v-if="row.detail.room6.other.amount != 0 || row.detail.room6.number.amount!=0 || row.detail.room6.lhb.amount!=0 || row.detail.room6.single.amount || row.detail.room6.combo.amount !=0">{{row.rebate_list.room6.log}}</div>
                    </div>
                </Poptip>-->
            </template>

            <template slot="action" slot-scope="{row}">
                <i-button @click="confirm(row.user_id,row.total_rebate)" size="small" type="success" v-if="rebated_users.indexOf(row.user_id) === -1">确认返水</i-button>
                <i-button size="small" type="info" v-else>已返水</i-button>
            </template>
        </i-table>
        <br />
        <Card dis-hover>
            <lett-loading :visible="loading" @click="hiddenLoading" />
            <Table :columns="bet_columns" :data="bet_list" border>
                <template slot="nickname" slot-scope="{row}">
                    <Avatar :src="row.user.avatar" class="mr-10" shape="circle" size="22" v-if="row.user.avatar" />
                    <span class="mr-6 font-14">{{row.user.nickname}}</span>
                </template>
            </Table>
            <br />
            <Page :page-size="pagesize" :total="total" @on-change="pageChange" />
        </Card>
        <rebate-config />
    </Card>
</template>

<script>
import rebateConfig from '@/views/drawer/rebate-config'
export default {
    name: 'memberRebate',
    components: {
        rebateConfig,
    },
    data() {
        return {
            value1: '',
            columns: [
                {
                    title: '用户ID',
                    key: 'user_id',
                },
                {
                    title: '昵称',
                    slot: 'nickname',
                    fixed: 'left',
                    width: 150,
                },
                {
                    title: '下注期数',
                    key: 'total_count',
                },
                {
                    title: '总流水',
                    key: 'total_amount',
                },
                {
                    title: '(总)负盈利',
                    key: 'total_profit',
                },
                {
                    title: '房间1-负盈利',
                    slot: 'room1_profit',
                },
                {
                    title: '房间3-负盈利',
                    slot: 'room3_profit',
                },
                {
                    title: '总返利',
                    key: 'total_rebate',
                },

                {
                    title: '房间一',
                    slot: 'room1',
                },

                // {
                //     title: '玩法2',
                //     slot: 'room2',
                // },
                {
                    title: '房间三',
                    slot: 'room3',
                },
                {
                    title: '房间四(未使用)',
                    slot: 'room4',
                },
                {
                    title: '房间五',
                    slot: 'room5',
                },
                {
                    title: '房间六(未使用)',
                    slot: 'room6',
                },
                // {
                //     title: '玩法7',
                //     slot: 'room7',
                // },
                // {
                //     title: '玩法8',
                //     slot: 'room8',
                // },
                {
                    title: '操作',
                    slot: 'action',
                },
            ],

            list: [],
            bet_list: [],
            bet_columns: [
                {
                    title: '用户ID',
                    key: 'user_id',
                },
                {
                    title: '昵称',
                    slot: 'nickname',
                },

                {
                    title: '日期',
                    key: 'date',
                },
                {
                    title: '反水金额',
                    key: 'profit',
                },
            ],
            page: 1,
            pagesize: 20,
            total: 0,
            rebated_users: [],
            loading: false,
            slect_loading: false,
            rebate_config: {},
        }
    },
    created() {
        var day1 = new Date()
        day1.setTime(day1.getTime() - 24 * 60 * 60 * 1000)
        var s1 = day1.getFullYear() + '-' + (day1.getMonth() + 1) + '-' + day1.getDate()
        this.value1 = s1
        this.getConfig()
        console.log(this.loading)
    },
    methods: {
        handleChange(e) {
            this.value1 = e
            console.log(this.value1)
        },
        getConfig() {
            this.$get('/user-rebate/config').then((res) => {
                this.rebate_config = res.data
                // console.log(this.rebate_config)
            })
        },

        onRebate() {
            let params = {
                date: this.value1,
            }
            if (this.value1 == '') {
                return this.$Message.warning('请选择日期')
            }
            this.slect_loading = true
            this.$get('/user-rebate/index', params).then((res) => {
                //console.log(res.data);

                this.list = []
                this.slect_loading = false
                if (res.data !== undefined) {
                    this.list = res.data
                } else {
                    return this.$Message.warning('今日暂无投注记录')
                }
            })
            this.getRebateList()
        },
        getRebateList() {
            let listparams = {
                page: this.page,
                date: this.value1,
            }
            this.loading = true
            this.$get('user-rebate/rebatelist', listparams).then((res) => {
                this.bet_list = []
                this.rebated_users = []
                this.bet_list = res.data.items
                if (this.bet_list.length > 0) {
                    this.bet_list.map((item) => {
                        this.rebated_users.push(item.user_id)
                    })
                }

                this.total = res.data.page.total
                this.loading = false
            })
        },
        pageChange(e) {
            this.page = e
            this.getRebateList()
        },
        confirm(user_id, amount) {
            this.loading = true
            if (this.value1 == '') {
                return this.$Message.warning('请选择日期')
            }
            this.$Modal.confirm({
                title: '确认反水',
                onOk: () => {
                    let params = {
                        amount: amount,
                        user_id: user_id,
                        // bet_amount: amount,
                        // bet_count: count,
                        date: this.value1,
                    }
                    this.$post('/user-rebate/confirm', params).then((res) => {
                        console.log('111')
                    })
                    this.getRebateList()
                    this.loading = false
                },
                onCancel: () => {
                    this.loading = false
                },
            })
        },
        onAllConfirm() {
            if (this.value1 == '') {
                return this.$Message.warning('请选择日期')
            }
            // console.log(this.list); return;
            if (this.list.length == 0) {
                return this.$Message.warning('暂无反水用户')
            }
            this.loading = true
            this.$Modal.confirm({
                title: '确认全部反水吗',
                onOk: () => {
                    let params = {
                        data: this.list,
                        date: this.value1,
                    }
                    this.$post('/user-rebate/allconfirm', params).then((res) => {
                        // if(res.data)
                        // console.log('111');
                    })
                    this.getRebateList()
                },
                onCancel: () => {
                    this.loading = false
                },
            })
        },
        hiddenLoading() {},
    },
}
</script>

