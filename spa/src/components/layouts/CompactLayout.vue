<template>
    <div>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <router-link class="nav-link p-0" to="/">
                                <img src="../../assets/logo_app.png" height="50" alt="">
                            </router-link>
                        </div>

                        <div class="card-body compact-body">
                            <router-view></router-view>
                        </div>

                        <div class="card-footer">
                            <router-link class="nav-link p-0" to="/">
                                Home
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'compact-layout',
    }
</script>

<style scoped="true">
    .compact-logo {
        height: 30px;
    }
    .compact-body {
        min-height: 300px;
    }
</style>