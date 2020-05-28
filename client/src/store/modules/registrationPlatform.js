import axios from 'axios'

export default {
    state: {
        formItemArr: [
            {id: 1, title: 'Ключ доступа', type: 'text', required: true, error: false,
                warningItemArr: [
                    {id: 1, title: "Ключ доступа должен иметь как минимум 4 сивола", error: true},
                    {id: 2, title: "Поле обязательно для заполнения", error: true}
                ]
            },
        ],
        accessKey: '',
        submitStatus: null
    },
    actions: {
        changeValidationAccessKeyRegisterPage(ctx, vAccessKey) {
            ctx.commit('updateValidationAccessKeyRegisterPage', vAccessKey)
        },
        changeAccessKeyRegisterPage(ctx, accessKey) {
            ctx.commit('updateAccessKeyRegisterPage', accessKey)
        },
        changeSubmitStatusRegisterPage(ctx, value) {
            ctx.commit('updateSubmitStatusRegisterPage', value)
        },
        workspaceInfo(ctx, key) {
            return new Promise(function(resolve, reject) {
                axios.get('http://sapechat.ru/api/workspace/info?workspace_key=' + key)
                .then(response => {
                    ctx.commit('updateSubmitStatusRegisterPage', 'OK')
                    localStorage.workspaceKey = key
                    resolve (response.data)
                })
                .catch(error => {
                    console.log('error: ', error)

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
        submitStatusRegisterPage(state) {
            return state.submitStatus
        }
    }
}