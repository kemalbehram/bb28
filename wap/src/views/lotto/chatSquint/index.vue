<template>
    <div class="lotto-header">
        <div class="lotto-header-item flex-1">
            <count-down :lotto="lotto" @finish="finishCountDown()" class />

            <span v-if="timeCountDown != 10">等待{{timeCountDown}}秒后才能刷新</span>
            <span @click="refresh" class="action" v-else>点击刷新</span>
        </div>
        <div class="lotto-header-item balance font-12">
            <span>第{{data.id}}期开奖</span>
            <div class="squint" id="top">
                <div class="award_box">
                    <div class="award" v-if="showPrize">
                        <div class="chat-item-bet--container">
                            <div class="win-code-extend" v-if="data.win_extend">
                                <span class="win-code-item color-white bg-blue" v-html="data.win_extend.code_arr[0]" />
                                <span>+</span>
                                <span class="win-code-item color-white bg-blue" v-html="data.win_extend.code_arr[1]" />
                                <span>+</span>
                                <span class="win-code-item color-white bg-blue" v-html="data.win_extend.code_arr[2]" />
                                <span>=</span>
                                <span class="win-code-item color-white bg-red">{{data.win_extend.code_he}}</span>
                                <span>{{data.win_extend.code_bos}}{{data.win_extend.code_sod}}</span>
                            </div>
                            <div class="win-code-extend" v-else>开奖中</div>
                        </div>
                    </div>
                    <div class="surface"></div>
                    <canvas @touchend="touchend" @touchmove="touchmove" @touchstart="touchstart" class="canvas" id="c1" v-show="!isScratch"></canvas>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import countDown from '../chatHeader/count-down'
export default {
    components: {
        countDown,
    },
    props: {
        lotto: {
            default: function () {
                return this.$parent
            },
            type: Object,
        },
    },
    data() {
        //
        return {
            c1: '', //画布
            ctx: '', //画笔
            ismousedown: false, //标志用户是否按下鼠标或开始触摸
            fontem: '', // 获取html字体大小
            isScratch: false, // 是否刮过卡
            showPrize: false, // 显示奖品
            data: {},
            loading: false,
            timeCountDown: 10,
            timer: null,
            page: {
                current: 1,
            },
            items: [],
            query: {
                source: 'all',
                page: 1,
            },
        }
    },
    watch: {
        'lotto.open_last'() {
            if (!this.lotto.open_last[0].win_extend) {
                // this.lotto.bet_current = this.lotto.open_last[0]
                this.data = this.lotto.open_last[1]
            } else {
                this.data = this.lotto.open_last[0]
            }
        },
    },
    mounted() {
        this.fontem = parseInt(window.getComputedStyle(document.documentElement, null)['font-size'])
        //这是为了不同分辨率上配合@media自动调节刮的宽度
        this.c1 = document.getElementById('c1')
        //这里很关键，canvas自带两个属性width、height,我理解为画布的分辨率，跟style中的width、height意义不同。
        //最好设置成跟画布在页面中的实际大小一样
        //不然canvas中的坐标跟鼠标的坐标无法匹配
        this.c1.width = this.c1.clientWidth + 15
        this.c1.height = this.c1.clientHeight
        // console.log(this.c1.width, this.c1.height)
        this.ctx = this.c1.getContext('2d')
        this.initCanvas()
    },
    unmounted() {
        this.clearTimer()
    },
    methods: {
        //定时器
        startCountDown() {
            this.timer = setInterval(() => {
                //创建定时器
                if (this.timeCountDown === 1) {
                    this.clearTimer() //关闭定时器
                    this.timeCountDown = 10
                } else {
                    this.decrement()
                }
            }, 1000)
        },
        decrement() {
            this.timeCountDown--
        },
        clearTimer() {
            //清除定时器
            clearInterval(this.timer)
            this.timer = null
        },

        // 画刮刮卡
        initCanvas() {
            this.ctx.globalCompositeOperation = 'source-over'
            this.ctx.fillStyle = '#e5e5e5'
            // console.log(this.c1.clientWidth, this.c1.clientHeight)
            this.ctx.fillRect(0, 0, this.c1.clientWidth, this.c1.clientHeight)
            this.ctx.fill()
            this.ctx.font = 'Bold 24px Arial'
            this.ctx.textAlign = 'center'
            this.ctx.fillStyle = '#a0a0a0'
            this.ctx.fillText('已有结果请刮开涂层', this.c1.width / 2, 55)
            //有些老的手机自带浏览器不支持destination-out,下面的代码中有修复的方法
            this.ctx.globalCompositeOperation = 'destination-out'
        },
        resetCanvas() {
            this.ctx.globalCompositeOperation = 'source-over'
            this.ctx.fillStyle = '#e5e5e5'
            // console.log(this.c1.clientWidth, this.c1.clientHeight)
            this.ctx.fillRect(0, 0, 300, 300)
            this.ctx.fill()
            this.ctx.font = 'Bold 24px Arial'
            this.ctx.textAlign = 'center'
            this.ctx.fillStyle = '#a0a0a0'
            this.ctx.fillText('已有结果请刮开涂层', this.c1.width / 2, 55)
            //有些老的手机自带浏览器不支持destination-out,下面的代码中有修复的方法
            this.ctx.globalCompositeOperation = 'destination-out'
        },
        touchstart(e) {
            e.preventDefault()
            this.ismousedown = true
        },
        // 操作刮卡
        touchend(e) {
            sessionStorage.setItem('isScratch', true)
            e.preventDefault()
            //得到canvas的全部数据
            var a = this.ctx.getImageData(0, 0, this.c1.width, this.c1.height)
            var j = 0
            for (var i = 3; i < a.data.length; i += 4) {
                if (a.data[i] == 0) j++
            }
            //当被刮开的区域等于一半时，则可以开始处理结果
            if (j >= a.data.length / 8) {
                this.isScratch = true
            }
            this.ismousedown = false
        },
        touchmove(e) {
            this.showPrize = true
            e.preventDefault()
            if (this.ismousedown) {
                if (e.changedTouches) {
                    e = e.changedTouches[e.changedTouches.length - 1]
                }
                var topY = document.getElementById('top').offsetTop
                var oX = this.c1.offsetLeft * 20,
                    oY = this.c1.offsetTop * 6 + topY
                // console.log(e.clientX + document.body.scrollLeft, e.pageX)
                var x = (e.clientX + document.body.scrollLeft || e.pageX) - oX || 0,
                    y = (e.clientY + document.body.scrollTop - 40 || e.pageY) - oY || 0
                //画360度的弧线，就是一个圆，因为设置了ctx.globalCompositeOperation = 'destination-out';
                //画出来是透明的
                this.ctx.beginPath()

                this.ctx.arc(x, y, this.fontem * 0.5, 0, Math.PI * 2, true) // 调整画笔的大小
                //下面3行代码是为了修复部分手机浏览器不支持destination-out
                //我也不是很清楚这样做的原理是什么
                // this.c1.style.display = 'none';
                // this.c1.offsetHeight;
                // this.c1.style.display = 'inherit';
                this.ctx.fill()
            }
        },

        refresh() {
            // this.isScratch = !this.isScratch
            this.timeCountDown = 10
            this.c1 = document.getElementById('c1')

            this.isScratch = !this.isScratch

            this.resetCanvas()
            this.startCountDown()
            this.$emit('refresh')
            // this.$emit('setNextCurrent')
        },
    },
    created() {
        if (!this.lotto.open_last[0].win_extend) {
            this.data = this.lotto.open_last[1]
        } else {
            this.data = this.lotto.open_last[0]
        }
    },
}
</script>
<style lang="less" scoped>
.lotto-header {
    position: fixed;
    width: 100%;
    z-index: 9000;
    top: 46px;
    height: 100px;
    // background: linear-gradient(90deg, #4f8dfe, #34bcfe);
    display: flex;
    border-bottom: 1px solid #f5f5f5;
    max-width: 768px;
    &-item {
        height: 80px;
        width: 50%;
        text-align: center;
        padding-top: 10px;
        position: relative;
        background: @white;
        line-height: 22px;
        z-index: 9980;
        .action {
            color: @blue-common;
            font-size: 12px;
        }
    }
    &-item:first-child {
        height: 220px;
        // margin-left: 10px;
        // border-top-left-radius: 10px;
        // border-bottom-left-radius: 10px;
    }
    &-item:last-child {
        height: 220px;
        // display: flex;
        // align-content: center;
        // align-items: center;
        // margin-right: 10px;
        // border-top-right-radius: 10px;
        // border-bottom-right-radius: 10px;
    }
    &-item:first-child::after {
        content: ' ';
        position: absolute;
        height: 30px;
        width: 1px;
        background: #f3f2f4;
        right: -1px;
        top: 38%;
        margin-top: -10px;
    }
    .balance {
        span {
            display: block;
        }
        // .squint {
        //     width: 90%;
        //     height: 35px;
        //     background: grey;
        //     margin-left: 5%;
        // }
    }
}
.container {
    width: 100%;
    height: 100%;
    background-color: #985113;
    padding: 100px 0;
    .award_box {
        width: 95%;
        height: 360px;
        margin: 0px auto;
        // background: url('./img/2.img') no-repeat center/100%;
        position: relative;
        padding-top: 70px;
        .award {
            width: 85%;
            height: 180px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 68%;
            .title {
                color: rgba(3, 45, 97, 1);
                font-size: 32px;
                font-weight: 800;
                line-height: 50px;
                margin-top: 50px;
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                span {
                    color: #cc162d;
                }
            }
        }
        .surface {
            position: absolute;
            z-index: 98;
            width: 95%;
            height: 160px;
            margin: 150px auto;
            // background: url('./img/1.png') no-repeat center/100%;
            padding-top: 70px;
        }
    }

    .map_box {
        margin-top: 200px;
        width: 100%;
        height: 500px;
        padding: 10px;
        bottom: 5px solid orange;
        box-sizing: border-box;
    }
}
.award_box {
    position: relative;
}
.canvas {
    position: absolute;
    width: 90%;
    height: 180px;
    left: 7%;
    top: 0;
    z-index: 9999;
}
.win-code-extend {
    display: flex;
    padding-top: 7px;
    // margin-top: 15px;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    .win-code-item {
        width: 20px;
        height: 20px;
    }
}
</style>