<template>
    <Modal title="图片预览" :value="showModal" :maskClosable="true" width="480" @on-visible-change="visibleChange" @on-cancel="visibleChange" :footer-hide="true">

        <div v-if="items.length == 0" style="none-image">
            <span>暂无图片</span>
        </div>

        <div v-else>
            <Carousel v-if="show" :value="current" loop easing="esae" dots="none" :height="480">
                <CarouselItem v-for="(item,index) in items" :key="index">
                    <div class="img-wrap">
                        <img :src="item" />
                    </div>
                </CarouselItem>
            </Carousel>
        </div>

    </Modal>
</template>

<script>
export default {
    props: {
        current: {
            type: Number,
            default: 0
        },
        items: {
            type: Array,
            default: []
        },
        show: {
            type: Boolean,
            default: false
        }
    },
    data: () => {
        return {
            showModal: false
        }
    },

    watch: {
        show(value) {
            this.showModal = this.show
        }
    },
    methods: {
        visibleChange(value) {
            this.$emit('on-visible-change', value)
        }
    }
}
</script>

<style scope >
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
