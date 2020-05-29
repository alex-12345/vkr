import axios from 'axios'

export default {
    state: {
        sendingPassword: false,
        sendingCreateUser: false,
        passwordChanged: false,
        worked: false
    },
    getters: {
        sendingPassword: state => state.sendingPassword,
        sendingCreateUser: state => state.CreateUser,
        passwordChanged: state => state.passwordChanged,
        passwordWorked: state => state.worked,
    },
    actions: {
        createUser(ctx, admin) {
            console.log('admin: ', admin)
            return new Promise(function(resolve, reject) {
                axios.post('http://sapechat.ru/api/invites', admin)
                .then(response => {
                    ctx.commit('updateSendingCreateUser')
                    resolve (response.data)
                })
                .catch(error => {
                    ctx.commit('updateSendingCreateUser')
                    reject (error.data)
                });
            });
        },
        changePassword(ctx, pass) {
            return new Promise(function(resolve, reject) {
                axios.put('http://sapechat.ru/api/users/current/password', pass)
                .then(response => {
                    ctx.commit('updateSendingPassword')
                    ctx.commit('updatePasswordChanged', true)
                    resolve (response.data)
                })
                .catch(error => {
                    ctx.commit('updateSendingPassword')
                    if (error.response.status == 400) {
                        ctx.commit('updatePasswordChanged', false)
                    }
                    else if (error.response.status == 403) {
                        ctx.commit('updatePasswordChanged', false)
                    }
                    reject (error.data)
                });
            });
        },
        changeSendingPassword(ctx) {
            ctx.commit('updateSendingPassword')
        },
        changeSendingCreateUser(ctx) {
            ctx.commit('updateSendingCreateUser')
        },
        changePasswordChanged(ctx, value) {
            ctx.commit('updatePasswordChanged', value)
        },
        changePasswordWorked(ctx) {
            ctx.commit('updatePasswordWorked')
        },
    },
    mutations: {
        updateSendingPassword(state) {
            state.sendingPassword = !state.sendingPassword
        },
        updateSendingCreateUser(state) {
            state.sendingCreateUser = !state.sendingCreateUser
        },
        updatePasswordChanged(state, value) {
            state.passwordChanged = value
        },
        updatePasswordWorked(state) {
            state.worked = true
        }
    }
}