<template>
    <div class="lett-cell-item">
        <div class="lett-cell-label">
            <slot name="title">{{title}}</slot>
        </div>
        <div class="lett-cell-aux">
            <!-- <slot>{{$options.filters.trim(value) }}</slot> -->
            <slot>{{value }}</slot>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        title: {
            type: [String, Number],
            default: ''
        },
        value: {
            type: [String, Number],
            default: ''
        }
    }
}
</script>

<style>
.lett-cell-item {
    clear: both;
}
.lett-cell-item:after {
    clear: both;
    display: table;
    content: '';
    line-height: 0;
}
.lett-cell-label {
    float: left;
    display: block;
    padding: 0 0 2px 0;
    width: 68px;
    line-height: 24px;
    text-align: left;
    color: #666869;
}

.lett-cell-aux {
    line-height: 24px;
    padding: 0 0 2px 0 !important;
    color: #20222a;
    margin-left: 68px;
}
</style>
