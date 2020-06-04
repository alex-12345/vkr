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

const token = localStorage.getItem('userToken')
if (token) {
  axios.defaults.headers.common['Authorization'] = token
}

axios.interceptors.response.use(
  (response) => {
    // Вернуть успешный ответ обратно
    console.log('Всё хорошо')
    return response;
  }, (error) => {
    console.log('Ошибка')
    //Возврат любой ошибки, которая не связана с аутентификацией, обратно в вызывающую службу
    console.log(error.response.status)
    if (error.response.status !== 401) {
      console.log('Не 401')
      return new Promise((resolve, reject) => {
        reject(error);
      });
    }

    //Вернём ошибку, если это неверные данные при авторизации
    if (error.config.url == 'http://sapechat.ru/api/auth/login_check') {
      console.log('Ошибка при авторизации')
      
      store.dispatch('removeToken')
      store.dispatch('removeRefreshToken')

      return new Promise((resolve, reject) => {
        reject(error);
      });
    }

    //Выйдите из системы, если обновление токена не работает или пользователь забанен
    console.log(error.config.url)
    if (error.config.url == 'http://sapechat.ru/api/auth/token/refresh') {
      console.log('Обновление токена не работает')
      
      store.dispatch('removeToken')
      store.dispatch('removeRefreshToken')
      router.push('/authorization');

      return new Promise((resolve, reject) => {
        reject(error);
      });
    }
    console.log('Обновим токен')

    //попробовать запрос заново с новым токеном
    return store.dispatch('getNewToken', store.getters.refreshToken)
    .then((token) => {
      console.log('Новый запрос')
      //Новый запрос с новым токеном
      const config = error.config;
      config.headers['Authorization'] = `Bearer ${token}`;

      return new Promise((resolve, reject) => {
        axios.request(config).then(response => {
          resolve(response);
        }).catch((error) => {
          reject(error);
        })
      });

    })
    .catch((error) => {
      console.log('ошибка в новом запросе')
      Promise.reject(error);
    });
  }
)

new Vue({
  store,
  router,
  render: h => h(App),
}).$mount('#app')
