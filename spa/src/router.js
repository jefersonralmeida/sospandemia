import Vue from 'vue';
import Router from 'vue-router';
import Home from './components/views/Home';
import store from './store';
import api from "./api";
import LoadingMessage from "./components/views/LoadingMessage";
import ManageEntities from "./components/views/ManageEntities";
import OAuthCallback from "./components/views/OAuthCallback";
import ManageDemands from "./components/views/ManageDemands";
import GenericError from "./components/errors/GenericError";
import Equipe from "./components/views/Equipe";
import Terms from "./components/views/terms";

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
        path: '/gerenciar-demandas',
        name: 'gerenciar-demandas',
        title: 'Gerenciar Demandas',
        component: ManageDemands,
    },
    {
        path: '/gerenciar-entidades',
        name: 'gerenciar_entidades',
        title: 'Gerenciar Entidades',
        component: ManageEntities,
        meta: {
            noEntityRequired: true,
        },
    },
    {
        path: '/sobre-nos',
        name: 'sobre-nos',
        title: 'Equipe',
        component: Equipe,
        meta: {
            guestAllowed: true,
        },
        
    },
    {
        path: '/termos-de-uso-e-privacidade',
        name: 'termos-de-uso-e-privacidade',
        title: 'Termo de uso e privacidade',
        component: Terms,
        meta: {
            guestAllowed: true,
        },
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
        path: '/503',
        name: '503',
        title: '503',
        meta: {
            layout: "compact-layout",
            guestAllowed: true,
        },
        component: GenericError,
        props: {
            errorCode: '503',
            errorTitle: 'Serviço Indisponível',
            errorMessage: 'O sistema está em manutenção. Tente novamente mais tarde.',
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
        component: GenericError,
        props: {
            errorCode: '404',
            errorTitle: 'Página não encontrada',
            errorMessage: 'O endereço que você está acessando não é válido',
        }
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
        next('/gerenciar-entidades');
    }

    // login required, entity required and the selected entity is not available for the user (logged) (invalid state)
    if (!to.meta.guestAllowed && !to.meta.noEntityRequired && store.getters.user && !store.getters.activeEntity) {
        store.commit('setEntity', null);
        next('/gerenciar-entidades');
    }

    next();
});



export default router;