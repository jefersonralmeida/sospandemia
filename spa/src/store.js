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
        uiLoaded: true,
    },
    mutations: {
        setEntity (state, entityId) {
            state.entity = entityId;
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
        uiLoaded (state) {
            state.uiLoaded = true;
        },
        uiLoading (state) {
            state.uiLoaded = false;
        },
        showMessage (state, payload) {
            state.notificationContent = payload.content
            state.noticicationError = payload.error
        }
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
                commit('setProfile', response.data);
            });
        }
    },
    getters: {
        isLogged: state => state.oauthToken !== null,
        user: state => state.profile ? state.profile : null,
        entities: state => state.profile ? state.profile.entities : null,
        activeEntityId: state => state.entity ? state.entity : null,
        activeEntity: state => state.entity && state.profile ? state.profile.entities.find(entity => entity.id === state.entity) : null,
    },
    plugins: [vuexLocal.plugin],
});

export default store;