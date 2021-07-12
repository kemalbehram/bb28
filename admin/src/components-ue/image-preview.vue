<template>
    <Modal title="图片预览" :value="showModal" width="480" @on-visible-change="visibleChange" @on-cancel="visibleChange" :footer-hide="true">

        <div v-if="src.length == 0" style="none-image">
            <span>暂无图片</span>
        </div>

        <div v-else class="ttt">
            <Carousel v-if="src.length > 0" :value="current" loop easing="esae" dots="none" :height="480">
                <CarouselItem>
                    <div class="img-wrap">
                        <img :src="src" />
                    </div>
                </CarouselItem>
            </Carousel>
        </div>

    </Modal>
</template>

<script>
export default {
    props: {
        current: 0,
        show: false,
        src: String
    },
    data: () => {
        return {
            showModal: false
        }
    },

    watch: {
        show(value) {
            this.showModal = this.show
        },
        src(value) {
            if (value.length > 0) {
                this.showModal = true
            } else {
                this.showModal = false
            }
        }
    },
    methods: {
        visibleChange(value) {
            this.$emit('on-visible-change', value)
        }
    }
}
</script>

<style lang="less" scope>
.none-image {
    text-align: center;
}
.img-wrap {
    background: #fff !important;
}
.img-wrap img {
    width: 100%;
    height: 480px;
    object-fit: contain;
    display: block;
}
</style>
