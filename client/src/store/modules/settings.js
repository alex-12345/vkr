import axios from 'axios'

export default {
    state: {
        sendingPassword: false,
        sendingCreateUser: false,
    },
    getters: {
        sendingPassword: state => state.sendingPassword,
        sendingCreateUser: state => state.sendingCreateUser,
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
                    resolve (response.data)
                })
                .catch(error => {
                    ctx.commit('updateSendingPassword')
                    reject (error)
                });
            });
        },
        changeDescription(ctx, description) {
            return new Promise(function(resolve, reject) {
                axios.put('http://sapechat.ru/api/users/current/description', {description: description})
                .then(response => {
                    ctx.commit('updateDescriptionUser', response.data.data.description)
                    resolve (response.data)
                })
                .catch(error => {
                    reject (error)
                });
            });
        },
        changeEmail(ctx, email) {
            return new Promise(function(resolve, reject) {
                axios.post('http://sapechat.ru/api/users/current/email', email)
                .then(response => {
                    resolve (response.data)
                })
                .catch(error => {
                    reject (error)
                });
            });
        },
        confirmChangeEmail(ctx, object) {
            return new Promise(function(resolve, reject) {
                axios.put('http://sapechat.ru/api/users/'+ object.id +'/email', {hash: object.hash})
                .then(response => {
                    ctx.commit('updateToken', response.data.token)
                    ctx.commit('updateRefreshToken', response.data.refresh_token)
                    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
                    resolve (response.data)
                })
                .catch(error => {
                    reject (error)
                });
            });
        },
        changeSendingPassword(ctx) {
            ctx.commit('updateSendingPassword')
        },
        changeSendingCreateUser(ctx) {
            ctx.commit('updateSendingCreateUser')
        },
    },
    mutations: {
        updateSendingPassword(state) {
            state.sendingPassword = !state.sendingPassword
        },
        updateSendingCreateUser(state) {
            state.sendingCreateUser = !state.sendingCreateUser
        },
    }
}