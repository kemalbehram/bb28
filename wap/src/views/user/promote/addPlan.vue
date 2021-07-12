<template>
    <div class="relative">
        <userHeader title="新建方案" />
        <form>
            <van-field :rules="[{ required: true, message: '请填写用户名' }]" label="方案名" placeholder="方案名" v-model="title" />
            <van-field :rules="[{ required: true, message: '请填写密码' }]" label="备注" placeholder="备注" type="password" v-model="sub" />
            <br />
            <div class="desc">
                <span class="teach">方案教程</span>
                <span>批量编辑</span>
            </div>
        </form>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    components: {
        userHeader,
    },
    data() {
        return {
            title: '',
            sub: '',
        }
    },
}
</script>
<style lang="less" scoped>
.desc {
    background: @white;
    height: 40px;
    line-height: 40px;
    span {
        display: inline-block;
        width: 50%;
        text-align: center;
        color: #ff6a00;
    }
    .teach {
        position: relative;
    }
    .teach::before {
        content: '';
        position: absolute;
        right: 1px;
        top: 7px;
        width: 1px;
        height: 25px;
        background: @line;
    }
}
</style>