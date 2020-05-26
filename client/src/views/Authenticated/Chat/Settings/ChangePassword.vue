<template>
    <form novalidate class="md-layout" @submit.prevent="validatePassword">
        <md-card class="md-layout-item">
            <md-card-header>
                <div class="md-title">Изменить пароль</div>
            </md-card-header>

            <md-card-content>
                <div class="md-layout md-gutter">
                    <div class="md-layout-item md-small-size-100">
                        <md-field :class="getValidationClass('password')">
                            <label for="pass">Введите пароль</label>
                            <md-input name="pass" type="password" v-model="form.password" :disabled="sending"></md-input>
                            <span class="md-error" v-if="!$v.form.password.required">Поле обязательно</span>
                            <span class="md-error" v-else-if="!$v.form.password.minLength">Пароль должен содержать минимум 6 символов</span>
                        </md-field>
                    </div>
                    <div class="md-layout-item md-small-size-100">
                        <md-field :class="getValidationClass('repeatPassword')">
                            <label for="repeatPass">Повторите пароль</label>
                            <md-input name="repeatPass" type="password" v-model="form.repeatPassword" :disabled="sending"></md-input>
                            <span class="md-error" v-if="!$v.form.repeatPassword.required">Поле обязательно</span>
                            <span class="md-error" v-else-if="!$v.form.repeatPassword.sameAsPassword">Пароль должен быть идентичен</span>
                        </md-field>
                    </div>
                </div>
            </md-card-content>

            <md-card-actions>
                <md-button type="submit" class="md-primary" :disabled="sending">Сохранить</md-button>
            </md-card-actions>
        </md-card>
    </form>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, sameAs, minLength } from 'vuelidate/lib/validators'

    export default {
        name: 'ChangePassword',
        mixins: [validationMixin],
        data: () => ({
            form: {
                password: null,
                repeatPassword: null,
            },
            sending: false,
        }),
        validations: {
            form: {
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
            },
            savePassword() {
                this.sending = true
                console.log('Password: ', this.form.password)
                this.sending = false
                this.clearForm()
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