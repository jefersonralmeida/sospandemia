<template>
    <div>
        <form method="post" :action="logoutUrl" ref="logoutForm" style="display: none;"/>
        <div v-if="$store.getters.isLogged">
            <a class="nav-link dropdown-toggle d-none d-sm-block" type="button" id="dropdownUserButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-24" v-if="avatar===null">{{ initials }}</div>
                <img class="avatar avatar-24" v-else :src="avatar"/>&nbsp;
                <span class="d-none d-lg-inline">{{ userName }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownUserButton">
                <a class="dropdown-item" href="#" @click="logout()"><span class="fa fa-sign-out-alt"></span> Logout</a>
            </div>
        </div>
    </div>
</template>

<script>
    import api from '../../../api';
    export default {
        name: 'logged-user-menu',
        props: ['avatar', 'userName', 'initials'],
        methods: {
            logout() {
                api.logout().then(() => {

                    // once logged out from the api, logout on the main app
                    this.$refs.logoutForm.submit();

                });
            }
        },
        computed: {
            logoutUrl() {
                return `${api.baseURL(true)}/web/logout`;
            }
        }
    }
</script>

<style scoped="true">
    .profile-userpic {
        margin: 0;
        width: 35px;
        height: 35px;
        border-radius: 9999px !important;
    }
</style>