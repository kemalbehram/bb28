<template>
    <div class="chat-content">
        <lottoNavBar :lotto_name="lotto_name" :room_id="room_id" ref="nav" />
        <div v-if="!show_squint">
            <lottoChatHeader :lotto="this" class="lotto-header-wrap" />
            <van-count-down :time="bet_new_one.bet_count_down * 1000" @finish="setNextCurrent()" class="display-none" v-if="bet_new_one.id"></van-count-down>
            <winCode :lotto="this" />
            <open-log :lotto="this" v-if="has_load === true" />
        </div>
        <div v-else>
            <lottoChatSquint :lotto="this" @refresh="refresh" />
        </div>
        <div id="lotto-win-table" v-show="show_open_dom === true" />
        <div class="button-content">
            <div class="button-item">
                <!-- <van-button :disabled="stop_bet" @click="betMethod(1)" color="#1989fa" size="small" type="info">投注</van-button> -->
                <van-button @click="betMethod(1)" color="#ff6a00" size="small" type="info">投注</van-button>
            </div>
            <van-action-sheet class="tra-bet" title="投注" v-model="betShow1">
                <div class="content-1">
                    <van-tree-select :items="betItems" :main-active-index.sync="active" height>
                        <template #content>
                            <div class="dxds" v-if="active == 1">
                                <div :class="checked[item.place] != undefined ? 'item active' : 'item'" :key="index" @click="singleBet(item.place)" v-for="(item,index) in getComboList">
                                    <span>{{item.name}}</span>
                                    <span>{{item.odds.substring(0,5)}}</span>
                                </div>
                            </div>
                            <div class="dxds" v-if="active == 2">
                                <div :class="checked[item.place] != undefined ? 'item active' : 'item'" :key="index" @click="singleBet(item.place)" v-for="(item,index) in getNumberList">
                                    <span>{{item.name}}</span>
                                    <span>{{item.odds.substring(0,5)}}</span>
                                </div>
                            </div>
                            <div class="dxds" v-if="active == 3">
                                <div :class="checked[item.place] != undefined ? 'item active' : 'item'" :key="index" @click="singleBet(item.place)" v-for="(item,index) in getSpecialList">
                                    <span>{{item.name}}</span>
                                    <span>{{item.odds.substring(0,5)}}</span>
                                </div>
                            </div>
                            <div class="dxds" v-if="active == 0">
                                <div :class="checked[item.place] != undefined ? 'item active' : 'item'" :key="index" @click="singleBet(item.place)" v-for="(item,index) in getWalletList">
                                    <span>{{item.name}}</span>
                                </div>
                            </div>
                            <!-- <van-grid :border="false" :column-num="2" class="combo-area" clickable v-if="active == 0">
                                <van-grid-item :class="item.name == '大双' || item.name =='小双' || item.name=='大单' || item.name=='小单' ? 'small' :''" :key="index" @click="singleBet(item.place)" v-for="(item,index) in getComboList">
                                    <span>{{item.name}}</span>
                                    <span class="bet-money" v-if="checked[item.place] != undefined">{{checked[item.place] != undefined ? checked[item.place] :0 }}</span>
                                    <b class="color-red">{{stop ? '封盘中' :''}}</b> 
                                    <span :style="checked[item.place] != undefined ? 'color:red' : ''" class="odds">{{item.odds.substring(0,5)}}</span>
                                </van-grid-item>
                            </van-grid>
                            <van-grid :border="false" :column-num="3" class="combo-area" clickable v-if="active == 1">
                                <van-grid-item :key="index" @click="singleBet(item.place)" v-for="(item,index) in getNumberList">
                                    <span class="color-red">{{item.name}}</span>
                                    <span class="bet-money" v-if="checked[item.place] != undefined">{{checked[item.place] != undefined ? checked[item.place] :0 }}</span>
                                    <b class="color-red">{{stop ? '封盘中' :''}}</b>

                                    <span :style="checked[item.place] != undefined ? 'color:red' : ''" class="number-odds">{{item.odds.substring(0,5)}}</span>
                                </van-grid-item>
                            </van-grid>
                            <van-grid :border="false" :column-num="2" class="combo-area" clickable v-if="active == 2">
                                <van-grid-item :key="index" @click="singleBet(item.place)" v-for="(item,index) in getSpecialList">
                                    <span class="color-red">{{item.name}}</span>
                                    <span class="bet-money" v-if="checked[item.place] != undefined">{{checked[item.place] != undefined ? checked[item.place] :0 }}</span>
                                    <span :style="checked[item.place] != undefined ? 'color:red' : ''" class="odds">{{item.odds.substring(0,5)}}</span>
                                    <b class="color-red">{{stop ? '封盘中' :''}}</b>
                                </van-grid-item>
                            </van-grid>-->
                        </template>
                    </van-tree-select>
                    <div class="chips">
                        <!-- <van-grid :border="false" :column-num="6" class="chips-area">
                            <van-grid-item :class="item.is_choose ? 'chosen':''" :key="index" @click="chooseChip(index,item.value)" v-for="(item,index) in chips_list">
                                <span>{{item.name}}</span>
                            </van-grid-item>
                        </van-grid>-->
                    </div>
                    <div class="button">
                        <van-field type="number" v-model="has_bet_str" />
                        <van-button @click="betConfirmPre" size="small" type="info">投注</van-button>
                        <van-button @click="clearBet" color="#FFD700" size="small">重置</van-button>
                        <van-button @click="cancelFastBet" class="ml-6" size="small" type="danger">撤销</van-button>
                    </div>
                </div>
            </van-action-sheet>

            <!-- <div class="button-item">
                <van-button @click="betMethod(2)" class="fast-t" color="white" size="small" type="info">快投</van-button>
                <van-action-sheet :round="false" :title="this.stop === true ? '封盘中' :'快投'" class="fast-bet" v-model="betShow2">
                    <div class="content-2">
                        <div class="info-area">
                            <van-field placeholder="注:每注请以空格分隔,例:大88 小888" readonly v-model="fast_checked" />
                            <van-button @click="fastPreConfirm" size="small">下注</van-button>
                        </div>
                        <div class="number-area">
                            <div @click="scrollLeft" class="left">
                                <van-icon color="#B7B7C7" name="arrow-left" size="14" />
                            </div>
                            <div class="middle" id="number-content">
                                <div :class="fast_bet_arr.indexOf(item.place) !== -1 ? 'item-active' : ''" :key="index" @click="fastNumberBet(item.place,item.name)" v-for="(item,index) in getNumberList">
                                    <span>{{item.name}}</span>
                                    <span>{{item.odds.substring(0,5)}}</span>
                                </div>
                            </div>
                            <div @click="scrollRight" class="right">
                                <van-icon color="#B7B7C7" name="arrow" size="14" />
                            </div>
                        </div>
                    </div>
                    <van-grid :border="false" :column-num="5" class="keybord-bet" clickable>
                        <van-grid-item @click="cancelFastBet" class="action">取消下注</van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getSpecialList[0].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getSpecialList[0].place,getSpecialList[0].name)">
                            <span>{{getSpecialList[0].name}}</span>
                            <span>{{getSpecialList[0].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getSpecialList[1].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getSpecialList[1].place,getSpecialList[1].name)" class="keybord-bet-item">
                            <span>{{getSpecialList[1].name}}</span>
                            <span>{{getSpecialList[1].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getSpecialList[2].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getSpecialList[2].place,getSpecialList[2].name)" class="keybord-bet-item">
                            <span>{{getSpecialList[2].name}}</span>
                            <span>{{getSpecialList[2].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item @click="cutFastBet" class="action">
                            <van-icon name="cross" />
                        </van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getComboList[0].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getComboList[0].place,getComboList[0].name)" class="keybord-bet-item">
                            <span>{{getComboList[0].name}}</span>
                            <span>{{getComboList[0].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item @click="fastChooseChip(1)" class="number">1</van-grid-item>
                        <van-grid-item @click="fastChooseChip(2)" class="number">2</van-grid-item>
                        <van-grid-item @click="fastChooseChip(3)" class="number">3</van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getComboList[1].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getComboList[1].place,getComboList[1].name)" class="keybord-bet-item">
                            <span>{{getComboList[1].name}}</span>
                            <span>{{getComboList[1].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getComboList[2].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getComboList[2].place,getComboList[2].name)" class="keybord-bet-item">
                            <span>{{getComboList[2].name}}</span>
                            <span>{{getComboList[2].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item @click="fastChooseChip(4)" class="number">4</van-grid-item>
                        <van-grid-item @click="fastChooseChip(5)" class="number">5</van-grid-item>
                        <van-grid-item @click="fastChooseChip(6)" class="number">6</van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getComboList[3].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getComboList[3].place,getComboList[3].name)" class="keybord-bet-item">
                            <span>{{getComboList[3].name}}</span>
                            <span>{{getComboList[3].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getComboList[4].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getComboList[4].place,getComboList[4].name)" class="keybord-bet-item">
                            <span>{{getComboList[4].name}}</span>
                            <span>{{getComboList[4].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item @click="fastChooseChip(7)" class="number">7</van-grid-item>
                        <van-grid-item @click="fastChooseChip(8)" class="number">8</van-grid-item>
                        <van-grid-item @click="fastChooseChip(9)" class="number">9</van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getComboList[5].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getComboList[5].place,getComboList[5].name)" class="keybord-bet-item">
                            <span>{{getComboList[5].name}}</span>
                            <span>{{getComboList[5].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getComboList[6].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getComboList[6].place,getComboList[6].name)" class="keybord-bet-item">
                            <span>{{getComboList[6].name}}</span>
                            <span>{{getComboList[6].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getComboList[9].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getComboList[9].place,getComboList[9].name)" class="keybord-bet-item">
                            <span>{{getComboList[9].name}}</span>
                            <span>{{getComboList[9].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item @click="fastChooseChip(0)" class="number">0</van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getComboList[8].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getComboList[8].place,getComboList[8].name)" class="keybord-bet-item">
                            <span>{{getComboList[8].name}}</span>
                            <span>{{getComboList[8].odds.substring(0,5)}}</span>
                        </van-grid-item>

                        <van-grid-item :class="fast_bet_arr.indexOf(getComboList[7].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getComboList[7].place,getComboList[7].name)" class="keybord-bet-item">
                            <span>{{getComboList[7].name}}</span>
                            <span>{{getComboList[7].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item @click="addFastSpace" class="action">空格</van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getSpecialList[3].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getSpecialList[3].place,getSpecialList[3].name)" class="keybord-bet-item">
                            <span>{{getSpecialList[3].name}}</span>
                            <span>{{getSpecialList[3].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getSpecialList[4].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getSpecialList[4].place,getSpecialList[4].name)" class="keybord-bet-item">
                            <span>{{getSpecialList[4].name}}</span>
                            <span>{{getSpecialList[4].odds.substring(0,5)}}</span>
                        </van-grid-item>
                        <van-grid-item :class="fast_bet_arr.indexOf(getSpecialList[5].place) !==-1 ? 'keybord-bet-item item-active' :'keybord-bet-item'" @click="fastBet(getSpecialList[5].place,getSpecialList[5].name)" class="keybord-bet-item">
                            <span>{{getSpecialList[5].name}}</span>
                            <span>{{getSpecialList[5].odds.substring(0,5)}}</span>
                        </van-grid-item>

                        <van-grid-item @click="clearFastBet" class="action">清空</van-grid-item>
                    </van-grid>
                </van-action-sheet>
            </div>-->
            <div class="button-item-input">
                <van-field @focus="betMethod(1)" placeholder="手动下注(支持:组合/特殊)" readonly v-model="betValue" />
            </div>
            <div class="button-item">
                <van-button @click="typeBet" class="fast-t" color="white" size="small">发送</van-button>
                <!-- <van-button color="#c8cdd0" size="small" type="info" v-else>封盘中</van-button> -->
            </div>
        </div>

        <chat-window :lotto="this" ref="chat_window" />

        <betConfirm ref="bet_confirm" />
        <right-bar @squint="squint" />
        <scroll-notice />
    </div>
</template>
<script>
import scrollNotice from './scroll-notice'
import lottoNavBar from '../navBar/index'
import lottoChatHeader from '../chatHeader'
import lottoChatSquint from '../chatSquint'
import winCode from './winCode'
import openLog from '../open-log'
import { Notify } from 'vant'
import betConfirm from '../tra/bet-confirm'
import chatWindow from '../chat-window'
import rightBar from './right-bar'
import { Dialog } from 'vant'
export default {
    name: 'chatLottoPage',
    components: {
        lottoNavBar,
        lottoChatHeader,
        winCode,
        openLog,
        betConfirm,
        chatWindow,
        rightBar,
        lottoChatSquint,
        scrollNotice,
    },
    data() {
        return {
            stop: false,
            stop_bet: true,
            currentMode: 1,
            currentTrendMode: 1,
            lotto_name: '',
            chat_open_log: true,
            room_id: 0,
            show_open_log: false,
            show_open_dom: false,
            has_load: false,
            pre_amount: '',
            close_bet: {
                current: null,
                last: null,
                visible: false,
            },
            newest_past: 0,
            float_odds: {},
            amount: '',
            config: {},
            checked: {},
            fast_checked: '',
            bet_current: {
                id: null,
            },
            bet_newest: {},
            bet_new_one: {
                id: null,
            },
            open_last: [],
            was_bet: {},
            playing: '',
            loading: false,
            chips_rate: 1,
            chips_list: [
                {
                    name: '50',
                    value: 50,
                    // img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/chip_1.png',
                    is_choose: false,
                },
                {
                    name: '100',
                    value: 100,

                    is_choose: true,
                },
                {
                    name: '200',
                    value: 200,

                    is_choose: false,
                },
                {
                    name: '500',
                    value: 500,

                    is_choose: false,
                },
                {
                    name: '1000',
                    value: 1000,

                    is_choose: false,
                },
                // {
                //     name: '2000',
                //     value: 2000,

                //     is_choose: false,
                // },
                {
                    name: '梭',
                    value: null,

                    is_choose: false,
                },
            ],
            place_extend: {},
            current_tab: 'open_log',
            user_odds: {},
            trend_open_last: [],
            bos_line: 0,
            sod_line: 0,
            current_chip: 0, //当前筹码
            bet_total_count: 0,
            bet_total: 0,
            bet_list: [],
            betValue: '',
            betShow1: false,
            betShow2: false,
            active: 0,
            // betItems: [{ text: '大小单双' }, { text: '猜数字' }, { text: '特殊模式' }, { text: '查回' }],
            betItems: [{ text: '查回' }, { text: '大小' }, { text: '特码' }, { text: '形态' }],
            fast_bet_arr: [],
            trend_show: false,
            // allow_scroll: false,
            lotto_history: [],
            has_bet_str: '',
            show_squint: false,
        }
    },
    created() {
        if (this.$store.state.config.lotto_items[this.$route.params.name] == undefined) {
            this.$router.push('/')
        }

        if (this.$store.state.config.play_types['room' + this.$route.params.room_id].is_open === false) {
            this.$router.push('/')
        }

        if (this.$route.params.room_id > 8) {
            this.$router.push('/')
        }
        // var api = '/lotto/' + this.$route.params.name + '/chat-open'
        // let param = {}

        // param.lotto_id = 2666282
        // param.room = 1
        // param.type = 'ca28'
        // this.$get(api, param).then((res) => {
        //     if (res.code !== 200) return false
        // })
        // return
        // this.$store.state.lotto.bet_stop = true
        // console.log(this.$store.state.lotto)
        // console.log(this.$store.state.lotto.stop_bet.length)
        this.lotto_name = this.$route.params.name
        this.room_id = parseInt(this.$route.params.room_id)
        // this.getUnited(true, true)
        this.playing = 'room' + this.room_id

        setInterval(() => {
            this.newest_past += 1
        }, 1000)
    },

    watch: {
        stop(val) {
            // if (val) {
            //     this.addInfo(4)
            // }
        },
        room_id(oldVal, newVal) {
            if (oldVal !== newVal) {
                // this.$store.state.lotto.stop_bet = true

                this.getUnited(true, true)
                this.checked = {}
            }
        },
        bet_current(oldVal, newVal) {
            if (oldVal.id != null && oldVal.id !== newVal.id) {
                this.stop = false
            }
        },
        // open_last() {
        //     if (open_last[0].win_extend) {
        //         this.data = this.lotto.open_last[1]
        //     } else {
        //         this.data = this.lotto.open_last[0]
        //     }
        // },
    },
    computed: {
        getComboList() {
            let roomId = 'room' + this.room_id
            let comboList = Object.values(this.$store.state.config.play_types[roomId].place).filter((item) => item.position == 1)

            return comboList
        },
        getNumberList() {
            let roomId = 'room' + this.room_id
            let numberList = Object.values(this.$store.state.config.play_types[roomId].place).filter((item) => item.position == 2)

            return numberList
        },
        getSpecialList() {
            let roomId = 'room' + this.room_id
            let specialList = Object.values(this.$store.state.config.play_types[roomId].place).filter((item) => item.position == 3)
            return specialList
        },
        getWalletList() {
            let roomId = 'room' + this.room_id
            let walletList = Object.values(this.$store.state.config.play_types[roomId].place).filter((item) => item.position == 4)
            return walletList
        },
    },
    methods: {
        getUnited(loading = false, chat = false) {
            if (loading == true) this.loading = true
            this.bet_new_one = { id: null }
            var api = '/lotto/' + this.$route.params.name + '/united'
            let param = {}
            param.room = 'room' + this.room_id
            this.$get(api, param).then((res) => {
                this.newest_past = 0
                this.config = res.data.config
                this.loading = false
                this.bet_newest = res.data.bet_newest
                this.open_last = res.data.open_last
                if (chat) {
                    this.playing = 'room' + this.room_id
                    this.$store.state.lotto[this.$route.params.name]['room' + this.room_id] = res.data.lotto_history.filter((item) => item.room == 'room' + this.room_id)
                    // this.lotto_history = res.data.lotto_history.filter((item) => item.room == 'room' + this.room_id)
                }
                this.was_bet = res.data.was_bet
                this.place_extend = res.data.place_extend
                this.float_odds = res.data.float_odds
                this.user_odds = res.data.user_odds
                if (Object.values(res.data.bet_newest).length > 0) {
                    this.bet_new_one = Object.values(res.data.bet_newest)[0]
                }

                if (Object.values(res.data.bet_newest).length === 0) {
                    // this.bet_current = res.data.bet_newest
                } else if (this.has_load === true && res.data.bet_newest.hasOwnProperty(this.bet_current.id)) {
                    let id = parseInt(this.bet_current.id)
                    this.bet_current = res.data.bet_newest[id]
                } else {
                    this.bet_current = Object.values(res.data.bet_newest)[0]
                    if (Object.values(res.data.bet_newest).length > 0) {
                        this.bet_new_one = Object.values(res.data.bet_newest)[0]
                    }
                }
                // this.bos_line = Math.max.apply(Math, this.open_last.map(function (o) { return o.bos_line }));
                // this.sod_line = Math.max.apply(Math, this.open_last.map(function (o) { return o.sod_line }));

                this.has_load = true
            })
        },
        setNextCurrent() {
            this.$store.state.lotto.stop_bet = true
            this.stop_bet = true
            setTimeout(() => {
                let id = this.bet_new_one.id
                this.close_bet.last = id
                id = parseInt(id) + 1 + ''
                this.close_bet.current = id

                if (this.bet_new_one.id === this.bet_current.id) {
                    if (this.bet_newest.hasOwnProperty(id)) {
                        this.bet_current = this.bet_newest[id]
                        this.close_bet.visible = true
                    } else {
                        return this.getUnited(1)
                    }
                }

                this.getUnited()
                // console.log('重新请求')
                this.stop = false

                // console.log(this.stop);
            }, 10000)
        },

        clearTime() {
            //清除定时器
            clearInterval(this.clearTimeSet)
            // console.log(clearInterval(this.clearTimeSet))
        },
        squint() {
            this.show_squint = !this.show_squint
        },
        setNewest() {
            this.bet_current = Object.values(res.data.bet_newest)[0]
        },
        // addInfo(new_add = 3) {},
        // chatOpen(lotto_id) {},
        refresh() {
            this.getUnited()
        },
        getOpenLast() {
            var api = '/lotto/' + this.$route.params.name + '/open-last'
            this.$get(api).then((res) => {
                if (res.code !== 200) return false
                this.open_last = res.data.items
            })
        },
        betMethod(e) {
            this.checked = {}
            if (e == 1) {
                this.betShow1 = true
            } else {
                this.betShow2 = true
            }
        },
        //点击下注
        singleBet(place) {
            if (this.current_chip == 0) {
                this.current_chip = parseInt(this.chips_list.filter((item) => item.is_choose === true)[0]['value'])
            }

            this.$forceUpdate()
            this.current_chip = parseInt(this.current_chip)
            if (this.current_chip == 0) return false
            let room_id = this.$route.params.room_id
            if (place.indexOf('_check') != -1 || place.indexOf('_return') != -1) {
                this.checked = {}
            } else {
                if (this.checked['room' + room_id + '_check'] != undefined) {
                    delete this.checked['room' + room_id + '_check']
                }
                if (this.checked['room' + room_id + '_return'] != undefined) {
                    delete this.checked['room' + room_id + '_return']
                }
            }

            if (this.checked[place] !== undefined) {
                //从已投对象中删除该投注
                delete this.checked[place]
            } else {
                // if (place.indexOf('00') != -1 || place.indexOf('27') != -1) {
                //     if (this.current_chip > 1000) {
                //         Notify('特殊数字 00/27 最多下注1000')
                //         return
                //     }
                // }
                // if (place.indexOf('01') != -1 || place.indexOf('26') != -1) {
                //     if (this.current_chip > 2000) {
                //         Notify('特殊数字 01/26 最多下注2000')
                //         return
                //     }
                // }
                // if (place.indexOf('02') != -1 || place.indexOf('25') != -1) {
                //     if (this.current_chip > 5000) {
                //         Notify('特殊数字 02/25 最多下注5000')
                //         return
                //     }
                // }
                // if (place.indexOf('number') != -1) {
                //     let betNum = place.substring(place.length - 2)
                //     if (parseInt(betNum) > 2 || parseInt(betNum) < 25) {
                //         if (this.current_chip > 10000) {
                //             Notify('数字 03-24 最多下注10000')
                //             return
                //         }
                //     }
                // }
                // if (place.indexOf('duizi') != -1) {
                //     if (this.current_chip > 30000) {
                //         Notify('对子 最多下注3万元')
                //         return
                //     }
                // }

                // if (place.indexOf('sunzi') != -1) {
                //     if (this.current_chip > 10000) {
                //         Notify('顺子 最多下注1万元')
                //         return
                //     }
                // }
                // if (place.indexOf('baozi') != -1) {
                //     if (this.current_chip > 2000) {
                //         Notify('豹子 最多下注2000元')
                //         return
                //     }
                // }

                // if (place.indexOf('long') != -1 || place.indexOf('hu') != -1 || place.indexOf('bao') != -1) {
                //     if (this.current_chip > 10000) {
                //         Notify('龙/虎/豹 最多下注1万元')
                //         return
                //     }
                // }
                this.checked[place] = this.current_chip
            }
            // this.has_bet_str = ''

            // //组装has_bet_str
            // let keys = Object.keys(this.checked)
            // this.$store.state.config.play_types['room' + this.$route.params.room_id].place.forEach((item, index) => {
            //     keys.forEach((ele, key) => {
            //         if (ele == item.place) {
            //             let data = {}
            //             data.name = item.name
            //             this.has_bet_str += data.name + ','
            //         }
            //     })
            // })
            // this.has_bet_str = this.has_bet_str.slice(0, this.has_bet_str.length - 1) + ':' + this.current_chip
            // console.log(this.has_bet_str)
        },
        //选择筹码
        chooseChip(chip_index, chip_val) {
            if (chip_val == null) chip_val = parseFloat(this.$store.state.user.wallet.balance)
            if (this.chips_list[chip_index].is_choose === false) {
                this.chips_list[chip_index].is_choose = true
                this.chips_list.forEach((element, index) => {
                    if (chip_index != index) {
                        this.chips_list[index].is_choose = false
                    }
                })
                this.current_chip = chip_val
            } else {
                this.chips_list[chip_index].is_choose = false
                this.current_chip = 0
            }

            let checked_length = Object.keys(this.checked)
            if (checked_length != 0) {
                for (let key in this.checked) {
                    this.checked[key] = this.current_chip
                }

                this.has_bet_str = ''

                //组装has_bet_str

                this.$store.state.config.play_types['room' + this.$route.params.room_id].place.forEach((item, index) => {
                    checked_length.forEach((ele, key) => {
                        if (ele == item.place) {
                            let data = {}
                            data.name = item.name
                            this.has_bet_str += data.name + ','
                        }
                    })
                })
                this.has_bet_str = this.has_bet_str.slice(0, this.has_bet_str.length - 1) + ':' + this.current_chip
            }
        },
        //清空投注记录
        clearBet() {
            this.checked = {}
            this.bet_list = []
            this.has_bet_str = ''
        },
        isMoney(s) {
            var regu = /((^[1-9]\d*)|^0)(\.\d{0,3}){0,1}$/
            var re = new RegExp(regu)
            if (re.test(s)) {
                return true
            } else {
                return false
            }
        },
        //预投注
        betConfirmPre() {
            if (!this.bet_current.id) {
                return Notify('当前暂无最新一期 请稍后再试')
            }

            let limit = 0
            let customize_amount = false

            // if (this.has_bet_str != '') {
            let is_money = this.isMoney(this.has_bet_str)
            if (!is_money) {
                return Notify('金额格式不合法')
            }
            customize_amount = true
            // }

            let keys = Object.keys(this.checked)
            let values = Object.values(this.checked)

            let total = 0
            let count = 0
            let dxdsTotal = 0
            let zuheTotal = 0
            let zuheCount = 0
            let jzTotal = 0
            let z_27 = 0
            let o_26 = 0
            let t_25 = 0
            let o_num = 0
            let duizi = 0
            let sunzi = 0
            let baozi = 0
            let lhb = 0
            let key_13_14 = 0
            let number_count = 0
            keys.forEach((key) => {
                // let value = customize_amount ? this.has_bet_str : this.checked[key]
                let value = this.has_bet_str
                if (value === null || value <= 0) return false
                if (value > 0) count += 1
                total += parseFloat(value)
                //大小单双总和
                if (key.indexOf('big') != -1 || key.indexOf('sml') != -1 || key.indexOf('sig') != -1 || key.indexOf('dob') != -1) {
                    dxdsTotal += parseFloat(value)
                }
                //组合 总和
                if (key.indexOf('bdo') != -1 || key.indexOf('bsg') != -1 || key.indexOf('sdo') != -1 || key.indexOf('ssg') != -1) {
                    zuheTotal += parseFloat(value)
                    zuheCount += 1
                }
                //极值和
                if (key.indexOf('xsm') != -1 || key.indexOf('xbg') != -1) {
                    jzTotal += parseFloat(value)
                }
                //0/27
                if (key.indexOf('00') != -1 || key.indexOf('27') != -1) {
                    z_27 += parseFloat(value)
                    number_count++
                }
                if (key.indexOf('01') != -1 || key.indexOf('26') != -1) {
                    o_26 += parseFloat(value)
                    number_count++
                }
                if (key.indexOf('02') != -1 || key.indexOf('25') != -1) {
                    t_25 += parseFloat(value)
                    number_count++
                }
                if (key.indexOf('duizi') != -1) {
                    duizi += parseFloat(value)
                }
                if (key.indexOf('sunzi') != -1) {
                    sunzi += parseFloat(value)
                }
                if (key.indexOf('baozi') != -1) {
                    baozi += parseFloat(value)
                }
                if (key.indexOf('long') != -1 || key.indexOf('hu') != -1 || key.indexOf('bao') != -1) {
                    lhb += parseFloat(value)
                }
                if (key.indexOf('13') != -1) {
                    key_13_14 += 1
                }
                if (key.indexOf('14') != -1) {
                    key_13_14 += 1
                }

                for (let i = 3; i <= 24; i++) {
                    if (parseFloat(key.split('_')[1]) == i) {
                        o_num += parseFloat(value)
                        number_count++
                    }
                }
            })

            if (count == 0) {
                return Notify('请选择投注内容')
            }

            if (number_count > 4) {
                return Notify('单期最多下注4个数字')
            }

            if (zuheCount >= 2 && key_13_14 > 0) {
                return Notify('单期不能同时下注组合和13、14')
            }
            if (zuheCount > 3) {
                return Notify('单期组合不能超过3个')
            }

            let roomId = 'room' + this.room_id
            let bet_limit = this.$store.state.config.play_types[roomId]['bet_limit']
            // console.log(bet_limit)
            if (parseFloat(total) > parseFloat(bet_limit.total)) {
                return Notify('最高投注' + bet_limit.total + '元')
            }
            if (parseFloat(dxdsTotal) > parseFloat(bet_limit.dxds)) {
                return Notify('大小单双最高投注' + bet_limit.dxds + '元')
            }
            if (parseFloat(zuheTotal) > parseFloat(bet_limit.combo)) {
                return Notify('组合最高投注' + bet_limit.combo + '元')
            }
            if (parseFloat(jzTotal) > parseFloat(bet_limit.jdjx)) {
                return Notify('极值最高投注' + bet_limit.jdjx + '元')
            }
            if (parseFloat(z_27) > parseFloat(bet_limit.z_27)) {
                return Notify('00/27最高投注' + bet_limit.z_27 + '元')
            }
            if (parseFloat(o_26) > parseFloat(bet_limit.o_26)) {
                return Notify('01/26最高投注' + bet_limit.o_26 + '元')
            }
            if (parseFloat(t_25) > parseFloat(bet_limit.t_25)) {
                return Notify('02/25最高投注' + bet_limit.t_25 + '元')
            }
            if (parseFloat(o_num) > parseFloat(bet_limit.o_num)) {
                return Notify('其他数字最高投注' + bet_limit.o_num + '元')
            }
            if (parseFloat(duizi) > parseFloat(bet_limit.duizi)) {
                return Notify('对子最高投注' + bet_limit.duizi + '元')
            }
            if (parseFloat(sunzi) > parseFloat(bet_limit.sunzi)) {
                return Notify('顺子最高投注' + bet_limit.sunzi + '元')
            }
            if (parseFloat(baozi) > parseFloat(bet_limit.baozi)) {
                return Notify('豹子最高投注' + bet_limit.baozi + '元')
            }
            if (parseFloat(lhb) > parseFloat(bet_limit.lhb)) {
                return Notify('龙虎豹最高投注' + bet_limit.lhb + '元')
            }

            this.getComboList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = this.has_bet_str)
                        // ;(data.name = item.name), (data.amount = customize_amount ? this.has_bet_str : values[key])
                        this.bet_list.push(data)
                    }
                })
            })

            this.getNumberList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = this.has_bet_str)
                        // ;(data.name = item.name), (data.amount = customize_amount ? this.has_bet_str : values[key])
                        this.bet_list.push(data)
                    }
                })
            })
            this.getSpecialList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = this.has_bet_str)
                        // ;(data.name = item.name), (data.amount = customize_amount ? this.has_bet_str : values[key])
                        this.bet_list.push(data)
                    }
                })
            })
            this.getWalletList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = this.has_bet_str)
                        // ;(data.name = item.name), (data.amount = customize_amount ? this.has_bet_str : values[key])
                        this.bet_list.push(data)
                    }
                })
            })
            let that = this
            Object.keys(this.checked).forEach(function (index) {
                // console.log('key=', index, 'value=', person[index]);
                that.checked[index] = that.has_bet_str
            })
            this.bet_total_count = count
            this.bet_total = total
            this.$refs.bet_confirm.visible = true
            this.$refs.bet_confirm.overlay = true
        },
        betConfirmfinal() {
            this.$toast.loading({
                message: null,
                className: 'bg-none',
                loadingType: 'spinner',
                duration: 0,
                getContainer: '#bet-confirm',
            })

            let api = '/lotto/' + this.$route.params.name + '/bet-basic'

            let params = {
                id: this.bet_current.id,
                checked: this.checked,
                total: this.bet_total,
                play_type: 'room' + this.room_id,
                // chips_rate: this.chips_rate
            }
            // console.log(params); return;
            this.$post(api, params, false, false).then((res) => {
                this.$refs.bet_confirm.overlay = false
                this.$toast.clear()

                if (res.code === 200) {
                    this.was_bet = res.data.was_bet
                    this.float_odds = res.data.float_odds
                    this.clearBet()
                    this.clearFastBet()
                    this.betValue = ''
                    this.$refs.bet_confirm.overlay = false
                    // this.lotto_history.push(res.data.bet_data)
                    // if (this.$store.state.user.option.bet_chat) {
                    //   this.$refs.chat_window.show_bet = false
                    // }
                    this.checked = {}
                    this.$refs.bet_confirm.visible = false
                    Notify({ type: 'success', message: res.message })
                    this.betShow1 = false
                } else {
                    this.bet_list = []

                    console.log('失败')
                    var that = this
                    this.$dialog({
                        title: 'ERROR',
                        message: res.message,
                        className: 'warning',
                        overlay: false,
                        lockScroll: false,
                    })
                }
            })
        },
        scrollLeft() {
            let dom = document.getElementById('number-content')
            dom.scrollLeft -= dom.clientWidth
        },
        scrollRight() {
            let dom = document.getElementById('number-content')
            dom.scrollLeft += dom.clientWidth
        },
        fastChooseChip(e) {
            this.fast_checked += e
        },
        cutFastBet() {
            let arr = this.fast_checked.split(' ')

            if (arr.length < 2) {
                this.checked = {}
                this.fast_checked = ''
                this.fast_bet_arr = []
            } else {
                arr.pop()
                this.fast_checked = arr.join(' ')
                this.fast_bet_arr.pop()
            }
        },
        clearFastBet() {
            this.checked = {}
            this.fast_checked = ''
            this.fast_bet_arr = []
        },
        fastNumberBet(place, name) {
            this.fast_checked += name + '数字-'
            this.fast_bet_arr.push(place)
        },
        fastBet(place, name) {
            this.fast_checked += name + '-'
            this.fast_bet_arr.push(place)
        },
        addFastSpace() {
            this.fast_checked += ' '
        },
        fastPreConfirm() {
            if (this.fast_checked == '') {
                return Notify('请选择下注类型！')
            }
            let fast_checked_arr = this.fast_checked.split(' ')

            if (this.fast_bet_arr.length != fast_checked_arr.length) {
                return Notify('下注格式不正确,请重新选择')
            }
            for (let i = 0; i < fast_checked_arr.length; i++) {
                let bet_value = fast_checked_arr[i].split('-')

                if (parseInt(bet_value) != NaN) {
                    this.checked[this.fast_bet_arr[i]] = bet_value[1]
                } else {
                    return Notify('下注格式不正确,请重新选择')
                }
            }
            // if (this.$store.state.user.info.agent.status === true) {
            //     return Notify('您为代理用户 不能参与投注')
            // }

            if (!this.bet_current.id) {
                return Notify('当前暂无最新一期 请稍后再试')
            }
            let limit = 0
            //room7_bdo: 100, room7_bsg: 100, room7_sdo: 100, room7_ssg: 100
            // if (this.room_id == 7 || this.room_id == 8) {
            //判断组合
            for (var val in this.checked) {
                if (val.indexOf('bsg') != -1) {
                    limit++
                }
                if (val.indexOf('bdo') != -1) {
                    limit++
                }
                if (val.indexOf('sdo') != -1) {
                    limit++
                }
                if (val.indexOf('ssg') != -1) {
                    limit++
                }
            }
            // }
            if (limit > 3) {
                return Notify('最多只能下注2个组合')
            }

            let keys = Object.keys(this.checked)
            let values = Object.values(this.checked)

            let total = 0
            let count = 0
            let dxdsTotal = 0
            let zuheTotal = 0
            let jzTotal = 0
            let z_27 = 0
            let o_26 = 0
            let t_25 = 0
            let o_num = 0
            let duizi = 0
            let sunzi = 0
            let baozi = 0
            let lhb = 0
            let number_count = 0
            keys.forEach((key) => {
                let value = this.checked[key]
                if (value === null || value <= 0) return false
                if (value > 0) count += 1
                total += parseFloat(value)
                //大小单双总和
                if (key.indexOf('big') != -1 || key.indexOf('sml') != -1 || key.indexOf('sig') != -1 || key.indexOf('dob') != -1) {
                    dxdsTotal += parseFloat(value)
                }
                //组合 总和
                if (key.indexOf('bdo') != -1 || key.indexOf('bsg') != -1 || key.indexOf('sdo') != -1 || key.indexOf('ssg') != -1) {
                    zuheTotal += parseFloat(value)
                }
                //极值和
                if (key.indexOf('xsm') != -1 || key.indexOf('xbg') != -1) {
                    jzTotal += parseFloat(value)
                }
                //0/27
                if (key.indexOf('00') != -1 || key.indexOf('27') != -1) {
                    z_27 += parseFloat(value)
                    number_count++
                }
                if (key.indexOf('01') != -1 || key.indexOf('26') != -1) {
                    o_26 += parseFloat(value)
                    number_count++
                }
                if (key.indexOf('02') != -1 || key.indexOf('25') != -1) {
                    t_25 += parseFloat(value)
                    number_count++
                }
                if (key.indexOf('duizi') != -1) {
                    duizi += parseFloat(value)
                }
                if (key.indexOf('sunzi') != -1) {
                    sunzi += parseFloat(value)
                }
                if (key.indexOf('baozi') != -1) {
                    baozi += parseFloat(value)
                }
                if (key.indexOf('long') != -1 || key.indexOf('hu') != -1 || key.indexOf('bao') != -1) {
                    lhb += parseFloat(value)
                }

                for (let i = 3; i <= 24; i++) {
                    if (parseFloat(key.split('_')[1]) == i) {
                        o_num += parseFloat(value)
                        number_count++
                    }
                }
            })

            if (count == 0) {
                return Notify('请选择投注内容')
            }
            if (number_count > 4) {
                return Notify('单句最多下4个数字')
            }
            let roomId = 'room' + this.room_id
            let bet_limit = this.$store.state.config.play_types[roomId]['bet_limit']

            if (parseFloat(total) > parseFloat(bet_limit.total)) {
                return Notify('最高投注' + bet_limit.total + '元')
            }
            if (parseFloat(dxdsTotal) > parseFloat(bet_limit.dxds)) {
                return Notify('大小单双最高投注' + bet_limit.dxds + '元')
            }
            if (parseFloat(zuheTotal) > parseFloat(bet_limit.combo)) {
                return Notify('组合最高投注' + bet_limit.combo + '元')
            }
            if (parseFloat(jzTotal) > parseFloat(bet_limit.jdjx)) {
                return Notify('极值最高投注' + bet_limit.jdjx + '元')
            }
            if (parseFloat(z_27) > parseFloat(bet_limit.z_27)) {
                return Notify('00/27最高投注' + bet_limit.z_27 + '元')
            }
            if (parseFloat(o_26) > parseFloat(bet_limit.o_26)) {
                return Notify('01/26最高投注' + bet_limit.o_26 + '元')
            }
            if (parseFloat(t_25) > parseFloat(bet_limit.t_25)) {
                return Notify('02/25最高投注' + bet_limit.t_25 + '元')
            }
            if (parseFloat(o_num) > parseFloat(bet_limit.o_num)) {
                return Notify('其他数字最高投注' + bet_limit.o_num + '元')
            }
            if (parseFloat(duizi) > parseFloat(bet_limit.duizi)) {
                return Notify('对子最高投注' + bet_limit.duizi + '元')
            }
            if (parseFloat(sunzi) > parseFloat(bet_limit.sunzi)) {
                return Notify('顺子最高投注' + bet_limit.sunzi + '元')
            }
            if (parseFloat(baozi) > parseFloat(bet_limit.baozi)) {
                return Notify('豹子最高投注' + bet_limit.baozi + '元')
            }
            if (parseFloat(lhb) > parseFloat(bet_limit.lhb)) {
                return Notify('龙虎豹最高投注' + bet_limit.lhb + '元')
            }

            this.getComboList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = values[key])
                        this.bet_list.push(data)
                    }
                })
            })

            this.getNumberList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = values[key])
                        this.bet_list.push(data)
                    }
                })
            })
            this.getSpecialList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = values[key])
                        this.bet_list.push(data)
                    }
                })
            })
            this.getWalletList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = values[key])
                        this.bet_list.push(data)
                    }
                })
            })

            this.bet_total_count = count
            this.bet_total = total
            this.$refs.bet_confirm.visible = true
            this.$refs.bet_confirm.overlay = true
        },
        cancelFastBet() {
            // this.betShow2 = false
            this.checked['room' + this.room_id + '_cancel'] = 0
            Dialog.confirm({
                title: '是否取消投注',
                message: '点击确认取消本期所有投注',
            })
                .then(() => {
                    this.$refs.bet_confirm.overlay = true
                    this.betConfirmfinal()
                })
                .catch(() => {
                    // on cancel
                    // this.clearBet()
                    delete this.checked['room' + this.room_id + '_cancel']
                })

            return
        },
        typeBet() {
            if (this.betValue == '') {
                Notify('请输入投注内容！')
                return
            }
            let type_bet_arr = this.betValue.split(' ')
            let regPlace = {
                大: 'big',
                小: 'small',
                单: 'sig',
                双: 'dob',
                大双: 'bdo',
                大单: 'bsg',
                小双: 'sdo',
                小单: 'ssg',
                极大: 'xbg',
                极小: 'xsm',
                龙: 'long',
                虎: 'hu',
                豹: 'bao',
                豹子: 'baozi',
                顺子: 'sunzi',
                对子: 'duizi',
            }
            if (this.betValue.indexOf('取消') != -1 && this.betValue.replace('取消', '') != '') {
                Notify('格式不正确,请输入“取消”')
                return
            } else if (this.betValue.indexOf('取消') != -1 && this.betValue.replace('取消', '') == '') {
                this.checked['room' + this.room_id + '_cancel'] = 0
                Dialog.confirm({
                    title: '是否取消投注',
                    message: '点击确认取消本期所有投注',
                })
                    .then(() => {
                        this.$refs.bet_confirm.overlay = true
                        this.betConfirmfinal()
                    })
                    .catch(() => {
                        // on cancel
                    })

                return
            }

            for (let i = 0; i < type_bet_arr.length; i++) {
                var num = type_bet_arr[i].replace(/[^0-9]/gi, '')
                if (num == '') {
                    Notify('格式不正确,请重新输入\n(每注以空格隔开， 例:大88 单:888)')
                    return
                }
                var str = type_bet_arr[i].replace(/[^\u4e00-\u9fa5]/gi, '')
                if (str == '') {
                    Notify('格式不正确,请重新输入\n(每注以空格隔开， 例:大88 单:888)')
                    return
                }
                let reg_place_keys = Object.keys(regPlace)
                let reg_key = reg_place_keys.indexOf(str)
                if (reg_key == -1) {
                    Notify('下注类型有误，请重新输入')
                    return
                }

                let reg_values = Object.values(regPlace)
                let final_bet_place = reg_values[reg_key]

                final_bet_place = 'room' + this.room_id + '_' + final_bet_place

                this.checked[final_bet_place] = num
            }

            if (this.$store.state.user.info.agent.status === true) {
                return Notify('您为代理用户 不能参与投注')
            }

            if (!this.bet_current.id) {
                return Notify('当前暂无最新一期 请稍后再试')
            }
            let limit = 0
            //room7_bdo: 100, room7_bsg: 100, room7_sdo: 100, room7_ssg: 100
            if (this.room_id == 7 || this.room_id == 8) {
                //判断组合
                for (var val in this.checked) {
                    if (val.indexOf('bsg') != -1) {
                        limit++
                    }
                    if (val.indexOf('bdo') != -1) {
                        limit++
                    }
                    if (val.indexOf('sdo') != -1) {
                        limit++
                    }
                    if (val.indexOf('ssg') != -1) {
                        limit++
                    }
                }
            }
            if (limit > 3) {
                return Notify('最多只能下注3个组合')
            }

            let keys = Object.keys(this.checked)
            let values = Object.values(this.checked)

            let total = 0
            let count = 0
            let dxdsTotal = 0
            let zuheTotal = 0
            let jzTotal = 0
            let qtNumberTotal = 0
            keys.forEach((key) => {
                let value = this.checked[key]
                if (value === null || value <= 0) return false
                if (value > 0) count += 1
                total += parseFloat(value)
                //大小单双总和
                if (key.indexOf('big') != -1 || key.indexOf('sml') != -1 || key.indexOf('sig') != -1 || key.indexOf('dob') != -1) {
                    dxdsTotal += parseFloat(value)
                }
                //组合 总和
                if (key.indexOf('bdo') != -1 || key.indexOf('bsg') != -1 || key.indexOf('sdo') != -1 || key.indexOf('ssg') != -1) {
                    zuheTotal += parseFloat(value)
                }
                //极值和

                if (key.indexOf('xsm') != -1 || key.indexOf('xbg') != -1) {
                    jzTotal += parseFloat(value)
                }
            })

            if (count == 0) {
                return Notify('请选择投注内容')
            }
            if (parseFloat(total) < 10) {
                return Notify('单次下注最低10元 请重新投注')
            }
            if (dxdsTotal > 100000) {
                return Notify('大小单双最高下注10万元 请重新投注')
            }
            if (zuheTotal > 30000) {
                return Notify('组合最高下注3万元 请重新投注')
            }
            if (jzTotal > 10000) {
                return Notify('极值最高下注1万元 请重新投注')
            }
            if (parseFloat(total) > 300000) {
                return Notify('单次下注最高300000元 请重新投注')
            }

            this.getComboList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = values[key])
                        this.bet_list.push(data)
                    }
                })
            })

            this.getNumberList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = values[key])
                        this.bet_list.push(data)
                    }
                })
            })
            this.getSpecialList.forEach((item, index) => {
                keys.forEach((ele, key) => {
                    if (ele == item.place) {
                        let data = {}
                        ;(data.name = item.name), (data.amount = values[key])
                        this.bet_list.push(data)
                    }
                })
            })

            this.bet_total_count = count
            this.bet_total = total
            this.$refs.bet_confirm.visible = true
            this.$refs.bet_confirm.overlay = true
        },
    },
    // unmounted() {

    // },
    beforeDestroy() {
        //页面关闭时清除定时器

        clearInterval(this.clearTimeSet)
    },
}
</script>
<style lang="less" scoped>
.button-content {
    // border-top: 1px solid #dcdcdc;
    box-shadow: 0px -2px 3px 1px #d3d3d3;
    padding-top: 5px;
    padding-bottom: 5px;
    position: fixed;
    z-index: 30;
    bottom: 0px;
    display: flex;
    width: 100%;
    justify-content: center;
    max-width: 768px;
    padding-left: 5px;
    background: whitesmoke;
    .button-item {
        margin: 0 auto;
        padding-bottom: 5px;
        padding-bottom: env(safe-area-inset-bottom);
        flex: 1;
        margin-left: 1px;
        /deep/.van-button {
            margin: 0 auto;
            display: inline-block;
            font-size: 14px;
            border-radius: 5px;
            width: 96%;
        }
        .fast-t {
            color: #535356 !important;
        }
        .content-2 {
            .info-area {
                display: flex;
                width: 96%;
                margin: 0 auto;
                height: 30px;
                margin-bottom: 10px;
                /deep/.van-field {
                    flex: 6;
                    margin-right: 5px;
                    line-height: 15px !important;
                    padding: 10px 10px;
                }
                /deep/.van-button {
                    flex: 1;
                }
            }
            .number-area {
                width: 100%;
                display: flex;
                background: #d1d3dc;
                justify-content: space-between;
                .left {
                    text-align: center;
                    flex: 1;
                    padding-top: 8px;
                }
                .right {
                    flex: 1;
                    padding-top: 8px;
                    text-align: center;
                }
                .middle {
                    flex: 8;

                    overflow-x: scroll;
                    overflow-y: hidden;
                    white-space: nowrap;
                    scroll-behavior: smooth;
                    div {
                        display: inline-block;
                        box-sizing: border-box;
                        width: 46px;
                        height: 35px;
                        border-radius: 4px;
                        margin: 0 2px;
                        box-shadow: 0px 1px 0px 0px #8c8b8e;
                        text-align: center;
                        color: #343a52;
                        background: #a8b0bd;
                        span {
                            display: block;
                        }
                        span:nth-child(1) {
                            margin-top: 4px;
                        }
                        span:nth-child(2) {
                            color: #6d717c;
                            font-size: 12px;
                        }
                    }
                    .item-active {
                        background: #c59d6a;
                        color: white;
                        span:nth-child(2) {
                            color: white;
                        }
                    }
                }

                .middle::-webkit-scrollbar {
                    display: none;
                }
            }
        }
    }
    .button-item-input {
        flex: 2.8;

        /deep/.van-cell {
            padding: 4px 12px !important;
            font-size: 12px;
            border-radius: 3px;
        }
    }
    .content-1 {
        padding: 10px 10px 0%;
        overflow: hidden;
        height: 100% !important;
        .dxds {
            display: flex;
            flex-wrap: wrap;
            .item {
                width: 44%;
                margin: 3%;
                background: #f3f3f6;
                height: 40px;
                line-height: 40px;
                color: #a2a6ba;
                display: flex;
                justify-content: space-around;
                border-radius: 4px;
                span {
                    font-size: 12px;
                    display: inline-block;
                }
            }
            .active {
                background: #4f8dfe;
                color: white;
            }
        }
    }
    /deep/.van-tree-select {
        .van-tree-select__nav {
            flex: 0.7;
            // height: 210px;
        }
        .van-tree-select__content {
            height: 300px;
            margin-left: 10px;
        }
    }
    .tra-bet {
        // height: 75% !important;
        padding-bottom: 15px;
        border-radius: unset;
    }
    .fast-bet {
        // height: 65% !important;
        padding-bottom: 15px;
        background: #d1d3dc;
        .keybord-bet {
            font-size: 12px;
            background: #d1d3dc;
            width: 98%;
            margin: 0 auto;
            margin-top: 5px;
            /deep/.van-button {
                font-size: 12px;
            }
            /deep/.van-grid-item {
                height: 12vw;
                padding: 3px;
            }
            .action {
                /deep/.van-grid-item__content {
                    background: #bcc0c9;
                    box-shadow: 0px 1px 0px 0px #8c8b8e;
                    border-radius: 4px;
                }
            }
            .keybord-bet-item {
                /deep/.van-grid-item__content {
                    // border: 1px solid #c59d6a;

                    border-radius: 3px;
                    box-shadow: 0px 1px 0px 0px #8c8b8e;

                    color: #343a52;
                    background: #a8b0bd;
                }
            }
            .item-active {
                /deep/.van-grid-item__content {
                    background: #c59d6a;
                    color: white;
                    border: none;
                }
            }
            .keybord-bet-confirm {
                /deep/.van-grid-item__content {
                    background: red;
                    color: white;
                    border-radius: 3px;
                }
            }
            .number {
                color: white;

                /deep/.van-grid-item__content {
                    border-radius: 3px;
                    background: white !important;
                    color: black;
                    box-shadow: 0px 1px 0px 0px #8c8b8e;
                }
            }
        }
    }

    .combo-area {
        /deep/.van-grid-item {
            padding: 5px 0;

            /deep/.van-grid-item__content {
                // background: grey;
                color: #333333;
                // border: 1px dashed #e08989 !important;
                // -webkit-border: 1px dashed #e08989 !important;
                // width: 80%;
                border-radius: 10px;
                padding: 5px 0px;
            }
            span {
                display: inline-block;
            }
        }
        .bet-money {
            height: 20px;
            // display: block;
            color: #c59d6a;
            font-size: 14px;
            width: 50px;
            text-align: center;
        }
    }
    .chips {
        width: 98%;
        height: 2vh;
        margin: 0px auto;
        // background: #f7f7f7;
        /deep/.van-grid-item__content {
            background: transparent;
        }
        .chips-area {
            position: relative;
            span {
                position: absolute;
                top: 40%;
                display: block;
                width: 70%;
                // height: 14px;
                text-align: center;
                padding: 6px 4px;
                font-size: 12px;
                font-weight: bold;
                background: #9cf;
                color: white;
                border-radius: 4px;
            }
            .chosen span {
                background: #4f8dfe;
            }
        }
    }
    .button {
        // width: 96%;
        margin: 0 auto;
        display: flex;
        /deep/.van-field {
            flex: 3;
            line-height: 31px;
            background: #f7f8fa;
            padding: 0px 3px !important;
            border-radius: 5px;
            // margin-left: 10px;
            .van-cell {
                background: #c4c8d2;
                .van-cell__value {
                    padding: 0 5px;
                }
            }
        }
        /deep/.van-button {
            flex: 1;
            font-size: 14px;
            border-radius: 5px;
            margin-left: 5px;
        }
        /deep/.van-button:nth-child(2) {
            border: none;

            // color: red !important;
            // background: linear-gradient(to bottom, #434a57, #292e37);
        }
        /deep/.van-button:nth-child(3) {
            border: none;
            margin-left: 5px;
            // background: #6ddcd3;
            // color: red !important;
        }
    }
}

.bet-history {
    position: fixed;
    width: 100%;
    z-index: -1;
    top: 135px;
    height: 73%;
    background: #f6f6f6;
    display: flex;
}
.chat-content {
    // display: flex;
    // flex-direction: column;
    // width: 100%;
    // height: 100%;
    overflow: hidden;
    // -webkit-box-orient: vertical;
    // -webkit-box-direction: normal;
}
.chat-content::-webkit-scrollbar {
    display: none; /* Chrome Safari */
}
// #app {
//     overflow-y: hidden !important;
// }
</style>