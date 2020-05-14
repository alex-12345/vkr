import axios from 'axios'

export default {
    state: {
        token: localStorage.getItem("user-token") || "",
        status: "",
    },
    getters: {
        isAuthenticated: state => !!state.token,
        authStatus: state => state.status
    },
    actions: {
        authRequest(ctx, user) {
            return new Promise(function(resolve, reject) {
                ctx.commit('updateAuthRequest')
                axios.post('http://sapechat.ru/api/auth/login_check', user)
                .then(response => {
                    console.log('data: ', response.data)
                    const token = response.data.token
                    localStorage.setItem('user-token', token)
                    ctx.commit('updateAuthSuccess', token)
                    ctx.commit('updateSubmitStatusLogin', 'OK')
                    resolve (response.data)
                })
                .catch(error => {
                    console.log('error: ', error)
                    localStorage.removeItem('user-token')
                    ctx.commit('updateAuthSuccess')
                    ctx.commit('updateSubmitStatusLogin', 'ERROR')
                    reject (error.data)
                });
            });
        }
    },
    mutations: {
        updateAuthRequest(state) {
            state.status = 'loading'
        },
        updateAuthSuccess(state, token) {
            state.status = 'success'
            state.token = token
        },
        updateAuthError(state) {
            state.status = 'error'
        }
    }
}