import Vue from 'vue';
import Router from 'vue-router';
import Home from './components/views/Home';
import Error404 from "./components/errors/Error404";
import CreateDemand from "./components/views/CreateDemand";
import store from './store';
import api from "./api";
import LoadingMessage from "./components/views/LoadingMessage";
import SelectEntity from "./components/views/SelectEntity";
import OAuthCallback from "./components/views/OAuthCallback";
import ManageDemands from "./components/views/ManageDemands";

Vue.use(Router);

// ------------------------

// routes shared between root app and organization app
const routes = [
    {
        path: '/',
        name: 'home',
        title: 'Home',
        component: Home,
        meta: {
            guestAllowed: true,
        },
    },
    {
        path: '/selecionar-entidade',
        name: 'selectionar-entidade',
        title: 'Selecionar Entidade',
        component: SelectEntity,
        meta: {
            noEntityRequired: true,
        },
    },
    {
        path: '/nova-demanda',
        name: 'nova-demanda',
        title: 'Nova Demanda',
        component: CreateDemand,
    },
    {
        path: '/gerenciar-demandas',
        name: 'gerenciar-demandas',
        title: 'Gerenciar Demandas',
        component: ManageDemands,
    },
    {
        path: '/oauth-callback',
        meta: {
            layout: "compact-layout",
            guestAllowed: true,
        },
        component: OAuthCallback,
        props: {
            message: 'Autenticando usuário...'
        }
    },
    {
        path: '/preparando-login',
        meta: {
            layout: "compact-layout",
            guestAllowed: true,
        },
        component: LoadingMessage,
        props: {
            message: 'Preparando autenticação...'
        }
    },
    {
        path: '*',
        name: '404',
        title: '404',
        meta: {
            layout: "compact-layout",
            guestAllowed: true,
        },
        component: Error404,
    },
];

const router = new Router({
    mode: 'history',
    base: '/',
    routes,
});

router.beforeEach((to, from, next) => {

    // login required and not logged, do the login process
    if (!to.meta.guestAllowed && !store.getters.isLogged) {
        api.authorizeLogin();
        next('/preparando-login');
    }

    // login required, entity required and no entity selected
    if (!to.meta.guestAllowed && !to.meta.noEntityRequired && !store.getters.activeEntityId) {
        next('/selecionar-entidade');
    }

    // login required, entity required and the selected entity is not available for the user (logged) (invalid state)
    if (!to.meta.guestAllowed && !to.meta.noEntityRequired && store.getters.user && !store.getters.activeEntity) {
        store.commit('setEntity', null);
        next('/selecionar-entidade');
    }

    next();
});



export default router;