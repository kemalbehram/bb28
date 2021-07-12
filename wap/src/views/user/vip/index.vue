<template>
    <div>
        <userHeader title="终身会员" />
        <img :src="vip1" alt />
        <div class="fenglu">
            <img :src="vip2" alt />
            <p class="title">累计领取礼金俸禄</p>
            <p class="salary">{{awards}}</p>
            <div class="desc">
                <span>我的等级:{{userLevel}}</span>
                <span class="ml-16">总投注:{{total_bet}}</span>
            </div>
            <!-- 
      <div class="level">
        <van-button type="danger" size="mini">Lv.0</van-button>
        <van-progress
          :percentage="100"
          stroke-width="8"
          pivot-color="white"
          color="white"
          :show-pivot="false"
        />
        <van-button type="default" size="mini" class="leve_up">Lv.1</van-button>
            </div>-->
            <div class="receive">
                <van-button @click="receiveUpgrade" size="small" type="danger">领取礼金</van-button>
                <van-button @click="receiveDay" size="small" type="danger">领取俸禄</van-button>
            </div>
        </div>
        <img :src="vip3" alt />
        <img :src="vip4" alt />
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
import { Notify } from 'vant'
export default {
    data() {
        return {
            vip1: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/vip1.jpg',
            vip2: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/vip2.png',
            vip3: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/vip3.jpg',
            vip4: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/vip4.jpg',
            total_bet: 0,
            awards: 0,
        }
    },
    created() {
        this.getLevel()
    },
    computed: {
        userLevel() {
            if (this.$store.state.user.info.level == undefined) {
                return 'Lv.00'
            } else {
                return this.$store.state.user.info.level.level_title
            }
            // console.log(typeof (this.$store.state.user.info.level));
        },
    },
    methods: {
        getLevel() {
            this.$get('/user/level/info').then((res) => {
                this.total_bet = res.data.total_bet
                this.awards = res.data.award
            })
        },
        receiveDay() {
            this.$post('user/level/day').then((res) => {
                this.awards = parseFloat(this.awards) + parseFloat(res.data[0])
            })
        },
        receiveUpgrade() {
            return Notify('申请成功，系统将在次日凌晨3点自动更新')
        },
    },
    components: {
        userHeader,
    },
}
</script>
<style lang="less" scoped>
.fenglu {
    background: #ef3e42;
    position: relative;
    .title {
        position: absolute;
        top: 3%;
        text-align: center;
        // left: 35%;
        width: 100%;
        transform: translate(0, -3%);

        color: #ee0037;
        font-size: 16px;
    }
    .salary {
        position: absolute;
        top: 12%;
        text-align: center;
        width: 100%;
        transform: translate(0, -12%);
        color: #ee0037;
        font-size: 26px;
    }
    .desc {
        position: absolute;
        top: 40%;
        text-align: center;
        left: 18%;
        display: flex;
        width: 63%;

        justify-content: space-between;
        span:first-child {
            color: #ef3c43;
        }
        span {
            display: inline-block;
        }
    }
    .level {
        display: flex;
        position: absolute;
        top: 50%;
        left: 15%;
        width: 70%;
        justify-content: space-around;
        /deep/.van-button {
            display: inline-block;
            flex: 1;
        }

        .leve_up {
            color: #ee0a24;
        }
        /deep/.van-progress {
            flex: 4;
            margin-top: 8px;
            border-radius: 0;
        }
    }
    .receive {
        display: flex;
        position: absolute;
        width: 71%;
        left: 14%;
        top: 60%;
        justify-content: space-around;
        /deep/.van-button {
            display: inline-block;
            width: 100px;
        }
    }
}
img {
    display: block;
    width: 100%;
}
</style>
