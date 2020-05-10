export default {
    state: {
        formItemArr: [
            {id: 1, title: 'Имя', type: 'text', required: true, error: false, alpha: true,
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
            {id: 3, title: 'Ip адрес', type: 'text', required: true, error: false, ipAddress: true,
                warningItemArr: [
                    {id: 1, title: "Ip адрес должен быть формата 0.0.0.0", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true},
                ]
            },
        ],
        name: '',
        ipAddress: '',
        password: '',
        submitStatus: null
    },
    actions: {
        changeValidationNameLogin(ctx, vName) {
            ctx.commit('updateValidationNameLogin', vName)
        },
        changeNameLogin(ctx, name) {
            ctx.commit('updateNameLogin', name)
        },
        changeValidationPasswordLogin(ctx, vPassword) {
            ctx.commit('updateValidationPasswordLogin', vPassword)
        },
        changePasswordLogin(ctx, password) {
            ctx.commit('updatePasswordLogin', password)
        },
        changeValidationIpAddressLogin(ctx, vIpAddress) {
            ctx.commit('updateValidationIpAddressLogin', vIpAddress)
        },
        changeIpAddressLogin(ctx, ipAddress) {
            ctx.commit('updateIpAddressLogin', ipAddress)
        },
        changeSubmitStatusLogin(ctx, value) {
            ctx.commit('updateSubmitStatusLogin', value)
        }
    },
    mutations: {
        updateValidationNameLogin(state, vName) {
            state.formItemArr[0].error = vName.invalid
            state.formItemArr[0].required = vName.required
            //console.log('До 1 ', state.formItemArr[0].warningItemArr[1].error)
            state.formItemArr[0].warningItemArr[1].error = vName.required
            //console.log('После 1 ', state.formItemArr[0].warningItemArr[1].error)
            state.formItemArr[0].alpha = vName.alpha
            //console.log('До 2 ', state.formItemArr[0].warningItemArr[0].error)
            state.formItemArr[0].warningItemArr[0].error = vName.alpha
            //console.log('После 2 ', state.formItemArr[0].warningItemArr[0].error)
        },
        updateNameLogin(state, name) {
            state.name = name
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
        updateValidationIpAddressLogin(state, vIpAddress) {
            state.formItemArr[2].error = vIpAddress.invalid
            state.formItemArr[2].required = vIpAddress.required
            state.formItemArr[2].warningItemArr[1].error = vIpAddress.required
            state.formItemArr[2].ipAddress = vIpAddress.ipAddress
            state.formItemArr[2].warningItemArr[0].error = vIpAddress.ipAddress
        },
        updateIpAddressLogin(state, ipAddress) {
            state.ipAddress = ipAddress
        },
        updateSubmitStatusLogin(state, value) {
            state.submitStatus = value
        }
    },
    getters: {
        formItemArrLogin(state) {
            return state.formItemArr
        },
        nameLogin(state) {
            return state.name
        },
        ipAddressLogin(state) {
            return state.ipAddress
        },
        passwordLogin(state) {
            return state.password
        },
        submitStatusLogin(state) {
            return state.submitStatus
        }
    }
}