import axios from 'axios'

export default {
    state: {
        user: {
            "username": '',
            "password": ''
        },
        status: "",
    },
    getters: {
        getUser: state => state.user,
        authStatus: state => state.status
    },
    actions: {
        addUser(ctx, userData) {
            ctx.commit('updateUser', userData)
        },
        authRequest(ctx, user) {
            return new Promise(function(resolve, reject) {
                ctx.commit('updateAuthRequest')
                axios.post('http://sapechat.ru/api/auth/login_check', user)
                .then(response => {
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
                    if (error.response.status == 401) {
                        ctx.commit('updateSubmitStatusLogin', 'AUTH_ERROR')
                    }
                    else if (error.response.status == 403) {
                        ctx.commit('updateSubmitStatusLogin', 'EMAIL_NOT_CONFIRM')
                    }
                    else if (error.response.status == 423) {
                        ctx.commit('updateSubmitStatusLogin', 'USER_IS_BLOCKED')
                    }
                    reject (error.data)
                });
            });
        },
        authLogout(ctx) {
            return new Promise(function(resolve) {
                ctx.commit('deleteToken')
                ctx.commit('deleteRefreshToken')
                delete axios.defaults.headers.common['Authorization']
                resolve()
            })
        }
    },
    mutations: {
        updateUser(state, userData) {
            state.user.username = userData.name
            state.user.password = userData.pass
        },
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