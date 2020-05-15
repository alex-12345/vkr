import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

Vue.use(Router)

const ifNotAuthenticated = (to, from, next) => {
    if (!store.getters.isAuthenticated) {
      next()
      return
    }
    next('/chat')
}
  
const ifAuthenticated = (to, from, next) => {
    if (store.getters.isAuthenticated) {
      next()
      return
    }
    next('/authorization')
}

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: () => import('./views/NotAuthenticated/HomePage.vue'),
            beforeEnter: ifNotAuthenticated,
            children: [
                {
                    path: 'authorization',
                    component: () => import('./views/NotAuthenticated/AuthorizationPage')
                },
                {
                    path: 'registration',
                    component: () => import('./views/NotAuthenticated/RegistrationPage'),
                },
                {
                    path: 'registrationPlatform',
                    component: () => import('./views/NotAuthenticated/RegistrationPlatformPage')
                },
                {
                    path: 'registrationUser',
                    component: () => import('./views/NotAuthenticated/RegistrationUserPage')
                } 
            ]
        },
        {
            path: '/chat',
            component: () => import('./views/Authenticated/MainPage.vue'),
            beforeEnter: ifAuthenticated,
        }    
    ]
})