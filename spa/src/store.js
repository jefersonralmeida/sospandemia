import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersistence from 'vuex-persist';
import api from './api';

Vue.use(Vuex);

//const baseDomain = process.env.VUE_APP_BASE_DOMAIN;

// persist everything but auth in localStorage
const vuexLocal = new VuexPersistence({
    storage: window.localStorage,
});

const store = new Vuex.Store({
    state: {
        entity: null,
        oauthToken: null,
        oauthInfo: {
            state: null,
            codeVerifier: null,
        },
        profile: null,
    },
    mutations: {
        setEntity (state, organizationId) {
            state.entity = organizationId;
        },
        setOauthToken (state, authInfo) {
            state.oauthToken = authInfo;
        },
        setUser (state, user) {
            state.auth.user = user;
        },
        setOauthInfo (state, oauthInfo) {
            state.oauthInfo = oauthInfo;
        },
        setProfile (state, profile) {
            state.profile = profile;
        },
        logout (state) {
            state.oauthToken = null;
            state.profile = null;
        },
    },
    actions: {
        findOrganization({state, commit}, domain) {

            // remove the organization
            if (domain === null) {
                commit('setOrganization', null);
            }

            // if the organization is not defined, get it from the api
            if (state.organization === null) {
                api.organizationByDomain(domain).then(response => {
                    commit('setOrganization', response.data)
                });
            }
            // else, do nothing
        },
        loadProfile({commit}) {
            api.profile().then(response => {
                console.log(response.data);
                commit('setProfile', response.data);
            });
        }
    },
    getters: {
        isLogged: state => state.oauthToken !== null,
        user: state => state.profile ? state.profile : null,
        entities: state => state.auth ? state.auth.user.entities : null,
    },
    plugins: [vuexLocal.plugin],
});

export default store;