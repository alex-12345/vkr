export default {
    state: {
        name: 'Иван',
        secondName: 'Иванов',
        email: 'johndoe@mail.com',
        password: 'rbrbvjhf',
    },
    getters: {
        nameUser: state => state.name,
        secondNameUser: state => state.secondName,
        emailUser: state => state.email,
        passwordUser: state => state.password,
    },
    actions: {
        changePassword(ctx, newPass) {
            ctx.commit('updatePassword', newPass)
        }
    },
    mutations: {
        updatePassword(state, newPass) {
            state.password = newPass
        }
    }
}