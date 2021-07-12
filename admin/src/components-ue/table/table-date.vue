<template>
    <span class="custom-tag">{{$moment(date).format('MM-DD HH:mm:ss')}}</span>
</template>

<script>
export default {
    props: {
        date: String
    }
}
</script>