import axios from 'axios'

export default {
    state: {
        
    },
    actions: {
        superAdminInfo(ctx, key) {
            return new Promise(function(resolve, reject) {
                axios.get('http://sapechat.ru/api/users/superadmin?workspace_key=' + key)
                .then(response => {
                    resolve (response.data)
                })
                .catch(error => {
                    reject (error.response)
                });
            });
        },
        putSuperAdmin(ctx, object) {
            return new Promise(function(resolve, reject) {
                axios.put('http://sapechat.ru/api/invites/superadmin?workspace_key=' + object.key, object.admin)
                .then(response => {
                    resolve (response.data)
                })
                .catch(error => {
                    reject (error.data)
                });
            });
        },
        createSuperAdmin(ctx, object) {
            return new Promise(function(resolve, reject) {
                axios.post('http://sapechat.ru/api/invites/superadmin?workspace_key=' + object.key, object.admin)
                .then(response => {
                    resolve (response.data)
                })
                .catch(error => {
                    console.log('Ошибка')
                    reject (error.data)
                });
            });
        },
        getInvitesStatus(ctx, object) {
            if (object.status == 1) {
                return new Promise(function(resolve, reject) {
                    axios.get('http://sapechat.ru/api/invites/'+ object.id +'/status?hash=' + object.hash + '&superadmin=' + object.status)
                    .then(response => {
                        resolve (response.data)
                    })
                    .catch(error => {
                        reject (error.data)
                    });
                });
            }
            else if (object.status == undefined) {
                console.log('Я тут')
                return new Promise(function(resolve, reject) {
                    axios.get('http://sapechat.ru/api/invites/'+ object.id +'/status?hash=' + object.hash)
                    .then(response => {
                        resolve (response.data)
                    })
                    .catch(error => {
                        reject (error.data)
                    });
                });
            }
        },
        confirmInviteAdmin(ctx, object) {
            return new Promise(function(resolve, reject) {
                axios.put('http://sapechat.ru/api/invites/'+ object.id +'/status', {hash: object.hash})
                .then(response => {
                    ctx.commit('updateToken', response.data.token)
                    ctx.commit('updateRefreshToken', response.data.refresh_token)
                    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
                    resolve (response.data)
                })
                .catch(error => {
                    reject (error.data)
                });
            });
        },
        confirmInviteUser(ctx, object) {
            return new Promise(function(resolve, reject) {
                axios.put('http://sapechat.ru/api/invites/'+ object.id +'/status', {hash: object.hash, password: object.password})
                .then(response => {
                    ctx.commit('updateToken', response.data.token)
                    ctx.commit('updateRefreshToken', response.data.refresh_token)
                    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
                    resolve (response.data)
                })
                .catch(error => {
                    reject (error.data)
                });
            });
        },
    },
    mutations: {
        
    },
    getters: {
        
    }
}