import axios from 'axios'

export default {
    state: {
        sending: false,
        passwordChanged: false,
        worked: false
    },
    getters: {
        sendingPassword: state => state.sending,
        passwordChanged: state => state.passwordChanged,
        passwordWorked: state => state.worked,
    },
    actions: {
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
        changePasswordChanged(ctx, value) {
            ctx.commit('updatePasswordChanged', value)
        },
        changePasswordWorked(ctx) {
            ctx.commit('updatePasswordWorked')
        },
    },
    mutations: {
        updateSendingPassword(state) {
            state.sending = !state.sending
        },
        updatePasswordChanged(state, value) {
            state.passwordChanged = value
        },
        updatePasswordWorked(state) {
            state.worked = true
        }
    }
}