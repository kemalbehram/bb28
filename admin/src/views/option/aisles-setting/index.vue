<template>
    <Card dis-hover>
        <table-header-new title="银行卡设置"></table-header-new>

        <Form :label-width="100" class="pt-20" label-position="left" v-if="aisles_bank_card">
            <FormItem label="名称" width="200px">
                <Input style="width:200px" type="text" v-model="aisles_bank_card.extend.name"></Input>
            </FormItem>
            <FormItem label="卡号">
                <Input style="width:200px" type="text" v-model="aisles_bank_card.extend.card_number" width="200px"></Input>
            </FormItem>

            <FormItem>
                <Button @click="updateInfo" class="mb-20" long style="width:100px" type="primary">确认修改</Button>
            </FormItem>
        </Form>
    </Card>
</template>

<script>
import JsMixins from '@/components-ue/table/table-index.js'
export default {
    mixins: [JsMixins],
    name: 'optionHelper',
    data() {
        return {
            aisles_bank_card: {},
        }
    },
    created() {
        this.getData()
    },
    methods: {
        getData() {
            this.$get('/option/aisles').then((res) => {
                this.aisles_bank_card = res.data.data
            })
        },
        updateInfo() {
            this.$post('/option/aislesUpdate', this.aisles_bank_card).then((res) => {
                console.log(res)
            })
        },
    },
    components: {},
}
</script>

