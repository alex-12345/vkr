<template>
    <form novalidate class="md-layout" @submit.prevent="validatePassword">
        <md-card class="md-layout-item">
            <md-card-header>
                <div class="md-title">Изменить пароль</div>
            </md-card-header>

            <md-card-content>
                <md-field :class="getValidationClass('password')">
                    <label for="pass">Введите старый пароль</label>
                    <md-input name="pass" type="password" v-model="form.oldPassword" :disabled="sendingPassword"></md-input>
                    <span class="md-error" v-if="!$v.form.password.required">Поле обязательно</span>
                    <span class="md-error" v-else-if="!$v.form.password.minLength">Пароль должен содержать минимум 6 символов</span>
                </md-field>
                    
                <md-field :class="getValidationClass('password')">
                    <label for="pass">Введите новый пароль</label>
                    <md-input name="pass" type="password" v-model="form.password" :disabled="sendingPassword"></md-input>
                    <span class="md-error" v-if="!$v.form.password.required">Поле обязательно</span>
                    <span class="md-error" v-else-if="!$v.form.password.minLength">Пароль должен содержать минимум 6 символов</span>
                </md-field>
                    
                <md-field :class="getValidationClass('repeatPassword')">
                    <label for="repeatPass">Повторите пароль</label>
                    <md-input name="repeatPass" type="password" v-model="form.repeatPassword" :disabled="sendingPassword"></md-input>
                    <span class="md-error" v-if="!$v.form.repeatPassword.required">Поле обязательно</span>
                    <span class="md-error" v-else-if="!$v.form.repeatPassword.sameAsPassword">Пароль должен быть идентичен</span>
                </md-field>

                <span class="md-error" v-if="error.show">{{error.body}}</span>
            </md-card-content>

            <md-progress-bar md-mode="indeterminate" v-if="sendingPassword" />

            <md-card-actions>
                <md-button type="submit" class="md-primary" :disabled="sendingPassword">Сохранить</md-button>
            </md-card-actions>
        </md-card>
    </form>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import axios from 'axios'
    import { required, sameAs, minLength } from 'vuelidate/lib/validators'
    import {mapGetters, mapActions} from 'vuex'

    export default {
        name: 'ChangePassword',
        mixins: [validationMixin],
        computed: mapGetters([
            "sendingPassword",
            "passwordChanged",
            "passwordWorked"
        ]),
        data: () => ({
            form: {
                oldPassword: null,
                password: null,
                repeatPassword: null,
            },
            error: {
                show: false,
                body: '',
            },
        }),
        validations: {
            form: {
                oldPassword: {
                    required,
                    minLength: minLength(6)
                },
                password: {
                    required,
                    minLength: minLength(6)
                },
                repeatPassword: {
                    required,
                    sameAsPassword: sameAs('password')
                },
            }
        },
        methods: {
            ...mapActions([
                'changePassword',
                'changeSendingPassword',
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
                this.form.password = null
                this.form.repeatPassword = null
                this.form.oldPassword = null
            },
            savePassword() {
                this.changeSendingPassword()
                const object = {
                    "old_password": this.form.oldPassword,
                    "new_password": this.form.password
                }
                axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.userToken}`
                this.changePassword(object)
                .then(() => {
                    this.error.show = false
                    this.clearForm()
                })
                .catch((error) => {
                    if (error.response.status == 400) {
                        this.error.show = true
                        this.error.body = 'Неправильный старый пароль.'
                    }
                    else if (error.response.status == 423) {
                        this.changeSubmitStatusLogin('USER_IS_BLOCKED')
                        this.authLogout()
                        .then(() => {
                            this.$router.push('/authorization')
                        })
                    }
                })
            },
            validatePassword () {
                this.$v.$touch()

                if (!this.$v.$invalid) {
                    this.savePassword()
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