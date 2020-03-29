<template>
    <div>
        <loading-message :message="message"></loading-message>
    </div>
</template>
<script>
    import LoadingMessage from "./LoadingMessage";
    import api from "../../api";

    export default {
        components: {LoadingMessage},
        props: ['message'],
        mounted() {

            const urlParams = new URLSearchParams(window.location.search);

            api.login(urlParams.get('state'), urlParams.get('code'));

        }
    };
</script>
<style>

</style>