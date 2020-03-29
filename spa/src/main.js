import Vue from 'vue'
import App from './App.vue'
import router from './router.js'
import store from './store.js'
import 'bootstrap'

// css
import 'bootstrap/dist/css/bootstrap.css';
import '@fortawesome/fontawesome-free/css/all.css';

Vue.config.productionTip = false;

new Vue({
  render: h => h(App),
  store,
  router
}).$mount('#app');
