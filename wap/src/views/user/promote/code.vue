<template>
    <div class="code-content">
        <userHeader title="我的邀请码" />
        <!-- <div class="tab">
            <span :class="active == 0 ? 'active' : ''" @click="active=0">推广注册一</span>
            <span :class="active == 1 ? 'active' : ''" @click="active=1">推广注册二</span>
        </div>-->
        <div class="content">
            <div class="content-header"></div>
            <div class="content-body">
                <div class="remessage">
                    我的UID是：
                    <b>{{user.id}}</b>，诚邀您加入
                    <b>{{$store.state.config.web_title}}</b>平台!合作共赢!
                </div>
                <div class="head-img">
                    <img :src="avatar" class="img-responsive" />
                </div>
                <div class="box_top"></div>
                <div class="er-img">
                    <div>
                        <h1>{{$store.state.config.web_title}}微信二维码</h1>
                        <a class="downqrcode" href="javascript:;" id="qrcode"></a>
                    </div>
                </div>
                <!-- <p class="joinlink" id="linka">{{link}}</p> -->
                <div class="info">
                    <!-- <van-button @click="copy(link)" class="info-button" color="#3caffe" type="info">复制链接</van-button> -->
                    <van-button class="info-button" color="#3caffe" type="info">截图保存</van-button>
                </div>
                <div class="box_tips">
                    <p>tips：通过以上二维码注册的用户均为你的下线</p>
                    <p>全民推广,拉玩家可享有永久下线下注流水3‰反水</p>
                </div>
            </div>
            <div class="content-footer"></div>
        </div>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
import QRCode from 'qrcodejs2'
// import config from '@/config'
import { Toast } from 'vant'
// eslint-disable-next-line // 屏蔽下一行eslint报错

export default {
    data() {
        return {
            logoImg: 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/logo.png',
            avatar: 'http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/bg/default_img.png',
            active: 0,
        }
    },

    mounted() {
        this.qrcode(this.user.id)
    },
    computed: {
        getCode() {
            return this.user.id
        },
        user() {
            return this.$store.state.user.info
        },
        link() {
            let domain_url = this.$store.state.config.domain_url
            // console.log('http://' + domain_url + '?verification=1022&ref=' + this.user.id)
            return 'http://' + domain_url + '?verification=1022&ref=' + this.user.id
            // return config.wapDomain + '?ref=' + this.user.id
        },
    },
    components: {
        userHeader,
    },
    methods: {
        copy(contanct) {
            var tag = document.createElement('input')
            tag.setAttribute('id', 'cp_hgz_input')
            tag.value = contanct
            document.getElementsByTagName('body')[0].appendChild(tag)
            document.getElementById('cp_hgz_input').select()
            document.execCommand('copy')
            document.getElementById('cp_hgz_input').remove()
            Toast.success('复制成功')
        },
        qrcode(user_id) {
            let qrcode = new QRCode('qrcode', {
                colorLight: '#ffffff',
                // width: 200,
                // height: 200,
                text: this.link,
            })
        },
    },
}
</script>
<style lang="less" scoped>
.code-content {
    position: relative;
    width: 100%;
    height: 100%;
    background: #ffdfbf;
    background-size: 100% 100%;
}
.tab {
    width: 60%;
    border: 1px solid @orange;
    margin: 0.8rem auto;
    text-align: center;
    background: @orange;
    overflow: hidden;
    border-radius: 5px;
    display: flex;
    justify-content: space-around;
    align-items: center;
    span {
        display: inline-block;
        text-align: center;
        font-size: 17px;
        border-radius: 5px;
        width: 50%;
        height: 100%;
        line-height: 33px;
        color: @white;
    }
    .active {
        color: @black;
        background-color: @white;
    }
}
.content {
    transform: scale(0.9);
    position: relative;
    &-header {
        background: url('http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/bg/qrcodebg_top.png') no-repeat;
        background-size: 100% 100%;
        height: 5vh;
    }
    &-body {
        background: url('http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/bg/qrcodebg_center.png');
        background-size: 100% auto;
        height: 70vh;
        width: 100%;
        .remessage {
            position: absolute;
            left: 1.2rem;
            padding: 1rem 5%;
            top: 3.5rem;
            width: 70%;
            font-size: 0.9rem;
            // background: url(http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/bg/msgbody.png) no-repeat top center;
            background-size: 100% 100%;
            b {
                color: @orange;
            }
        }
        .head-img {
            width: 4rem;
            height: 4rem;
            position: absolute;
            right: 1.5rem;
            top: 3.7rem;
            img {
                width: 100%;
                border-radius: 50%;
            }
        }
        .box_top {
            height: 8rem;
        }
        .er-img {
            // width: 50vw;
            // // height: 15rem;
            // margin: 0 auto;
            // background: url(http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/bg/qrcodebg_border.png) no-repeat;
            background-size: 100% 100%;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
            a {
                // display: block;
                // width: 86%;
                // height: 86%;
                // margin: 0 auto;
                // padding: 7% 0;
                width: 45vw;
                display: inline-table;
                /deep/img {
                    width: 100%;
                    height: 100%;
                }
                // padding: 2% 0%;
            }
        }
        .joinlink {
            font-size: 17px;
            text-align: center;
            color: @black;
            margin: 0;
        }
        .info {
            padding: 0.6rem;
            display: flex;
            justify-content: space-around;
            &-button {
                width: 35%;
                height: 2.3rem;
                background: @orange!important;
                border-color: @orange!important;
            }
        }
        .box_tips {
            color: #999;
            text-align: center;
        }
    }
    &-footer {
        background: url('http://ooo28-public.oss-cn-hangzhou.aliyuncs.com/yl28/bg/qrcodebg_bottom.png') no-repeat;
        background-size: 100% auto;
        height: 8vh;
        width: 100%;
    } //
}
</style>