import Vue from 'vue'
import Vuex from 'vuex'
import header from './modules/header'
import auth from './modules/auth'
import interfaceAuth from './modules/interfaceAuth'
import registrationPlatform from './modules/registrationPlatform'
import registrationUser from './modules/registrationUser'
import dropDownMenu from './modules/dropDownMenu'
import user from './modules/user'
import chats from './modules/chats'
import messages from './modules/messages'
import employees from './modules/employees'
import tokenStorage from './modules/tokenStorage'
import settings from './modules/settings'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        header,
        auth,
        interfaceAuth,
        registrationPlatform,
        registrationUser,
        dropDownMenu,
        user,
        chats,
        messages,
        employees,
        tokenStorage,
        settings
    }
})