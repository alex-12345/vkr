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
        changeValidationName(ctx, vName) {
            ctx.commit('updateValidationName', vName)
        },
        changeName(ctx, name) {
            ctx.commit('updateName', name)
        },
        changeValidationPassword(ctx, vPassword) {
            ctx.commit('updateValidationPassword', vPassword)
        },
        changePassword(ctx, password) {
            ctx.commit('updatePassword', password)
        },
        changeValidationIpAddress(ctx, vIpAddress) {
            ctx.commit('updateValidationIpAddress', vIpAddress)
        },
        changeIpAddress(ctx, ipAddress) {
            ctx.commit('updateIpAddress', ipAddress)
        },
        changeSubmitStatus(ctx, value) {
            ctx.commit('updateSubmitStatus', value)
        }
    },
    mutations: {
        updateValidationName(state, vName) {
            state.formItemArr[0].error = vName.invalid
            state.formItemArr[0].required = vName.required
            state.formItemArr[0].warningItemArr[1].error = vName.required
            state.formItemArr[0].alpha = vName.alpha
            state.formItemArr[0].warningItemArr[0].error = vName.alpha
        },
        updateName(state, name) {
            state.name = name
        },
        updateValidationPassword(state, vPassword) {
            state.formItemArr[1].error = vPassword.invalid
            state.formItemArr[1].required = vPassword.required
            state.formItemArr[1].warningItemArr[1].error = vPassword.required
            state.formItemArr[1].minLength = vPassword.minLength
            state.formItemArr[1].warningItemArr[0].error = vPassword.minLength
        },
        updatePassword(state, password) {
            state.password = password
        },
        updateValidationIpAddress(state, vIpAddress) {
            state.formItemArr[2].error = vIpAddress.invalid
            state.formItemArr[2].required = vIpAddress.required
            state.formItemArr[2].warningItemArr[1].error = vIpAddress.required
            state.formItemArr[2].ipAddress = vIpAddress.ipAddress
            state.formItemArr[2].warningItemArr[0].error = vIpAddress.ipAddress
        },
        updateIpAddress(state, ipAddress) {
            state.ipAddress = ipAddress
        },
        updateSubmitStatus(state, value) {
            state.submitStatus = value
        }
    },
    getters: {
        formItemArr(state) {
            return state.formItemArr
        },
        name(state) {
            return state.name
        },
        ipAddress(state) {
            return state.ipAddress
        },
        password(state) {
            return state.password
        },
        submitStatus(state) {
            return state.submitStatus
        }
    }
}