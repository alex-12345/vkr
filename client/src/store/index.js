import Vue from 'vue'
import Vuex from 'vuex'
import header from './modules/header'
import authorization from './modules/authorization'
import registrationPlatform from './modules/registrationPlatform'
import registrationUser from './modules/registrationUser'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        header,
        authorization,
        registrationPlatform,
        registrationUser
    }
})