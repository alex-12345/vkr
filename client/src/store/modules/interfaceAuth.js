export default {
    state: {
        formItemArr: [
            {id: 1, title: 'Имя', type: 'text', required: true, error: false, email: true,
                warningItemArr: [
                    {id: 1, title: "Имя должно состоять только из букв", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true},
                ]
            },
            {id: 2, title: 'Пароль', type: 'password', required: true, error: false, minLength: true,
                warningItemArr: [
                    {id: 1, title: "Пароль должен иметь как минимум 6 сиволов", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true},
                ]
            },
            {id: 3, title: 'Доменное имя', type: 'text', required: true, error: false, url: true,
                warningItemArr: [
                    {id: 1, title: "Некорректное доменное имя", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true}
                ]
            },
        ],
        email: '',
        password: '',
        domainNameLogin: '',
        submitStatus: null
    },
    actions: {
        changeValidationEmailLogin(ctx, vEmail) {
            ctx.commit('updateValidationEmailLogin', vEmail)
        },
        changeEmailLogin(ctx, email) {
            ctx.commit('updateEmailLogin', email)
        },
        changeValidationPasswordLogin(ctx, vPassword) {
            ctx.commit('updateValidationPasswordLogin', vPassword)
        },
        changePasswordLogin(ctx, password) {
            ctx.commit('updatePasswordLogin', password)
        },
        changeValidationDomainNameLogin(ctx, vDomainName) {
            ctx.commit('updateValidationDomainNameLogin', vDomainName)
        },
        changeDomainNameLogin(ctx, domainName) {
            ctx.commit('updateDomainNameLogin', domainName)
        },
        changeSubmitStatusLogin(ctx, value) {
            ctx.commit('updateSubmitStatusLogin', value)
        }
    },
    mutations: {
        updateValidationEmailLogin(state, vEmail) {
            state.formItemArr[0].error = vEmail.invalid
            state.formItemArr[0].required = vEmail.required
            state.formItemArr[0].warningItemArr[1].error = vEmail.required
            state.formItemArr[0].email = vEmail.email
            state.formItemArr[0].warningItemArr[0].error = vEmail.email
        },
        updateEmailLogin(state, email) {
            state.email = email
        },
        updateValidationPasswordLogin(state, vPassword) {
            state.formItemArr[1].error = vPassword.invalid
            state.formItemArr[1].required = vPassword.required
            state.formItemArr[1].warningItemArr[1].error = vPassword.required
            state.formItemArr[1].minLength = vPassword.minLength
            state.formItemArr[1].warningItemArr[0].error = vPassword.minLength
        },
        updatePasswordLogin(state, password) {
            state.password = password
        },
        updateValidationDomainNameLogin(state, vDomainName) {
            state.formItemArr[2].error = vDomainName.invalid
            state.formItemArr[2].required = vDomainName.required
            state.formItemArr[2].warningItemArr[1].error = vDomainName.required
            state.formItemArr[2].url = vDomainName.url
            state.formItemArr[2].warningItemArr[0].error = vDomainName.url
        },
        updateDomainNameLogin(state, domainName) {
            state.domainNameLogin = domainName
        },
        updateSubmitStatusLogin(state, value) {
            state.submitStatus = value
        }
    },
    getters: {
        formItemArrLogin(state) {
            return state.formItemArr
        },
        emailLogin(state) {
            return state.email
        },
        passwordLogin(state) {
            return state.password
        },
        domainNameLogin(state) {
            return state.domainNameLogin
        },
        submitStatusLogin(state) {
            return state.submitStatus
        }
    }
}