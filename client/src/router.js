import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: () => import('./views/HomePage.vue'),
            children: [
                {
                    path: 'authorization',
                    component: () => import('./views/AuthorizationPage')
                },
                {
                    path: 'registration',
                    component: () => import('./views/RegistrationPage'),
                },
                {
                    path: 'registrationPlatform',
                    component: () => import('./views/RegistrationPlatformPage')
                },
                {
                    path: 'registrationUser',
                    component: () => import('./views/RegistrationUserPage')
                } 
            ]
        },    
    ]
})