export default {
    state: {
        idUser: 1,
        nameUser: 'Иван',
        secondNameUser: 'Иванов',
        emailUser: 'johndoe@mail.com',
        passwordUser: 'rbrbvjhf',
        avatarUser: 'https://placeimg.com/40/40/people/5'
    },
    getters: {
        idUser: state => state.idUser,
        nameUser: state => state.nameUser,
        secondNameUser: state => state.secondNameUser,
        emailUser: state => state.emailUser,
        passwordUser: state => state.passwordUser,
        avatarUser: state => state.avatarUser,
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