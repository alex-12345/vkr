import Vue from 'vue'
import Vuelidate from 'vuelidate'
import axios from 'axios';
import App from './App.vue'
import router from './router'

window.axios = axios

Vue.use(Vuelidate)

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App),
}).$mount('#app')