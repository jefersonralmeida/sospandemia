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
// import Error403 from "./components/errors/Error403";
// import Login from "./components/views/Login";
// import WorkflowEditor from "./components/views/WorkflowEditor";
// import IndexOrganization from "./components/views/organizations/IndexOrganization";
// import CreateOrganization from "./components/views/organizations/CreateOrganization";
// import SelectOrganization from "./components/views/SelectOrganization";
// import IndexUsers from "./components/views/users/IndexUsers";

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
        component: SelectEntity
    },
    {
        path: '/nova-demanda',
        name: 'home',
        title: 'Home',
        component: CreateDemand,
    },
    // {
    //     path: '/users/index',
    //     name: 'users.index',
    //     title: 'Users',
    //     component: IndexUsers,
    // },
    // {
    //     path: '/organizations/index',
    //     name: 'organizations.index',
    //     title: 'Organizations',
    //     component: IndexOrganization,
    // },
    // {
    //     path: '/organizations/create',
    //     name: 'organizations.create',
    //     title: 'Organizations',
    //     component: CreateOrganization,
    // },
    // {
    //     path: '/wflow-editor',
    //     name: 'wflow-editor',
    //     title: 'Workflow Editor',
    //     component: WorkflowEditor,
    // },
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

    next();
});



export default router;