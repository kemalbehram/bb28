<template>
    <div class="user-avatar-dropdown">
        <Dropdown @on-click="handleClick">
            <!-- <Badge :dot="!!messageUnreadCount"> -->
            <Avatar :src="userAvatar" />
            <!--       </Badge> -->
            <Icon :size="18" type="md-arrow-dropdown"></Icon>
            <DropdownMenu slot="list">
                <!-- <DropdownItem name="message">
                    消息中心<Badge style="margin-left: 10px" :count="10"></Badge>
                </DropdownItem>-->
                <DropdownItem name="logout">退出登录</DropdownItem>
            </DropdownMenu>
        </Dropdown>
    </div>
</template>

<script>
import './user.less'
import { mapActions } from 'vuex'
export default {
    name: 'User',
    props: {
        userAvatar: {
            type: String,
            default: ''
        }
    },
    methods: {
        ...mapActions(['handleLogOut']),
        logout() {
            this.handleLogOut().then(() => {
                this.$router.push({
                    name: 'login'
                })
            })
        },
        // message () {
        //   this.$router.push({
        //     name: 'message_page'
        //   })
        // },
        handleClick(name) {
            switch (name) {
                case 'logout':
                    this.logout()
                    break
                // case 'message': this.message()
                //   break
            }
        }
    }
}
</script>

<style lang="less" scoped>
/deep/.ivu-avatar {
    margin-top: -8px;
}
</style>