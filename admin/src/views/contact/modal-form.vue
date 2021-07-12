<template>
    <Drawer :footer-hide="true" :title="title" v-model="visible" width="520">
        <lett-loading :visible="loading" />
        <Form :label-width="76" label-position="right">
            <FormItem label="消息状态">
                <Row>
                    <Col span="16">
                        <Select v-model="form.status">
                            <Option :value="1">等待处理</Option>
                            <Option :value="2">处理中</Option>
                            <Option :value="3">处理结束</Option>
                        </Select>
                    </Col>
                    <Col class="pl-10" span="8">
                        <Button @click="confirm" long type="primary">确认修改</Button>
                    </Col>
                </Row>
            </FormItem>

            <Divider />

            <FormItem class="mb-0" label="留言用户" v-if="form.id">{{form.user.nickname}}</FormItem>
            <FormItem class="mb-0" label="联系信息" v-if="form.id">{{form.name}} · {{form.mobile}}</FormItem>
            <FormItem class="mb-0" label="留言详情">
                <div class="cell-content" v-html="$options.filters.trim(form.content)" />
            </FormItem>
        </Form>
    </Drawer>
</template>

<script>
export default {
    props: {
        config: {},
        action: '',
        title: ''
    },

    data() {
        return {
            loading: false
        }
    },

    computed: {
        visible: {
            get() {
                return this.config.visible
            },
            set() {
                this.config.visible = false
                this.config.data = {}
            }
        },

        form: {
            get() {
                return this.config.data
            },
            set() {}
        }
    },

    methods: {
        confirm() {
            const api = this.config.api
            this.$post(api, this.form).then(res => {
                this.form = {}
                const update = this.action == 'update' ? true : false
                this.$emit('success', false, res.data, update)
            })
        }
    }
}
</script>

<style lang="less" scoped>
.cell-content {
    line-height: 22px;
    padding: 6px 0;
}
</style>