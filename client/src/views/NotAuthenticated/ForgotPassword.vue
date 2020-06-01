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
    import { required, email } from 'vuelidate/lib/validators'
    import { mapActions } from 'vuex'

    export default {
        name: 'ForgotPassword',
        mixins: [validationMixin],
        data: () => ({
            form: {
                email: null,
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
            },
            saveEmail() {
                this.sending = true
                
                const email = {
                    email: this.form.email,
                    link: "http://client.sapechat.ru/confirmPassword",
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