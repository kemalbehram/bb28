<template>
    <Modal title="图片预览" v-model="modal" width="480" @on-cancel="$emit('update:visible', false)" :footer-hide="true">

        <div v-if="items.length == 0" class="none-image">
            <span>暂无图片</span>
        </div>

        <div v-else>
            <Carousel v-if="modal" :value="current" loop easing="esae" dots="none" :height="480">
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
        current: 0,
        items: {
            type: Array,
            default: []
        },
        visible: false
    },
    data: () => {
        return {
            modal: false
        }
    },

    watch: {
        visible(value) {
            this.modal = value
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
