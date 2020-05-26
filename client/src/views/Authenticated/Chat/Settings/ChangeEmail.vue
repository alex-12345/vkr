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
            </md-card-content>

            <md-card-actions>
                <md-button type="submit" class="md-primary" :disabled="sending">Сохранить</md-button>
            </md-card-actions>
        </md-card>
    </form>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, email } from 'vuelidate/lib/validators'

    export default {
        name: 'ChangeEmail',
        mixins: [validationMixin],
        data: () => ({
            form: {
                email: null,
            },
            sending: false,
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
                console.log('Email: ', this.form.email)
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