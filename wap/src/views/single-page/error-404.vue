<template>
    <div class="flex-center position-ref full-height">
        <div class="code">{{code}}</div>·
        <div class="message">{{message}}</div>
    </div>
</template>

<script>
export default {
    props: {
        code: {
            default: 404,
            type: [String, Number]
        },
        message: {
            default: 'Not Fount',
            type: [String, Number]
        }
    }
}
</script>

<style lang="less" scoped>
.full-height {
    height: 100vh;
    background-color: #fff;
    color: #636b6f;
    font-family: 'Nunito', sans-serif;
    height: 100vh;
    margin: 0;
}

.flex-center {
    align-items: center;
    display: flex;
    justify-content: center;
}

.position-ref {
    position: relative;
}

.code {
    font-size: 17px;
    padding: 0 15px 0 15px;
    text-align: center;
}

.message {
    font-size: 15px;
    text-align: center;
    padding: 10px;
}
</style>

