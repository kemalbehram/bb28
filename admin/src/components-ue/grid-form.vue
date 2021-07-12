<template>
    <Row :gutter="gutter">
        <Col :span="left">
            <slot name="left"></slot>
        </Col>
        <Col :span="right">
            <slot name="right"></slot>
        </Col>
    </Row>
</template>

<script>
export default {
    props: {
        left: {
            default: 12,
            type: [String, Number]
        },

        right: {
            default: 12,
            type: [String, Number]
        },

        gutter: {
            default: 16,
            type: [String, Number]
        }
    }
}
</script>

