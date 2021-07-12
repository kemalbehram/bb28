<template>
    <div class="flex-center position-ref full-height">
        <div class="code">500</div>·
        <div class="message">Server error</div>
    </div>
</template>

<style scoped>
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
    font-size: 26px;
    padding: 0 15px 0 15px;
    text-align: center;
}

.message {
    font-size: 25px;
    text-align: center;
    padding: 10px;
}
</style>

