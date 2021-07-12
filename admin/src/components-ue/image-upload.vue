<template>
    <div class="relative">
        <div :style="style()" class="image-wrap" v-if="value">
            <Spin fix v-if="loading">上传中...</Spin>
            <img :src="value" v-if="value" />
            <label :for="forId()" class="mask">点击修改</label>
        </div>

        <label :for="forId()" :style="style()" class="label" v-else>
            <div :style="style(4)" class="upload">
                <Spin fix v-if="loading">上传中...</Spin>
                <Icon size="20" type="ios-camera"></Icon>
            </div>
        </label>

        <input :id="forId()" @change="changeFile" accept="image/*" class="none" name="image" ref="input" type="file" />
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: [String, Number],
            default: 'default'
        },
        value: String,
        action: String,
        width: [Number, String],
        height: [Number, String],
        extend: {
            type: [Object, Array, String, Number],
            default: () => {}
        }
    },

    data() {
        return {
            loading: false
        }
    },

    model: {
        prop: 'value',
        event: 'update'
    },

    methods: {
        forId() {
            return 'upload_' + this.id
        },

        style(fix) {
            var line = this.height
            if (fix) {
                line = this.height - fix
            }
            var style =
                'width:' +
                this.width +
                'px;height:' +
                this.height +
                'px;line-height:' +
                line +
                'px;'
            return style
        },

        changeFile(file) {
            if (!file.target.files[0]) return false
            this.loading = true
            var forms = new FormData()
            var configs = {
                headers: { 'Content-Type': 'multipart/form-data' }
            }
            forms.append('file', file.target.files[0])

            if (this.extend != undefined) {
                var extend = Object.keys(this.extend)
                extend.forEach(item => {
                    forms.append(item, this.extend[item])
                })
            }

            this.$post(this.action, forms, false).then(res => {
                this.loading = false
                if (res.code == 200) {
                    this.$emit('update', res.data.url)
                    this.$emit('on-success', res.data, this.extend)
                } else {
                    this.$Message.warning(res.message)
                }
            })

            this.$refs.input.value = null
        }
    }
}
</script>


<style lang="less" scoped>
@width: 120px;
@height: 120px;
.image-wrap {
    width: @width;
    height: @height;
    border: 1px solid #eaeced;
    display: block;
    position: relative;
    border-radius: 2px;
    overflow: hidden;
    line-height: @height;
}
.image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.image-wrap:hover .mask {
    cursor: pointer;
    opacity: 1;
    -webkit-transition: all 0.2s ease-in-out;
    -moz-transition: all 0.2s ease-in-out;
    -o-transition: all 0.2s ease-in-out;
    -ms-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out;
}
.image-wrap .mask {
    opacity: 0;
    background: rgba(0, 0, 0, 0.7);
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    text-align: center;
    color: #fff;
    -webkit-transition: all 0.2s ease-in-out;
    -moz-transition: all 0.2s ease-in-out;
    -o-transition: all 0.2s ease-in-out;
    -ms-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out;
}
.label {
    width: @width;
    display: inline-block;
}
.upload {
    width: @width;
    height: @height;
    line-height: (@height - 4);
    border: 1px solid #eaeced;
    cursor: pointer;
    text-align: center;
    border-radius: 2px;
    position: relative;
}
</style>
