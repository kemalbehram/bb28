<template>
    <div>
        <userHeader title="下线详情" />
        <div class="my-info">
            <van-image :src="avatar" class="avatar" fit="cover" height="54" round width="54" />
            <span class="nickname">{{nickname}}</span>
            <span class="user-id">ID:{{user_id}}</span>
        </div>
    </div>
</template>
<script>
import userHeader from '@/components/user-header'
export default {
    data() {
        return {
            user_id: '',
            avatar: '',
            nickname: '',
        }
    },
    components: {
        userHeader,
    },
    created() {
        this.user_id = this.$route.params.user_id
        console.log(this.$route.params.user_id)
        this.getData(this.user_id)
    },
    methods: {
        getData(user_id) {
            this.loading = true
            // var query = { user_id }
            this.$get('user/reference/levelInfo', { user_id }).then((res) => {
                console.log(res)
                if (res.code !== 200) return this.$notify(res.message)
                this.loading = false
                this.avatar = res.data.userInfo.avatar
                this.nickname = res.data.userInfo.nickname
            })
        },
    },
}
</script>
<style lang="less" scoped>
.my-info {
    height: 26vh;
    background-image: url('https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/my_info.jpg');
    background-size: 100% 100%;
    position: relative;
    .avatar {
        position: absolute;
        left: 50%;
        top: 30%;
        transform: translate(-50%, -30%);
    }
    .nickname {
        position: absolute;
        left: 50%;
        top: 65%;
        transform: translate(-50%, -65%);
        color: white;
    }
    .user-id {
        position: absolute;
        left: 50%;
        top: 80%;
        transform: translate(-50%, -80%);
        color: white;
    }
}
</style>