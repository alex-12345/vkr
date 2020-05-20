import Vue from 'vue'
import store from './store'
import router from './router'
import Vuelidate from 'vuelidate'
import axios from 'axios';
import App from './App.vue'
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

window.axios = axios

Vue.use(Vuelidate)
Vue.use(VueMaterial)

Vue.config.productionTip = false

const token = localStorage.getItem('user-token')
if (token) {
  axios.defaults.headers.common['Authorization'] = token
}

new Vue({
  store,
  router,
  render: h => h(App),
}).$mount('#app')
