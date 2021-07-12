<template>
    <grid-form :gutter="20" left="12" right="12">
        <template slot="left">
            <Card dis-hover title="基本设置">
                <Button @click="updateData" size="small" slot="extra" type="primary">保存设置</Button>
                <lett-loading :visible="loading" @click="getData" />

                <Form :label-width="100" class="pt-10" label-position="left">
                    <FormItem label="彩票控制">
                        <Input type="text" v-model="items.master_lotto_control"></Input>
                    </FormItem>

                    <FormItem label="下分双模式">
                        <Input type="text" v-model="items.master_trans_dual_mode"></Input>
                    </FormItem>

                    <FormItem label="客服头像">
                        <Input type="text" v-model="items.master_service_avatar"></Input>
                    </FormItem>

                    <FormItem label="MOCK中奖">
                        <Input type="text" v-model="items.master_mock_winner"></Input>
                    </FormItem>
                    <FormItem label="平台初始资金">
                        <Input type="text" v-model="items.master_app_init_fund"></Input>
                    </FormItem>
                    <FormItem label="Build_PC">
                        <Input type="text" v-model="items.master_build_id_pc"></Input>
                    </FormItem>
                    <FormItem label="Build_WAP">
                        <Input type="text" v-model="items.master_build_id_wap"></Input>
                    </FormItem>
                </Form>
            </Card>
        </template>
    </grid-form>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            items: {},
        }
    },

    created() {
        this.getData()
    },

    methods: {
        updateData() {
            this.$post('/option/update/patch', this.items)
        },

        getData() {
            let params = { regexp: 'master' }
            this.$get('/option/get', params).then((res) => {
                this.items = res.data
            })
        },
    },
}
</script>