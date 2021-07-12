<template>
    <div  class="relative">
        <userHeader  title="代理制度" />
        <div class="holder" v-if="!rule">
            <div class="container">
            <van-image height="100" :src="icon" width="100" />
        <p><h1>英利28以公平，公正的制度</h1></p>
        <p>满6个下线可见详细代理制度
            <template v-for="(item,index) in group">
            <br>{{item.title}}：{{item.value}}
            </template>    
        </p>    
        </div>
        </div>
        <div class="holder" v-else>
            <div class="con-header">
                <van-image class="con-header--icon" height="100" :src="icon" width="100" />
                <span class="weighter">英利28以公平，公正的制度</span>
                <span>根据用户推广团队的投注额来返推广彩金</span>
                <span>具体规则如下</span>
            </div>
            <div class="con-content">
                
                    <div class="con-content--title">
                        <span class="color-orange">▌</span>
                        <span>流水提成</span>
                    </div>
                    <div class="con-content--th">
                        <span class="line-first">游戏类型</span>
                        <span class="line-second">直属下线</span>
                        <span class="line-third">二级下线</span>
                    </div>
                    <div class="con-content--tr" v-for="(item,index) in condition">
                        <span class="line-first">{{item.game}}-{{item.title}}</span>
                        <span class="line-second">{{item.son}}%</span>
                        <span class="line-third">{{item.grand}}%</span>
                    </div>
            </div>

            <div class="con-content">
                <div class="con-content--title">
                    <span class="color-orange">▌</span>
                    <span>介绍费规则</span>
                    <p><b>适用游戏:</b>加拿大28、比特币28</p>
                    <p><b>基本要求:</b></p>
                    <p>
                        1．充值满2000、输赢超过600、下注25期以上、组合数字极值加起来10%以上，介绍费最高可补1次加首次共2次，常驻才可补介绍费，首次介绍费7天后可查。
                    </p>
                    <p>
                        2.分分28/比特28两期算一期计算介绍费。
                    </p>
                    <p>
                        3．游戏的1.8+1.88+2.5玩法为高赔率，均无介绍费。注意事项:对压，杀组合，全单注者介绍费酌情减少或减半，严重者心意红包处理。
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
    },
    data(){
        return {
            group:[],
            rule:false
        }
    },
    computed:{
        icon(){
            return this.$store.state.config.icon
        },
        condition() {
            let arr = []
            let game = Object.values(this.$store.state.config.lotto_items)
            let room = this.$store.state.config.play_types
            game = Array.from(game)
            
            game.forEach(function (value, key) {
                value.types.forEach(function (val, kkk) {
                    arr.push({ 
                        game: value.title,
                        title: room[val].title,
                        son:room[val].agent_rebate.agent_son,
                        grand:room[val].agent_rebate.agent_grand,
                    })
                })
            })
            return arr
        },
    },
    methods:{
        getNotice() {
            this.$get('/offline/agent-notice').then((res) => {
                this.group = res.data.notice
                this.rule= res.data.satisfy
            })
        },
        
    },
    created(){
        console.log(this.condition)
        this.getNotice()
    }
}
</script>
<style lang="less" scoped>
.container {
    text-align: center;
    padding: 15vw;
    p {
        color: @black;
        span {
            color: #b7b7c7;
            
        }
        b {
            font-size: 20px;
        }
    }
}
.con-header{
    &--icon{
        padding:3vh
    }
    text-align: center;
    
    span{
        display: block;
        font-size: 15px;  
    }
    .weighter{
        font-size: 18px;
        font-weight: 550;
    }
}
.relative{
    width: 100%;
    // height: 100%;
    background: @white;
}
.con-content{
    padding: 10px 10vw;
    // ba
    &--title{
        font-size: 17px;
    }
    &--th{
        text-align: right;
        padding: 10px 0;
        border-radius: 3px;
    }
    &--tr{
        text-align: right;
        padding: 10px 0;
        border-radius: 3px;
    }
    &--tr:nth-child(2n+1){
        background: @line-light;
    }
}
.line-first{
    text-align: center;
    display: inline-block;
    width: 40%;
}
.line-first:first-child{
    text-align: left;
}
.line-second{
    display: inline-block;
    width: 30%;
}
.line-third{
    display: inline-block;
    width: 30%;
}
.color-blue{
    color:@blue-common
}
</style>