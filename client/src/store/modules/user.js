import axios from 'axios'

export default {
    state: {
        idUser: localStorage.getItem("user_id") || null,
        nameUser: localStorage.getItem("first_name") || null,
        secondNameUser: localStorage.getItem("second_name") || null,
        emailUser: localStorage.getItem("email_user") || null,
        avatarUser: localStorage.getItem("main_photo") || null,
        descriptionUser: localStorage.getItem("description") || null,
        rolesUser: localStorage.getItem("roles") || null,
        isLockedUser: localStorage.getItem("is_locked") || null,
        isActiveUser: localStorage.getItem("is_active") || null,
        registrationDateUser: localStorage.getItem("registration_date") || null,
        initials: {
            first: localStorage.getItem("initial_first") || null,
            second: localStorage.getItem("initial_second") || null,
        }
    },
    getters: {
        idUser: state => state.idUser,
        nameUser: state => state.nameUser,
        secondNameUser: state => state.secondNameUser,
        emailUser: state => state.emailUser,
        passwordUser: state => state.passwordUser,
        avatarUser: state => state.avatarUser,
        descriptionUser: state => state.descriptionUser,
        rolesUser: state => state.rolesUser,
        isLockedUser: state => state.isLockedUser,
        isActiveUser: state => state.isActiveUser,
        registrationDateUser: state => state.registrationDateUser,
        initials: state => state.initials,
    },
    actions: {
        getCurrentUserInfo(ctx) {
            return new Promise(function(resolve, reject) {
                axios.get('http://sapechat.ru/api/users/current')
                .then(response => {
                    ctx.commit('updateIdUser', response.data.data.id)
                    ctx.commit('updateNameUser', response.data.data.first_name)
                    ctx.commit('updateSecondNameUser', response.data.data.second_name)
                    ctx.commit('updateEmailUser', response.data.data.email)
                    ctx.commit('updateAvatarUser', response.data.data.main_photo)
                    ctx.commit('updateDescriptionUser', response.data.data.description)
                    ctx.commit('updateRolesUser', response.data.data.roles)
                    ctx.commit('updateIsLockedUser', response.data.data.is_locked)
                    ctx.commit('updateIsActiveUser', response.data.data.is_active)
                    ctx.commit('updateRegistrationDateUser', response.data.data.registration_date)
                    resolve (response.data)
                })
                .catch(error => {
                    console.log('Ошибка')
                    reject (error.data)
                });
            });
        },
        changeIdUser(ctx, id) {
            ctx.commit('updateIdUser', id)
        },
        changeNameUser(ctx, firstName) {
            ctx.commit('updateNameUser', firstName)
        },
        changeSecondNameUser(ctx, lastName) {
            ctx.commit('updateSecondNameUser', lastName)
        },
        changeEmailUser(ctx, email) {
            ctx.commit('updateEmailUser', email)
        },
        changeAvatarUser(ctx, photo) {
            ctx.commit('updateAvatarUser', photo)
        },
        changeDescriptionUser(ctx, description) {
            ctx.commit('updateDescriptionUser', description)
        },
        changeRolesUser(ctx, roles) {
            ctx.commit('updateRolesUser', roles)
        },
        changeIsLockedUser(ctx, isLocked) {
            ctx.commit('updateIsLockedUser', isLocked)
        },
        changeIsActiveUser(ctx, isActive) {
            ctx.commit('updateIsActiveUser', isActive)
        },
        changeRegistrationDateUser(ctx, registrationDate) {
            ctx.commit('updateRegistrationDateUser', registrationDate)
        },
        removeUser(ctx) {
            ctx.commit('deleteUser')
        },
    },
    mutations: {
        updateIdUser(state, id) {
            localStorage.user_id = id
            state.idUser = id
        },
        updateNameUser(state, firstName) {
            localStorage.first_name = firstName
            state.nameUser = firstName
            localStorage.initial_first = firstName[0]
            state.initials.first = state.nameUser[0]
        },
        updateSecondNameUser(state, lastName) {
            localStorage.second_name = lastName
            state.secondNameUser = lastName
            localStorage.initial_second = lastName[0]
            state.initials.second = state.secondNameUser[0]
        },
        updateEmailUser(state, email) {
            localStorage.email_user = email
            state.emailUser = email
        },
        updateAvatarUser(state, photo) {
            localStorage.main_photo = photo
            state.avatarUser = photo
        },
        updateDescriptionUser(state, description) {
            localStorage.description_user = description
            state.descriptionUser = description
        },
        updateRolesUser(state, roles) {
            localStorage.roles_user = roles
            state.rolesUser = roles
        },
        updateIsLockedUser(state, isLocked) {
            localStorage.is_locked = isLocked
            state.isLockedUser = isLocked
        },
        updateIsActiveUser(state, isActive) {
            localStorage.is_active = isActive
            state.isActiveUser = isActive
        },
        updateRegistrationDateUser(state, registrationDate) {
            localStorage.registration_date = registrationDate
            state.registrationDateUser = registrationDate
        },
        deleteUser(state) {
            localStorage.removeItem('user_id')
            state.idUser = ''
            localStorage.removeItem('first_name')
            state.nameUser = ''
            localStorage.removeItem('initial_first')
            state.initials.first = ''
            localStorage.removeItem('second_name')
            state.secondNameUser = ''
            localStorage.removeItem('initial_second')
            state.initials.second = ''
            localStorage.removeItem('email_user')
            state.emailUser = ''
            localStorage.removeItem('main_photo')
            state.avatarUser = ''
            localStorage.removeItem('description_user')
            state.descriptionUser = ''
            localStorage.removeItem('roles_user')
            state.rolesUser = ''
            localStorage.removeItem('is_locked')
            state.isLockedUser = ''
            localStorage.removeItem('is_active')
            state.isActiveUser = ''
            localStorage.removeItem('registration_date')
            state.registrationDateUser = ''
        },
    }
}