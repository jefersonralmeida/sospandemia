<template>
    <div class="loading-screen row h-100 d-flex justify-content-center align-items-center">
        <div class="loading-box">
            Loading...
        </div>
    </div>
</template>

<script>
    export default {
        name: 'loading-screen',
    }
</script>

<style scoped="true">
    .loading-screen {
        background-color: lightgrey;
    }
    .loading-box {
        font-size: 26px;
    }
</style>