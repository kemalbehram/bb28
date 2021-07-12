<template>
    <ul class="ue-img-group mb-6">
        <li v-for="(item, index) in items" :key="index">
            <img :src="item" @click="onClick(index)" />
        </li>
    </ul>
</template>

<script>
export default {
    props: {
        items: Array
    },

    methods: {
        onClick(index) {
            this.$emit('on-click', index)
        }
    }
}
</script>

<style>
.ue-img-group {
    margin: 0 -4px 0;
}
.ue-img-group:after {
    clear: both;
    display: table;
    content: '';
    line-height: 0;
}
.ue-img-group li {
    list-style: none;
    width: 64px;
    height: 64px;
    margin: 4px;
    float: left;
    position: relative;
    overflow: hidden;
    border: 1px solid #f1f3f4;
    text-align: center;
    vertical-align: middle;
}
/* .ue-img-group li:before {
  content: ' ';
  display: inline-block;
  vertical-align: middle;
  height: 100%;
} */
.ue-img-group img {
    width: 64px;
    height: 64px;
    vertical-align: middle;
    object-fit: cover;
}
</style>
