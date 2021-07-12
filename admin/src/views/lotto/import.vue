<template>
    <Card dis-hover title="数据导入">
        <lett-loading :visible="loading" @click="onCloseLoading" />

        <Form :label-width="78" class="pt-20 pb-10" label-position="left">
            <FormItem label="接口导入">
                <Select class="mr-10 width-30" v-model="form.source_code">
                    <Option :key="code" :value="code" v-for="(name, code) in $store.state.config.open_cai_code">{{ name }}</Option>
                </Select>

                <DatePicker class="width-30" placeholder="选择日期" type="date" v-model="form.source_date" />

                <Button @click="importFromApi" class="float-right" type="primary">确认导入</Button>
            </FormItem>
        </Form>

        <Form :label-width="78" class="pt-10" label-position="left">
            <FormItem label="文本导入">
                <Input :rows="20" placeholder="请输入数据源" type="textarea" v-model="form.source_text" />
                <div class="form-desc-base">传入JSON数组字符串，请注入格式是否正确</div>
            </FormItem>

            <FormItem>
                <Button @click="importFromText" type="primary">确认导入</Button>
            </FormItem>
        </Form>
    </Card>
</template>

<script>
export default {
    name: 'lottoDataImport',
    data() {
        return {
            loading: false,
            lotto_source: [
                {
                    code: 'cakeno',
                    name: '加拿大基诺'
                },
                {
                    code: 'bjkl8',
                    name: '北京快乐8'
                },
                {
                    code: 'mlaft',
                    name: '马其他幸运飞艇'
                }
            ],

            form: {
                import: '',
                source_api: ''
            }
        }
    },

    computed: {
        visible: {
            get() {
                return this.config.visible
            },
            set() {
                this.config.visible = false
            }
        }
    },

    methods: {
        onCloseLoading() {
            this.loading = false
        },

        importFromApi() {
            this.$post('/lotto/import/api', this.form).then(res => {
                if (res.code === 200) {
                    this.loading = '数据导入成功 点击关闭'
                }
            })
        },

        importFromText() {
            this.$post('/lotto/import/text', this.form).then(res => {
                if (res.code === 200) {
                    this.loading = '数据导入成功 点击关闭'
                }
            })
        }
    }
}
</script>
