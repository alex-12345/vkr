import axios from 'axios'

export default {
    state: {
        status: "",
    },
    getters: {
        authStatus: state => state.status
    },
    actions: {
        authRequest(ctx, object) {
            return new Promise(function(resolve, reject) {
                axios.post('http://' + object.domainName + '/api/auth/login_check', {username: object.username, password: object.password })
                .then(response => {
                    localStorage.domainName = object.domainName

                    //Вызов мутаций из tokenStorage.js
                    ctx.commit('updateToken', response.data.token)
                    ctx.commit('updateRefreshToken', response.data.refresh_token)

                    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`

                    ctx.commit('updateAuthSuccess')
                    ctx.commit('updateSubmitStatusLogin', 'OK')
                    resolve (response.data)
                })
                .catch(error => {
                    ctx.commit('deleteToken')
                    ctx.commit('deleteRefreshToken')

                    ctx.commit('updateAuthSuccess')
                    reject (error)
                });
            });
        },
        createRequestPasswordChange(ctx, object) {
            console.log('email: ', object)
            return new Promise(function(resolve, reject) {
                axios.post('http://' + object.domainName + '/api/recovery', {email: object.email, link: object.link})
                .then(response => {
                    localStorage.idRequestPasswordChange = response.data.data.id
                    resolve (response.data)
                })
                .catch(error => {
                    reject (error.data)
                });
            })
        },
        checkRecovey(ctx, object) {
            return new Promise(function(resolve, reject) {
                axios.get('http://' + localStorage.domainName + '/api/recovery/'+ object.id +'/status?hash=' + object.hash)
                .then(response => {
                    resolve (response.data)
                })
                .catch(error => {
                    reject (error)
                });
            });
        },
        confirmPasswordChange(ctx, object) {
            return new Promise(function(resolve, reject) {
                axios.put('http://' + localStorage.domainName + '/api/recovery/'+ object.id +'/status', {hash: object.hash, password: object.password})
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
        authLogout(ctx) {
            return new Promise(function(resolve) {
                ctx.commit('deleteToken')
                ctx.commit('deleteRefreshToken')
                ctx.commit('deleteUser')
                delete axios.defaults.headers.common['Authorization']
                resolve()
            })
        }
    },
    mutations: {
        updateAuthRequest(state) {
            state.status = 'loading'
        },
        updateAuthSuccess(state) {
            state.status = 'success'
        },
        updateAuthError(state) {
            state.status = 'error'
        },
    }
}