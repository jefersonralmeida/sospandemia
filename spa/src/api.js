import axios from "axios";
import store from "./store";
import randomstring from "randomstring";
import CryptoJs from 'crypto-js';
import router from './router';

const api = {

    baseURL: () => {
        let url = `${window.location.protocol}//${window.location.hostname}`;
        const port = process.env.VUE_APP_API_PORT ? process.env.VUE_APP_API_PORT : window.location.port;
        return `${url}:${port}`;
    },

    httpClient: function() {

        // base config
        const config = {
            baseURL: `${this.baseURL()}/api`,
            headers: {},
        };

        // add auth token
        if (store.state.oauthToken) {
            config.headers['Authorization'] = `Bearer ${store.state.oauthToken.access_token}`;
        }

        // add organization
        // if (store.state.organization) {
        //     config.headers['X-Wflow-Organization'] = store.state.organization.id;
        // }

        const httpClient = axios.create(config);

        // response interceptor
        httpClient.interceptors.response.use(
            response => response,
            error => {
                switch (error.response.status) {
                    // case 401: // Unauthorized
                    //     // TODO - handle refresh
                    //     store.commit('logout');
                    //     router.push('/login');
                    //     break;
                    // case 403: // Forbidden
                    //     // TODO
                    //     break;
                    case 503:
                        router.push('/503');
                        break;
                }
                return Promise.reject(error);
            }
        );

        return httpClient;
    },

    organizationByDomain: function(domain) {
        return this.httpClient().get(`organizations/${domain}/public`);
    },

    authorizeLogin: function () {

        const currentDomain = window.location.hostname;
        const currentDomainProtocol = window.location.protocol;
        const currentDomainPort = window.location.port;
        let redirectUrlPrefix = `${currentDomainProtocol}//${currentDomain}`;
        if (currentDomainPort) {
            redirectUrlPrefix += `:${currentDomainPort}`;
        }

        const redirectToAuthorizePage = (clientId, redirectUrl) => {
            const state = randomstring.generate();
            const codeVerifier = randomstring.generate(128);
            store.commit('setOauthInfo', {
                clientId,
                redirectUrl,
                state,
                codeVerifier,
            });
            const codeChallenge = CryptoJs.SHA256(codeVerifier).toString(CryptoJs.enc.Base64).replace(/=/g, '').replace(/\+/g, '-').replace(/\//g, '_');
            const queryData = {
                client_id: clientId,
                redirect_uri: redirectUrl,
                response_type: 'code',
                scope: '*',
                state: state,
                code_challenge: codeChallenge,
                code_challenge_method: 'S256',
            };
            const query = Object.keys(queryData).map(key => `${key}=${encodeURI(queryData[key])}`).join('&');
            const url = `${this.baseURL(true)}/oauth/authorize?${query}`;
            window.location.replace(url);
        };

        redirectToAuthorizePage(1, `${redirectUrlPrefix}/oauth-callback`);

    },

    login: function(stateCode, authorizationCode) {

        if (store.state.oauthInfo.state !== stateCode) {
            router.push('/404'); // TODO - Mandar para página de erro...
        }

        const requestData = {
            grant_type: 'authorization_code',
            client_id: store.state.oauthInfo.clientId,
            redirect_uri: store.state.oauthInfo.redirectUrl,
            code_verifier: store.state.oauthInfo.codeVerifier,
            code: authorizationCode,
        };

        axios.post(`${this.baseURL()}/oauth/token`, requestData). then(response => {
            store.commit('setOauthToken', response.data);
            store.dispatch('loadProfile').then(response => {
                router.push('/gerenciar-demandas');
            });
        });
    },

    logout: function() {

        // first revoke the token on backend (async notify)
        const promise = this.httpClient().post('logout');

        // then drop the token from vuex (local)
        store.commit('logout');

        // return the promise to the caller
        return promise;

    },

    profile: function() {
        return this.httpClient().get('profile');
    },

    indexDemandsByEntity: function(page) {
        return this.httpClient().get(`entities/${store.getters.activeEntityId}/demands?page=${page}`);
    },

    createDemand: function(data) {
        return this.httpClient().post(`entities/${store.getters.activeEntityId}/new-demand`, data);
    },

    getDemand: function(demandId) {
        return this.httpClient().get(`demands/${demandId}`);
    },

    updateDemand: function(demandId, data) {
        return this.httpClient().put(`demands/${demandId}`, data);
    },

    deleteDemand: function(demandId) {
        return this.httpClient().delete(`demands/${demandId}`);
    },

    searchDemands: function(query, page, filterType, filterParam) {
        let params = {
            query, page
        }
        if(filterType)
            params[filterType] = filterParam + ''
        return this.httpClient().get('demands/search', {
            params
        });
    },


    createEntity: function(data) {
        return this.httpClient().post('entities/', data);
    },

    getEntity: function(entityId) {
        return this.httpClient().get(`entities/${entityId}`);
    },

    updateEntity: function(entityId, data) {
        return this.httpClient().put(`entities/${entityId}`, data);
    },
    leaveEntity: function(entityId) {
        return this.httpClient().post(`entities/${entityId}/leave`);
    },
    inviteToEntity: function(entityId, userId){
        return this.httpClient().post(`entities/${entityId}/invite/${userId}`);
    },

    getStates: function(){
        return this.httpClient().get("states");
    },
    getDistricts: function(stateId, query){
        return this.httpClient().get(`states/${stateId}/search-districts?query=${query}`);
    }
};

export default api;