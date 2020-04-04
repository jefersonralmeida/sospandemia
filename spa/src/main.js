import Vue from 'vue'
import App from './App.vue'
import VModal from 'vue-js-modal'
import router from './router.js'
import store from './store.js'
import Vuetify from "vuetify";


import 'bootstrap'

// css
import 'bootstrap/dist/css/bootstrap.css';
import '@fortawesome/fontawesome-free/css/all.css';
import "vuetify/dist/vuetify.min.css";

Vue.use(Vuetify);

Vue.use(VModal)

Vue.config.productionTip = false;

new Vue({
  render: h => h(App),
  store,
  router
}).$mount('#app');
