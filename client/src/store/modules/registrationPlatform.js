import axios from 'axios'

export default {
    state: {
        formItemArr: [
            {id: 1, title: 'Ключ доступа', type: 'text', required: true, error: false, minLength: true,
                warningItemArr: [
                    {id: 1, title: "Ключ доступа должен иметь как минимум 4 сивола", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true}
                ]
            },
            {id: 2, title: 'Доменное имя', type: 'text', required: true, error: false, url: true,
                warningItemArr: [
                    {id: 1, title: "Некорректное доменное имя", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true}
                ]
            },
        ],
        accessKey: '',
        domainName: '',
        submitStatus: null
    },
    actions: {
        changeValidationAccessKeyRegisterPage(ctx, vAccessKey) {
            ctx.commit('updateValidationAccessKeyRegisterPage', vAccessKey)
        },
        changeAccessKeyRegisterPage(ctx, accessKey) {
            ctx.commit('updateAccessKeyRegisterPage', accessKey)
        },
        changeValidationDomainNameRegisterPage(ctx, vDomainName) {
            ctx.commit('updateValidationDomainNameRegisterPage', vDomainName)
        },
        changeDomainNameRegisterPage(ctx, domainName) {
            ctx.commit('updateDomainNameRegisterPage', domainName)
        },
        changeSubmitStatusRegisterPage(ctx, value) {
            ctx.commit('updateSubmitStatusRegisterPage', value)
        },
        workspaceInfo(ctx, object) {
            return new Promise(function(resolve, reject) {
                axios.get('http://' + object.domainName + '/api/workspace/info?workspace_key=' + object.key)
                .then(response => {
                    ctx.commit('updateSubmitStatusRegisterPage', 'OK')
                    localStorage.workspaceKey = object.key
                    localStorage.domainName = object.domainName
                    resolve (response.data)
                })
                .catch(error => {

                    if (error.response.status == 403) {
                        ctx.commit('updateSubmitStatusRegisterPage', 'WORKSPACE_NOT_FOUND')
                    }
                    else if (error.response.status == 404) {
                        ctx.commit('updateSubmitStatusRegisterPage', 'WORKSPACE_NOT_FOUND')
                    }
                    reject (error.data)
                });
            });
        },
    },
    mutations: {
        updateValidationAccessKeyRegisterPage(state, vAccessKey) {
            state.formItemArr[0].error = vAccessKey.invalid
            state.formItemArr[0].required = vAccessKey.required
            state.formItemArr[0].warningItemArr[1].error = vAccessKey.required
            state.formItemArr[0].minLength = vAccessKey.minLength
            state.formItemArr[0].warningItemArr[0].error = vAccessKey.minLength
        },
        updateAccessKeyRegisterPage(state, accessKey) {
            state.accessKey = accessKey
        },
        updateValidationDomainNameRegisterPage(state, vDomainName) {
            state.formItemArr[1].error = vDomainName.invalid
            state.formItemArr[1].required = vDomainName.required
            state.formItemArr[1].warningItemArr[1].error = vDomainName.required
            state.formItemArr[1].url = vDomainName.url
            state.formItemArr[1].warningItemArr[0].error = vDomainName.url
        },
        updateDomainNameRegisterPage(state, domainName) {
            state.domainName = domainName
        },
        updateSubmitStatusRegisterPage(state, value) {
            state.submitStatus = value
        }
    },
    getters: {
        formItemArrRegisterPage(state) {
            return state.formItemArr
        },
        accessKeyRegisterPage(state) {
            return state.accessKey
        },
        domainNameRegisterPage(state) {
            return state.domainName
        },
        submitStatusRegisterPage(state) {
            return state.submitStatus
        }
    }
}