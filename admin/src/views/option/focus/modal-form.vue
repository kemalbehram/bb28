<template>
    <Drawer :footer-hide="true" title="创建焦点图" v-model="visible" width="720">
        <lett-loading :visible="loading" />
        <Form :label-width="70" class="pt-10 pb-10" label-position="right">
            <FormItem label="显示位置">
                <Select :max-tag-count="2" multiple v-model="form.scope">
                    <Option :key="index" :value="index" v-for="(item,index) in this.$store.state.config.focus_scope">{{ item }}</Option>
                </Select>
            </FormItem>
            <FormItem label="焦点图">
                <image-upload :action="uploadAction" :height="200" :id="action" :width="538" v-model="form.image" />
            </FormItem>

            <FormItem label="链接页面">
                <Select v-model="form.mapping">
                    <Option :key="key" :value="item.mapping" v-for="(item,key) in this.$store.state.config.focus_mapping">{{item.name}}</Option>
                </Select>
            </FormItem>

            <FormItem :key="item.value" :label="item.name" v-for="item in field">
                <!-- {{item}} -->
                <div class="hidden">{{item.defalut && (form.params[item.value] = item.defalut)}}</div>
                <Input :disabled="item.disabled" :placeholder="item.placeholder" v-model="form.params[item.value]" />
            </FormItem>

            <Button @click="confirm" class="mt-20" long type="primary">确认创建</Button>
        </Form>
    </Drawer>
</template>

<script>
export default {
    props: {
        config: {},
        action: '',
    },

    data() {
        return {
            loading: false,
            uploadAction: 'image/create',
            default: {
                mapping: '',
                params: {},
                image: '',
            },
            field: [],
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
            },
        },

        form: {
            get() {
                if (this.action == 'create') return this.default
                if (this.action == 'update') return this.config.data
            },
            set() {},
        },
    },

    watch: {
        'form.mapping'(val) {
            if (val) {
                this.field = this.$store.state.config.focus_mapping[val].params
            } else {
                this.field = []
            }
        },
    },

    methods: {
        confirm() {
            this.$post(this.config.api, this.form).then((res) => {
                this.$emit('success', false, res.data, true)
            })
        },
    },
}
</script>

