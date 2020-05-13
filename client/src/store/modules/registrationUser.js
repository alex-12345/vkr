export default {
    state: {
        formItemArr: [
            {id: 1, title: 'Имя', type: 'text', required: true, error: false, alpha: true,
                warningItemArr: [
                    {id: 1, title: "Имя должно состоять только из букв", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true}
                ]
            },
            {id: 2, title: 'Фамилия', type: 'text', required: true, error: false, alpha: true,
                warningItemArr: [
                    {id: 1, title: "Фамилия должна состоять из букв", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true}
                ]
            },
            {id: 3, title: 'Email', type: 'text', required: true, error: false, email: true,
                warningItemArr: [
                    {id: 1, title: "Email некорректен", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true},
                ]
            },
            {id: 4, title: 'Пароль', type: 'password', required: true, error: false, minLength: true,
                warningItemArr: [
                    {id: 1, title: "Пароль должен иметь как минимум 6 сиволов", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true},
                ]
            },
            {id: 5, title: 'Повторите пароль', type: 'password', required: true, error: false, sameAsPassword: true,
                warningItemArr: [
                    {id: 1, title: "Пароли должны быть идентичны", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true},
                ]
            }
        ],
        name: '',
        secondName: '',
        email: '',
        password: '',
        repeatPassword: '',
        submitStatus: null
    },
    actions: {
        changeValidationNameRegisterUser(ctx, vName) {
            ctx.commit('updateValidationNameRegisterUser', vName)
        },
        changeNameRegisterUser(ctx, name) {
            ctx.commit('updateNameRegisterUser', name)
        },
        changeValidationSecondNameRegisterUser(ctx, vSecondName) {
            ctx.commit('updateValidationSecondNameRegisterUser', vSecondName)
        },
        changeSecondNameRegisterUser(ctx, secondName) {
            ctx.commit('updateSecondNameRegisterUser', secondName)
        },
        changeValidationEmailRegisterUser(ctx, vEmail) {
            ctx.commit('updateValidationEmailRegisterUser', vEmail)
        },
        changeEmailRegisterUser(ctx, email) {
            ctx.commit('updateEmailRegisterUser', email)
        },
        changeValidationPasswordRegisterUser(ctx, vPassword) {
            ctx.commit('updateValidationPasswordRegisterUser', vPassword)
        },
        changePasswordRegisterUser(ctx, password) {
            ctx.commit('updatePasswordRegisterUser', password)
        },
        changeValidationRepeatPasswordRegisterUser(ctx, vRepeatPassword) {
            ctx.commit('updateValidationRepeatPasswordRegisterUser', vRepeatPassword)
        },
        changeRepeatPasswordRegisterUser(ctx, repeatPassword) {
            ctx.commit('updateRepeatPasswordRegisterUser', repeatPassword)
        },
        changeSubmitStatusRegisterUser(ctx, value) {
            ctx.commit('updateSubmitStatusRegisterUser', value)
        }
    },
    mutations: {
        updateValidationNameRegisterUser(state, vName) {
            state.formItemArr[0].error = vName.invalid
            state.formItemArr[0].required = vName.required
            state.formItemArr[0].warningItemArr[1].error = vName.required
            state.formItemArr[0].alpha = vName.alpha
            state.formItemArr[0].warningItemArr[0].error = vName.alpha
        },
        updateNameRegisterUser(state, name) {
            state.name = name
        },
        updateValidationSecondNameRegisterUser(state, vSecondName) {
            state.formItemArr[1].error = vSecondName.invalid
            state.formItemArr[1].required = vSecondName.required
            state.formItemArr[1].warningItemArr[1].error = vSecondName.required
            state.formItemArr[1].alpha = vSecondName.alpha
            state.formItemArr[1].warningItemArr[0].error = vSecondName.alpha
        },
        updateSecondNameRegisterUser(state, secondName) {
            state.secondName = secondName
        },
        updateValidationEmailRegisterUser(state, vEmail) {
            state.formItemArr[2].error = vEmail.invalid
            state.formItemArr[2].required = vEmail.required
            state.formItemArr[2].warningItemArr[1].error = vEmail.required
            state.formItemArr[2].email = vEmail.email
            state.formItemArr[2].warningItemArr[0].error = vEmail.email
        },
        updateEmailRegisterUser(state, email) {
            state.email = email
        },
        updateValidationPasswordRegisterUser(state, vPassword) {
            state.formItemArr[3].error = vPassword.invalid
            state.formItemArr[3].required = vPassword.required
            state.formItemArr[3].warningItemArr[1].error = vPassword.required
            state.formItemArr[3].minLength = vPassword.minLength
            state.formItemArr[3].warningItemArr[0].error = vPassword.minLength
        },
        updatePasswordRegisterUser(state, password) {
            state.password = password
        },
        updateValidationRepeatPasswordRegisterUser(state, vRepeatPassword) {
            state.formItemArr[4].error = vRepeatPassword.invalid
            state.formItemArr[4].required = vRepeatPassword.required
            state.formItemArr[4].warningItemArr[1].error = vRepeatPassword.required
            state.formItemArr[4].sameAsPassword = vRepeatPassword.sameAsPassword
            state.formItemArr[4].warningItemArr[0].error = vRepeatPassword.sameAsPassword
        },
        updateRepeatPasswordRegisterUser(state, repeatPassword) {
            state.repeatPassword = repeatPassword
        },
        updateSubmitStatusRegisterUser(state, value) {
            state.submitStatus = value
        }
    },
    getters: {
        formItemArrRegisterUser(state) {
            return state.formItemArr
        },
        nameRegisterUser(state) {
            return state.name
        },
        secondNameRegisterUser(state) {
            return state.secondName
        },
        emailRegisterUser(state) {
            return state.email
        },
        passwordRegisterUser(state) {
            return state.password
        },
        repeatPasswordRegisterUser(state) {
            return state.repeatPassword
        },
        submitStatusRegisterUser(state) {
            return state.submitStatus
        }
    }
}