import axios from 'axios'

export default {
    state: {
        token: localStorage.getItem("userToken") || null,
        refreshToken: localStorage.getItem("userRefreshToken") || null,
    },
    getters: {
        getToken: state => state.token,
        refreshToken: state => state.refreshToken,
        isAuthenticated: state => !!state.token,
    },
    actions: {
        getNewToken(ctx, refreshToken) {
            return new Promise(function(resolve, reject) { 
                axios
                    .post('http://sapechat.ru/api/auth/token/refresh', {"refresh_token": refreshToken})
                    .then(response => {

                        ctx.commit('updateToken', response.data.token)
                        ctx.commit('updateRefreshToken', response.data.refresh_token)
              
                        resolve(response.data.token);
                    })
                    .catch((error) => {
                        console.log('Я тут')
                        reject(error);
                    });
            });
        },
        removeToken(ctx) {
            ctx.commit('deleteToken')
        },
        removeRefreshToken(ctx) {
            ctx.commit('deleteRefreshToken')
        }
    },
    mutations: {
        updateToken(state, token) {
            localStorage.userToken = token
            state.token = token
        },
        deleteToken(state) {
            localStorage.removeItem('userToken')
            state.token = ''
        },
        updateRefreshToken(state, refreshToken) {
            localStorage.userRefreshToken = refreshToken
            state.refreshToken = refreshToken
        },
        deleteRefreshToken(state) {
            localStorage.removeItem('userRefreshToken')
            state.refreshToken = ''
        },
    }
}