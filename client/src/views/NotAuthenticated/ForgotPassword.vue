<template>
    <div>
        <form novalidate class="md-layout" @submit.prevent="validateEmail">
            <md-card class="md-layout-item md-size-30 md-small-size-100">
                <md-card-header>
                    <div class="md-title">Поменять пароль</div>
                </md-card-header>

                <md-card-content>
                    <div class="md-layout md-gutter">
                        <div class="md-layout-item md-small-size-100">
                            <md-field :class="getValidationClass('email')">
                                <label for="email">Введите email</label>
                                <md-input name="email" type="email" v-model="form.email" :disabled="sending"></md-input>
                                <span class="md-error" v-if="!$v.form.email.required">Поле обязательно</span>
                                <span class="md-error" v-else-if="!$v.form.email.email">Email некорректен</span>
                            </md-field>
                        </div>
                        <div class="md-layout-item md-small-size-100">
                            <md-field :class="getValidationClass('domainName')">
                                <label for="domain-name">Введите домен</label>
                                <md-input name="domain-name" v-model="form.domainName" :disabled="sending"></md-input>
                                <span class="md-error" v-if="!$v.form.domainName.required">Поле обязательно</span>
                                <span class="md-error" v-else-if="!$v.form.domainName.url">Некорректное доменное имя</span>
                            </md-field>
                        </div>
                    </div>
                    <span class="md-error" v-if="error.show">{{error.body}}</span>
                </md-card-content>

                <md-progress-bar md-mode="indeterminate" v-if="sending" />

                <md-card-actions>
                    <md-button type="submit" class="md-primary" :disabled="sending">Отправить</md-button>
                </md-card-actions>
            </md-card>
        </form>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, email, helpers } from 'vuelidate/lib/validators'
    import { mapActions } from 'vuex'
    const url = helpers.regex('url', /^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/)

    export default {
        name: 'ForgotPassword',
        mixins: [validationMixin],
        data: () => ({
            form: {
                email: null,
                domainName: localStorage.domainName || null,
            },
            error: {
                show: false,
                body: '',
            },
            sending: false,
        }),
        validations: {
            form: {
                email: {
                    required,
                    email
                },
                domainName: {
                    required,
                    url
                }
            },
        },
        methods: {
            ...mapActions([
                'createRequestPasswordChange',
            ]),
            getValidationClass (fieldName) {
                const field = this.$v.form[fieldName]

                if (field) {
                    return {
                        'md-invalid': field.$invalid && field.$dirty
                    }
                }
            },
            clearForm () {
                this.$v.$reset()
                this.form.email = null
                this.form.domainName = null
            },
            saveEmail() {
                this.sending = true
                
                const email = {
                    email: this.form.email,
                    link: 'http://client.' + this.form.domainName + '/confirmPassword',
                    domainName: this.form.domainName
                }

                this.createRequestPasswordChange(email)
                .then(() => {
                    this.clearForm()
                    this.error.show = true
                    this.error.body = 'Письмо отправлено. Проверьте почту.'
                })

                this.sending = false
                this.clearForm()
            },
            validateEmail () {
                this.$v.$touch()

                if (!this.$v.$invalid) {
                    this.saveEmail()
                }
            },
        },
    }
</script>

<style scoped>
    .md-card{
        margin: 80px auto;
    }
</style>