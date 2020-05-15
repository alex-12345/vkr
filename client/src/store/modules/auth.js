import axios from 'axios'

export default {
    state: {
        user: {
            "username": '',
            "password": ''
        },
        token: localStorage.getItem("user-token") || "",
        status: "",
    },
    getters: {
        getUser: state => state.user,
        isAuthenticated: state => !!state.token,
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
                    const token = response.data.token
                    localStorage.setItem('user-token', token)
                    axios.defaults.headers.common['Authorization'] = token
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
        },
        authLogout(ctx) {
            return new Promise(function(resolve) {
                ctx.commit('updateAuthLogout')
                localStorage.removeItem('user-token')
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
        updateAuthSuccess(state, token) {
            state.status = 'success'
            state.token = token
        },
        updateAuthError(state) {
            state.status = 'error'
        },
        updateAuthLogout(state) {
            state.token = "";
        }
    }
}