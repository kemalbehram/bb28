<template lang="local">
<Layout style="height: 100%" class="main">
    <Sider hide-trigger collapsible :width="256" :collapsed-width="64" v-model="collapsed" class="left-sider" :style="{overflow: 'hidden'}">
        <div class="logo-con">
            <!-- <img v-show="!collapsed" :src="maxLogo" key="max-logo" /> -->

            <img :src="minLogo" key="min-logo" />
            <span v-show="!collapsed" class="name">{{$store.state.config.web_title}}管理后台</span>
        </div>
        <side-menu accordion ref="sideMenu" :active-name="$route.name" :collapsed="collapsed" @on-select="turnToPage" :menu-list="menuList">
            <!-- 需要放在菜单上面的内容，如Logo，写在side-menu标签内部，如下 -->
        </side-menu>
    </Sider>
    <Layout>
        <Header class="header-con">
            <header-bar :collapsed="collapsed" @on-coll-change="handleCollapsedChange">
                <user :message-unread-count="unreadCount" :user-avatar="userAvatar"/>
                <span class="mr-20 color-red pointer" @click="openChat" v-if="$store.state.service.new">您有新的消息</span>
                <span class="mr-20 pointer" @click="openChat" v-else>在线客服</span>
                                <span class=" mr-20 pointer" @click="openAwardChat">红包聊天室</span>
                <!-- <language v-if="$config.useI18n" @on-lang-change="setLocal" style="margin-right: 10px;" :lang="local"/> -->
                <!-- <fullscreen v-model="isFullscreen" style="margin-right: 10px;"/> -->
            </header-bar>
        </Header>
        <Content class="main-content-con">
            <Layout class="main-layout-con">
                <div class="tag-nav-wrapper">
                    <tags-nav :value="$route" @input="handleClick" :list="tagNavList" @on-close="handleCloseTag" />
                </div>
                <Content class="content-wrapper">
                    <keep-alive :include="cacheList">
                        <router-view :key="$route.fullPath"/>
                    </keep-alive>
                    <div class="mb-50"></div>
                    <common-bottom/>
                    <ABackTop :height="100" :bottom="80" :right="50" container=".content-wrapper"></ABackTop>
                </Content>
            </Layout>
        </Content>
    </Layout>
</Layout>
</template>

<script>
import SideMenu from './components/side-menu'
import HeaderBar from './components/header-bar'
import TagsNav from './components/tags-nav'
import User from './components/user'
import ABackTop from './components/a-back-top'
import Fullscreen from './components/fullscreen'
import Language from './components/language'
import { mapMutations, mapActions, mapGetters } from 'vuex'
import { getNewTagList, getNextRoute, routeEqual } from '@/libs/util'
import routers from '@/router/routers'
import commonBottom from './components/common-bottom'
import minLogo from '@/assets/images/logo-default.png'
import maxLogo from '@/assets/images/logo.jpg'
import './main.less'
export default {
    name: 'Main',
    components: {
        SideMenu,
        HeaderBar,
        Language,
        TagsNav,
        Fullscreen,
        User,
        ABackTop,
        commonBottom,
    },
    data() {
        return {
            collapsed: false,
            minLogo,
            maxLogo,
            isFullscreen: false,
        }
    },
    computed: {
        ...mapGetters(['errorCount']),
        tagNavList() {
            return this.$store.state.app.tagNavList
        },
        tagRouter() {
            return this.$store.state.app.tagRouter
        },
        userAvatar() {
            return this.$store.state.user.avatarImgPath
        },
        cacheList() {
            return ['ParentView', ...(this.tagNavList.length ? this.tagNavList.filter((item) => !(item.meta && item.meta.notCache)).map((item) => item.name) : [])]
        },
        menuList() {
            return this.$store.getters.menuList
        },
        local() {
            return this.$store.state.app.local
        },
        hasReadErrorPage() {
            return this.$store.state.app.hasReadErrorPage
        },
        unreadCount() {
            return this.$store.state.user.unreadCount
        },
    },
    methods: {
        ...mapMutations([
            'setBreadCrumb',
            'setTagNavList',
            'addTag',
            // 'setLocal',
            'setHomeRoute',
        ]),
        ...mapActions([
            'handleLogin',
            // 'getUnreadMessageCount'
        ]),
        turnToPage(route) {
            let { name, params, query, path } = {}
            if (typeof route === 'string') name = route
            else {
                name = route.name
                params = route.params
                query = route.query
                path = route.path
            }
            if (name.indexOf('isTurnByHref_') > -1) {
                window.open(name.split('_')[1])
                return
            }

            this.$router.push({
                name,
                params,
                query,
                path,
            })
        },
        handleCollapsedChange(state) {
            this.collapsed = state
        },
        handleCloseTag(res, type, route) {
            if (type === 'all') {
                this.turnToPage(this.$config.homeName)
            } else if (routeEqual(this.$route, route)) {
                if (type !== 'others') {
                    const nextRoute = getNextRoute(this.tagNavList, route)
                    this.$router.push(nextRoute)
                }
            }
            this.setTagNavList(res)
        },
        handleClick(item) {
            this.turnToPage(item)
        },

        openChat() {
            this.$store.state.service.modal = true
            this.$store.state.service.new = false
            setTimeout(() => {
                let msg = document.getElementById('chat-window')
                msg.scrollTop = msg.scrollHeight
            })
        },
        openAwardChat() {
            this.$store.state.service.award_modal = true
        },
    },
    watch: {
        $route(newRoute) {
            const { name, query, params, meta, path } = newRoute
            this.addTag({
                route: {
                    name,
                    query,
                    params,
                    meta,
                    path,
                },
                type: 'push',
            })
            this.setBreadCrumb(newRoute)
            this.setTagNavList(getNewTagList(this.tagNavList, newRoute))
            this.$refs.sideMenu.updateOpenName(newRoute.name)
        },
    },
    mounted() {
        /**
         * @description 初始化设置面包屑导航和标签导航
         */
        this.setTagNavList()
        this.setHomeRoute(routers)
        const { name, params, query, meta } = this.$route
        this.addTag({
            route: { name, params, query, meta },
        })
        this.setBreadCrumb(this.$route)
        // 设置初始语言
        // this.setLocal(this.$i18n.locale)
        // 如果当前打开页面不在标签栏中，跳到homeName页
        if (!this.tagNavList.find((item) => item.name === this.$route.name)) {
            this.$router.push({
                name: this.$config.homeName,
            })
        }
        // 获取未读消息条数
        // this.getUnreadMessageCount()
    },
}
</script>
