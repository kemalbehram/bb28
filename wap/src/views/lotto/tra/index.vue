<template>
    <div class="tra-content">
        <lottoNavBar :lotto_name="lotto_name" :room_id="room_id" />
        <lottoHeader :lotto="this" class="lotto-header-wrap" />
        <van-count-down :time="bet_new_one.bet_count_down * 1000" @finish="setNextCurrent()" class="display-none" v-if="bet_new_one.id"></van-count-down>
        <open-log :lotto="this" v-if="has_load === true" />
        <div id="lotto-win-table" v-show="show_open_dom === true" />

        <div class="lotto-content">
            <div class="mode-tab">
                <div :class="currentMode == 1 ? 'mode-active' :''" @click="showmode(1)">组合模式</div>
                <div :class="currentMode == 2 ? 'mode-active' :''" @click="showmode(2)">数字模式</div>
                <div :class="currentMode == 3 ? 'mode-active' :''" @click="showmode(3)">特殊模式</div>
            </div>

            <van-grid :border="false" :column-num="2" class="combo-area" v-if="currentMode == 1">
                <van-grid-item :class="item.name == '大双' || item.name =='小双' || item.name=='大单' || item.name=='小单' ? 'small' :''" :key="index" @click="singleBet(item.place)" v-for="(item,index) in getComboList">
                    <h2 :style="checked[item.place] != undefined ? 'color:#f0bb01' : ''">{{item.name}}</h2>
                    <span class="bet-money" v-if="checked[item.place] != undefined">{{checked[item.place] != undefined ? checked[item.place] :0 }}</span>
                    <b class="color-red">{{stop ? '封盘中' :''}}</b>
                    <span class="odds">{{item.odds.substring(0,5)}}</span>
                </van-grid-item>
            </van-grid>

            <van-grid :border="false" :column-num="4" class="number-area" v-if="currentMode == 2">
                <van-grid-item :key="index" @click="singleBet(item.place)" v-for="(item,index) in getNumberList">
                    <h2 :style="checked[item.place] != undefined ? 'color:#f0bb01' : ''">{{item.name}}</h2>
                    <span class="bet-money" v-if="checked[item.place] != undefined">{{checked[item.place] != undefined ? checked[item.place] :0 }}</span>
                    <b class="color-red">{{stop ? '封盘中' :''}}</b>

                    <span class="number-odds">{{item.odds.substring(0,5)}}</span>
                </van-grid-item>
            </van-grid>

            <van-grid :border="false" :column-num="3" class="special-area" v-if="currentMode == 3">
                <van-grid-item :key="index" @click="singleBet(item.place)" v-for="(item,index) in getSpecialList">
                    <h2 :style="checked[item.place] != undefined ? 'color:#f0bb01' : ''">{{item.name}}</h2>
                    <span class="bet-money" v-if="checked[item.place] != undefined">{{checked[item.place] != undefined ? checked[item.place] :0 }}</span>
                    <span class="odds">{{item.odds.substring(0,5)}}</span>
                    <b class="color-red">{{stop ? '封盘中' :''}}</b>
                </van-grid-item>
            </van-grid>

            <div class="chips">
                <van-grid :border="false" :column-num="6" class="chips-area">
                    <van-grid-item :class="item.is_choose ? 'chosen':''" :key="index" @click="chooseChip(index,item.value)" v-for="(item,index) in chips_list">
                        <van-image :src="item.img_url" />
                        <span>{{item.name}}</span>
                    </van-grid-item>
                </van-grid>
            </div>
            <div class="balance-area">
                <div class="balance">余额:{{this.$store.state.user.wallet.balance}}</div>
                <div class="button">
                    <van-button @click="clearBet" size="small">清空</van-button>
                    <van-button :disabled="$store.state.lotto.stop_bet" @click="betConfirmPre" size="small">投注</van-button>
                </div>
            </div>
        </div>

        <div class="trend-tab">
            <div :class="currentTrendMode == 1 ? 'mode-active' :''" @click="showTrendMode(1)">大小</div>
            <div :class="currentTrendMode == 2 ? 'mode-active' :''" @click="showTrendMode(2)">单双</div>
        </div>

        <div class="bos-trend display-flex" v-if="currentTrendMode == 1">
            <div :key="lineindex" :style="{'width': (100/bos_line)+'%'}" class="trend-colunm" v-for="(lineitem,lineindex) in bos_line">
                <div :class="item.win_extend != null && item.win_extend.code_bos == '小' ? 'trend-item green' :'trend-item red'" :key="index" :v-if="item.win_extend !=null && item.bos_line == lineindex" v-for="(item,index) in open_last.filter(element=>element.bos_line == lineitem)">{{item.win_extend != null ? item.win_extend.code_bos : ''}}</div>
            </div>
        </div>

        <div class="sod-trend display-flex" v-else>
            <div :key="lineindex" :style="{'width': (100/sod_line)+'%'}" class="trend-colunm" v-for="(lineitem,lineindex) in sod_line">
                <div :class="item.win_extend != null && item.win_extend.code_sod == '单' ? 'green' :'red'" :key="index" :v-if="item.win_extend !=null && item.sod_line == lineindex" v-for="(item,index) in open_last.filter(element=>element.sod_line == lineitem)">{{item.win_extend != null ? item.win_extend.code_sod : ''}}</div>
            </div>
        </div>

        <bet-log :room="room_id" ref="betLog" />
        <betConfirm ref="bet_confirm" />
    </div>
</template>
<script>
import lottoNavBar from '../navBar/index'
import lottoHeader from '../header'
import openLog from '../open-log'
import betLog from './bet-log'
import { Notify } from 'vant'
import betConfirm from './bet-confirm'
export default {
    name: 'traLottoPage',
    data() {
        return {
            stop: false,
            stop_bet: true,
            currentMode: 1,
            currentTrendMode: 1,
            lotto_name: '',

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
                    name: '10',
                    value: 10,
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/chip_1.png',
                    is_choose: false,
                },
                {
                    name: '100',
                    value: 100,
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/chip_2.png',
                    is_choose: false,
                },
                {
                    name: '500',
                    value: 500,
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/chip_3.png',
                    is_choose: false,
                },
                {
                    name: '1k',
                    value: 1000,
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/chip_4.png',
                    is_choose: false,
                },
                {
                    name: '5k',
                    value: 5000,
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/chip_5.png',
                    is_choose: false,
                },
                {
                    name: '梭',
                    value: null,
                    img_url: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/chip_6.png',
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
            chat_open_log: false,
        }
    },
    created() {
        if (this.$route.params.room_id > 8) {
            this.$router.push('/')
        }
        this.lotto_name = this.$route.params.name
        this.room_id = parseInt(this.$route.params.room_id)
        this.getUnited(true)

        setInterval(() => {
            this.newest_past += 1
        }, 1000)
    },

    components: {
        lottoHeader,
        lottoNavBar,
        openLog,
        betLog,
        betConfirm,
    },
    watch: {
        room_id(oldVal, newVal) {
            if (oldVal !== newVal) {
                this.getUnited(true)
                this.checked = {}
            }
        },
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
    },
    methods: {
        setNextCurrent() {
            console.log('下一期')
            let id = this.bet_new_one.id
            this.close_bet.last = id
            id = parseInt(id) + 1 + ''
            this.close_bet.current = id
            this.$store.state.lotto.stop_bet = true
            if (this.bet_new_one.id === this.bet_current.id) {
                if (this.bet_newest.hasOwnProperty(id)) {
                    this.bet_current = this.bet_newest[id]
                    this.close_bet.visible = true
                } else {
                    return this.getUnited(1)
                }
            }
            this.getUnited()
            console.log('重新请求')
            this.stop = false
        },
        chatOpen(lotto_id) {
            console.log('开奖了')
            this.$store.state.lotto.stop_bet = false
        },
        showmode(e) {
            this.currentMode = e
        },
        showTrendMode(e) {
            console.log(e)
            this.currentTrendMode = e
        },
        setNewest() {
            this.bet_current = Object.values(res.data.bet_newest)[0]
        },

        getUnited(loading = false) {
            if (loading == true) this.loading = true
            this.bet_new_one = { id: null }
            var api = '/lotto/' + this.$route.params.name + '/united?open_last=30'
            this.$get(api).then((res) => {
                this.newest_past = 0
                this.config = res.data.config
                this.loading = false
                this.bet_newest = res.data.bet_newest
                this.open_last = res.data.open_last

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
                this.bos_line = Math.max.apply(
                    Math,
                    this.open_last.map(function (o) {
                        return o.bos_line
                    })
                )
                this.sod_line = Math.max.apply(
                    Math,
                    this.open_last.map(function (o) {
                        return o.sod_line
                    })
                )

                this.has_load = true
            })
        },
        getOpenLast() {
            var api = '/lotto/' + this.$route.params.name + '/open-last?open_last=30'
            this.$get(api).then((res) => {
                if (res.code !== 200) return false
                this.open_last = res.data.items
            })
        },
        //点击下注
        singleBet(place) {
            this.$forceUpdate()

            if (this.current_chip == 0) return false
            if (this.checked[place] !== undefined) {
                // //特殊数字
                // if (place.indexOf('00') != -1 || place.indexOf('27') != -1) {
                //     if (this.checked[place] >= 1000) {
                //         Notify('特殊数字 00/27 最多下注1000')
                //         return
                //     }
                // }
                // if (place.indexOf('01') != -1 || place.indexOf('26') != -1) {
                //     if (this.checked[place] >= 2000) {
                //         Notify('特殊数字 01/26 最多下注2000')
                //         return
                //     }
                // }
                // if (place.indexOf('02') != -1 || place.indexOf('25') != -1) {
                //     if (this.checked[place] >= 5000) {
                //         Notify('特殊数字 02/25 最多下注5000')
                //         return
                //     }
                // }

                // if (place.indexOf('number') != -1) {
                //     let betNum = place.substring(place.length - 2)
                //     if (parseInt(betNum) > 2 || parseInt(betNum) < 25) {
                //         if (this.checked[place] >= 10000) {
                //             Notify('数字 03-24 最多下注10000')
                //             return
                //         }
                //     }
                // }
                // if (place.indexOf('duizi') != -1) {
                //     if (this.checked[place] >= 30000) {
                //         Notify('对子 最多下注3万元')
                //         return
                //     }
                // }

                // if (place.indexOf('sunzi') != -1) {
                //     if (this.checked[place] >= 10000) {
                //         Notify('顺子 最多下注1万元')
                //         return
                //     }
                // }
                // if (place.indexOf('baozi') != -1) {
                //     if (this.checked[place] >= 2000) {
                //         Notify('豹子 最多下注2000元')
                //         return
                //     }
                // }
                // if (place.indexOf('long') != -1 || place.indexOf('hu') != -1 || place.indexOf('bao') != -1) {
                //     if (this.checked[place] >= 10000) {
                //         Notify('龙/虎/豹 最多下注1万元')
                //         return
                //     }
                // }

                this.checked[place] = this.current_chip + this.checked[place]
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
        },
        //选择筹码
        chooseChip(chip_index, chip_val) {
            if (chip_val == null) chip_val = parseFloat(this.$store.state.user.wallet.balance)
            //console.log(parseInt(this.$store.state.user.wallet.balance));
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
        },
        //清空投注记录
        clearBet() {
            this.checked = {}
            this.bet_list = []
        },
        //预投注
        betConfirmPre() {
            // if (this.$store.state.user.info.agent.status === true) {
            //     return Notify('您为代理用户 不能参与投注')
            // }

            if (!this.bet_current.id) {
                return Notify('当前暂无最新一期 请稍后再试')
            }
            let limit = 0
            //let dxds_limit = 0
            //room7_bdo: 100, room7_bsg: 100, room7_sdo: 100, room7_ssg: 100

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
                // if (val.indexOf('big') != -1) {
                //     dxds_limit++
                // }
                // if (val.indexOf('sml') != -1) {
                //     dxds_limit++
                // }
                // if (val.indexOf('sig') != -1) {
                //     dxds_limit++
                // }
                // if (val.indexOf('dob') != -1) {
                //     dxds_limit++
                // }
            }
            //判断大小单双

            if (limit > 3) {
                return Notify('最多只能下注3个组合')
            }
            // if (dxds_limit > 2) {
            //     return Notify('大小单双最多只能下2组')
            // }

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

                for (let i = 0; i <= 27; i++) {
                    if (parseFloat(key.split('_')[1]) == i) {
                        qtNumberTotal += parseFloat(value)
                    }
                }
            })

            if (count == 0) {
                return Notify('请选择投注内容')
            }
            if (this.room_id == 1) {
                if (parseFloat(total) < 50) {
                    return Notify('单次下注最低50元 请重新投注')
                }
            } else if (this.room_id == 2) {
                if (parseFloat(total) < 30) {
                    return Notify('单次下注最低30元 请重新投注')
                }

                if (qtNumberTotal > 10000) {
                    return Notify('数字最高下注1万元 请重新投注')
                }
                if (dxdsTotal > 200000) {
                    return Notify('大小单双最高下注20万元 请重新投注')
                }

                if (jzTotal > 10000) {
                    return Notify('极值最高下注1万元 请重新投注')
                }
            }

            if (zuheTotal > 200000) {
                return Notify('组合最高下注20万元 请重新投注')
            }

            if (parseFloat(total) > 200000) {
                return Notify('单次下注最高20万元 请重新投注')
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
                    this.$refs.bet_confirm.overlay = false
                    // if (this.$store.state.user.option.bet_chat) {
                    //   this.$refs.chat_window.show_bet = false
                    // }
                    Notify({ type: 'success', message: res.message })
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
    },
}
</script>
<style lang="less" scoped>
.tra-content {
    height: 100%;
    background: url(https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/bet_bg.png);
}
.lotto-content {
    padding-top: 102px;
    height: 67vh;
    background: url(https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/bet_bg.png);
    //background-size: 100% 100%;

    .mode-tab {
        display: flex;
        flex-wrap: nowrap;
        justify-content: space-around;
        height: 30px;
        line-height: 30px;
        margin-bottom: 10px;
        cursor: pointer;
        .mode-active {
            background: #30343b;
        }
        div {
            color: #60cdc1;
            background: #414956;
            width: 100%;
            text-align: center;
        }
    }

    .combo-area,
    .number-area,
    .special-area {
        width: 86%;
        height: 43vh;
        margin: 0px auto;
        /deep/.van-grid-item {
            box-sizing: border-box;
            cursor: pointer;
            .van-grid-item__content {
                border: 1px solid #435667;
                background: #2a2f3a;
                color: #6bd9d1;

                position: relative;
                h2 {
                    position: absolute;
                    top: -16%;
                    left: 7%;
                }
                .odds {
                    position: absolute;
                    right: 0;
                    bottom: 0;
                    background: #276b6a;
                    color: white;
                    padding: 2px 8px;
                    border-radius: 12px 0 0 0;
                    // width: 29px;
                }
                .bet-money {
                    height: 20px;
                    display: block;
                    color: #f0bd00;
                    font-size: 14px;
                    width: 50px;
                    text-align: center;
                }
            }
        }
    }
    .combo-area {
        .bet-money {
            position: absolute;
            right: 0;
            top: 29%;
            left: 35%;
        }
    }
    .number-area {
        .number-odds {
            position: absolute;
            right: 0;
            bottom: 0;
            background: #276b6a;
            color: white;
            padding: 1px 5px;
            border-radius: 12px 0 0 0;
            font-size: 12px;
        }
        .bet-money {
            position: absolute;
            right: 0;
            top: 3%;
            font-size: 12px !important;
        }
        b {
            position: absolute;
            right: 0;
            top: 3%;
            font-size: 14px !important;
        }
    }
    .special-area {
        /deep/.van-grid-item {
            .van-grid-item__content {
                h2 {
                    position: absolute;
                    top: -6%;
                    left: 7%;
                }
            }
            .bet-money {
                position: absolute;
                right: 0;
                top: 39%;
                left: 29%;
            }
        }
    }
    .small {
        flex-basis: 25% !important;
    }
    .chips {
        width: 90%;
        height: 10vh;
        margin: 0px auto;
        /deep/.van-grid-item__content {
            background: transparent;
        }
        .chips-area {
            position: relative;
            span {
                position: absolute;
                top: 40%;
                font-size: 12px;
                font-weight: bold;
            }
            .chosen {
                height: 60px;
            }
        }
    }
    .balance-area {
        width: 86%;
        margin: 0 auto;
        display: flex;
        .balance {
            flex: 1;
            color: #f0bb01;
            font-size: 16px;
            padding-top: 6px;
        }
        .button {
            flex: 1;
            /deep/.van-button {
                width: 48%;
                font-size: 14px;
            }
            /deep/.van-button:first-child {
                border: none;
                color: white;
                background: linear-gradient(to bottom, #434a57, #292e37);
            }
            /deep/.van-button:nth-child(2) {
                border: none;
                margin-left: 3px;
                background: #6ddcd3;
            }
        }
    }
}
.trend {
    width: 99%;
    margin: 0 auto;
    /deep/.van-tabs__nav--card {
        margin: 0 0;
        border: none;
        /deep/.van-tab {
            color: white;
            background: #414956;
            border: none;
        }
        /deep/.van-tab--active {
            background: #276b6a;
        }
    }
}

.trend-tab {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-around;
    height: 30px;
    line-height: 30px;

    cursor: pointer;
    .mode-active {
        background: #276b6a;
    }
    div {
        color: white;
        background: #414956;
        width: 100%;
        text-align: center;
    }
}
.bos-trend,
.sod-trend {
    background: #252932;
    flex-direction: row-reverse;
    .trend-colunm {
        .trend-item {
            width: 100%;
            text-align: center;
        }
        .green {
            color: #71e4db;
        }
        .red {
            color: red;
        }
    }
}
</style>