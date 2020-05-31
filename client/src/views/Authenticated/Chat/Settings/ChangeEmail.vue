<template>
    <form novalidate class="md-layout" @submit.prevent="validateEmail">
        <md-card class="md-layout-item">
            <md-card-header>
                <div class="md-title">Изменить email</div>
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
                <md-button type="submit" class="md-primary" :disabled="sending">Сохранить</md-button>
            </md-card-actions>
        </md-card>
    </form>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, email } from 'vuelidate/lib/validators'
    import { mapActions } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'ChangeEmail',
        mixins: [validationMixin],
        data: () => ({
            form: {
                email: null,
            },
            sending: false,
            error: {
                show: false,
                body: '',
            },
        }),
        validations: {
            form: {
                email: {
                    required,
                    email
                },
            }
        },
        methods: {
            ...mapActions([
                'changeEmail',
                'changeSubmitStatusLogin',
                'authLogout'
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
                    new_email: this.form.email,
                    link: "http://client.sapechat.ru/confirmEmail",
                }

                axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.userToken}`
                this.changeEmail(email)
                .then(() => {
                    this.error.show = false
                    this.clearForm()
                })
                .catch((error) => {
                    if (error.response.data.errors.title == 'This user already have this email!') {
                        this.error.show = true
                        this.error.body = 'Этот пользователь уже имеет этот email.'
                    }
                    else if (error.response.data.errors.title == 'User with this email already exist and confirmed') {
                        this.error.show = true
                        this.error.body = 'Пользователь с таким адресом электронной почты уже существует.'
                    }
                    else if (error.response.status == 423) {
                        this.changeSubmitStatusLogin('USER_IS_BLOCKED')
                        this.authLogout()
                        .then(() => {
                            this.$router.push('/authorization')
                        })
                    }
                });

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
    .md-layout {
        margin-top: 40px;
    }
</style>