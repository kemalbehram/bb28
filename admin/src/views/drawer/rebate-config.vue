<template>
    <Drawer :closable="false" @on-visible-change="getData" title="反水设置" v-model="params.visible" width="900">
        <!-- <lett-loading :visible="loading" @click="hiddenLoading" /> -->

        <!-- <Card :dis-hover="true" :shadow="false">
            <p slot="title">代理</p>

            <Form :label-width="70" class="relative pt-10" label-position="right" v-if="JSON.stringify(form_agent) != '{}'">
                <FormItem :key="index" :label="'房间'+index.substr(index.length-1,1)" class="border-bottom pb-20" v-for="(item,index) in form_agent">
                    <div class="display-flex mb-10">
                        <Input class="pr-20 color-red" readonly style="width:20%" type="text" v-model="item.play_type">
                            <span slot="prepend">玩法</span>
                        </Input>
                        <RadioGroup v-model="item.mode">
                            <Radio label="1">流水模式</Radio>
                            <Radio label="2">亏损模式</Radio>
                        </RadioGroup>
                    </div>
                    <div class="display-flex mb-20">
                        
                        <Input class="pr-20" type="number" v-model="item.money">
                            <span slot="prepend">金额限制</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.count">
                            <span slot="prepend">期数</span>
                        </Input>
                    </div>

                    <div class="display-flex">
                        <Input class="pr-20" type="number" v-model="item.single">
                            <span slot="prepend">单注</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.combo">
                            <span slot="prepend">组合</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.lhb">
                            <span slot="prepend">龙虎豹</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.number">
                            <span slot="prepend">数字</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.other">
                            <span slot="prepend">其他</span>
                        </Input>
                    </div>
                </FormItem>
                <FormItem>
                    <Button @click="updateConfig(1)" class="mb-20" long type="primary">确认修改</Button>
                </FormItem>
            </Form>
        </Card>-->

        <Card :dis-hover="true" :shadow="false">
            <lett-loading :visible="loading" @click="hiddenLoading" />
            <!-- <p >普通用户</p> -->
            <div class="space-between" slot="title">
                <span>普通用户</span>
                <!-- <Switch false-color="#ff4949" true-color="#13ce66" v-model="showClose"> -->
                <i-switch size="large" v-model="showClose">
                    <span slot="open">显示</span>
                    <span slot="close">关闭</span>
                </i-switch>
            </div>

            <Form :label-width="70" class="relative pt-10" label-position="right" v-if="JSON.stringify(form_user) != '{}'">
                <FormItem :key="index" :label="'房间'+index.substr(index.length-1,1)" class="border-bottom pb-20" v-for="(item,index) in form_user" v-if="checkShow(item.opend)">
                    <div class="display-flex mb-10">
                        <RadioGroup class="pr-20" v-model="item.opend">
                            <Radio label="1" value="1">显示</Radio>
                            <Radio label="2" value="2">关闭</Radio>
                        </RadioGroup>
                        <RadioGroup v-model="item.model">
                            <Radio label="1" value="1">流水模式</Radio>
                            <Radio label="2" value="2">亏损模式</Radio>
                        </RadioGroup>
                        <RadioGroup style="margin:left:40px" v-model="item.limit">
                            <Radio label="1" value="1">无限制（全部玩法都反水）</Radio>
                            <Radio label="2" value="2">组合反水</Radio>
                        </RadioGroup>
                    </div>
                    <div :key="c_index" class="display-flex mb-20" v-for="(c_item,c_index) in item.items">
                        <Input class="pr-20" type="number" v-model="item.items[c_index].money">
                            <span slot="prepend">金额限制</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.items[c_index].count">
                            <span slot="prepend">期数</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.items[c_index].other">
                            <span slot="prepend">返水比列</span>
                        </Input>
                        <!-- <RadioGroup class="pr-20" v-model="item.items[c_index].is_open">
                            <Radio label="1" value="1">开启</Radio>
                            <Radio label="2" value="2">关闭</Radio>
                        </RadioGroup>-->
                        <div class="width-50">
                            <i-switch class="pr-20" size="large" v-model="item.items[c_index].is_open">
                                <span slot="open" value="1">开启</span>
                                <span slot="close" value="2">关闭</span>
                            </i-switch>
                        </div>
                        <Icon @click="remove(index,c_index)" class="pointer" size="30" type="ios-backspace-outline" />
                    </div>

                    <!-- <div class="display-flex">
                        <Input class="pr-20" type="number" v-model="item.single">
                            <span slot="prepend">单注</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.combo">
                            <span slot="prepend">组合</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.lhb">
                            <span slot="prepend">龙虎豹</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.number">
                            <span slot="prepend">数字</span>
                        </Input>
                        <Input class="pr-20" type="number" v-model="item.other">
                            <span slot="prepend">其他</span>
                        </Input>
                    </div>-->
                    <div class="text-center">
                        <Button @click="addCondition(index)" icon="md-add" type="primary">添加条件</Button>
                    </div>
                </FormItem>
                <FormItem>
                    <Button @click="updateConfig(2)" class="mb-20" long type="primary">确认修改</Button>
                </FormItem>
            </Form>
        </Card>
    </Drawer>
</template>
<script>
export default {
    data() {
        return {
            params: this.$store.state.drawer.rebate_config,

            loading: false,
            form_user: {},
            form_agent: {},
            showClose: false,
        }
    },

    methods: {
        remove(index, position) {
            this.form_user[index].items.splice(position, 1)
        },
        addCondition(index) {
            this.form_user[index].items.push({
                count: null,
                money: null,
                other: null,
                is_open: '2',
            })
        },
        getData(visible) {
            var api = 'user-rebate/config'
            this.loading = true
            this.$get(api).then((res) => {
                if (res.code !== 200) {
                    return (this.loading = res.message)
                }
                this.loading = false
                this.form_user = res.data.user_rebate
                this.form_agent = res.data.agent_rebate
                // console.log(JSON.stringify(this.form_user.room1))
            })
        },
        updateConfig(id) {
            var api = 'user-rebate/update-config'
            var params = {}
            params.id = id
            this.loading = true
            if (id == 1) {
                params.data = this.form_agent
            } else {
                params.data = this.form_user
            }
            this.$post(api, params).then((res) => {
                if (res.code !== 200) return false
            })
        },
        checkShow(code) {
            if (this.showClose == true) {
                return true
            }
            if (this.showClose == false && code == 1) {
                return true
            }
            return false
        },
        hiddenLoading() {},
    },
}
</script>
<style lang="less" scoped>
.space-between {
    display: flex;
    justify-content: space-between;
}
.width-80 {
    width: 80px;
}
</style>