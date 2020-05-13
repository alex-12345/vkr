export default {
    state: {
        formItemArr: [
            {id: 1, title: 'Название', type: 'text', required: true, error: false,
                warningItemArr: [
                    {id: 1, title: "Поле обязательно для заполнения", error: true},
                ]
            },
            {id: 2, title: 'Ключ доступа', type: 'text', required: true, error: false,
                warningItemArr: [
                    {id: 1, title: "Ключ доступа должен иметь как минимум 4 сивола", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true}
                ]
            },
            {id: 3, title: 'Ip адрес', type: 'text', required: true, error: false, ipAddress: true,
                warningItemArr: [
                    {id: 1, title: "Ip адрес должен быть формата 0.0.0.0", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true},
                ]
            },
        ],
        title: '',
        ipAddress: '',
        accessKey: '',
        submitStatus: null
    },
    actions: {
        changeValidationTitleRegisterPage(ctx, vTitle) {
            ctx.commit('updateValidationTitleRegisterPage', vTitle)
        },
        changeTitleRegisterPage(ctx, title) {
            ctx.commit('updateTitleRegisterPage', title)
        },
        changeValidationAccessKeyRegisterPage(ctx, vAccessKey) {
            ctx.commit('updateValidationAccessKeyRegisterPage', vAccessKey)
        },
        changeAccessKeyRegisterPage(ctx, accessKey) {
            ctx.commit('updateAccessKeyRegisterPage', accessKey)
        },
        changeValidationIpAddressRegisterPage(ctx, vIpAddress) {
            ctx.commit('updateValidationIpAddressRegisterPage', vIpAddress)
        },
        changeIpAddressRegisterPage(ctx, ipAddress) {
            ctx.commit('updateIpAddressRegisterPage', ipAddress)
        },
        changeSubmitStatusRegisterPage(ctx, value) {
            ctx.commit('updateSubmitStatusRegisterPage', value)
        }
    },
    mutations: {
        updateValidationTitleRegisterPage(state, vTitle) {
            state.formItemArr[0].error = vTitle.invalid
            state.formItemArr[0].required = vTitle.required
            state.formItemArr[0].warningItemArr[0].error = vTitle.required
        },
        updateTitleRegisterPage(state, title) {
            state.title = title
        },
        updateValidationAccessKeyRegisterPage(state, vAccessKey) {
            state.formItemArr[1].error = vAccessKey.invalid
            state.formItemArr[1].required = vAccessKey.required
            state.formItemArr[1].warningItemArr[1].error = vAccessKey.required
            state.formItemArr[1].minLength = vAccessKey.minLength
            state.formItemArr[1].warningItemArr[0].error = vAccessKey.minLength
        },
        updateAccessKeyRegisterPage(state, accessKey) {
            state.accessKey = accessKey
        },
        updateValidationIpAddressRegisterPage(state, vIpAddress) {
            state.formItemArr[2].error = vIpAddress.invalid
            state.formItemArr[2].required = vIpAddress.required
            state.formItemArr[2].warningItemArr[1].error = vIpAddress.required
            state.formItemArr[2].ipAddress = vIpAddress.ipAddress
            state.formItemArr[2].warningItemArr[0].error = vIpAddress.ipAddress
        },
        updateIpAddressRegisterPage(state, ipAddress) {
            state.ipAddress = ipAddress
        },
        updateSubmitStatusRegisterPage(state, value) {
            state.submitStatus = value
        }
    },
    getters: {
        formItemArrRegisterPage(state) {
            return state.formItemArr
        },
        titleRegisterPage(state) {
            return state.title
        },
        ipAddressRegisterPage(state) {
            return state.ipAddress
        },
        accessKeyRegisterPage(state) {
            return state.accessKey
        },
        submitStatusRegisterPage(state) {
            return state.submitStatus
        }
    }
}