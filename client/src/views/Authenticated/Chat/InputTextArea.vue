<template>
    <div class="inputTextArea">
        <form novalidate class="md-layout" @submit.prevent="validateUser">
            <md-card class="md-layout-item md-size-50 md-small-size-100">

                <md-card-content>
                    <div class="md-layout md-gutter">
                        <div class="md-layout-item md-small-size-100">
                            <md-field :class="getValidationClass('firstName')">
                                <label for="first-name">Напишите сообщение...</label>
                                <md-textarea name="first-name" id="first-name" autocomplete="given-name" v-model="form.firstName" :disabled="sending"></md-textarea>
                                <span class="md-error" v-if="!$v.form.firstName.required">Сообщение обязательно</span>
                            </md-field>
                        </div>
                    </div>
                </md-card-content>

                <md-card-actions>
                    <md-button type="submit" class="md-primary" :disabled="sending">Отправить</md-button>
                </md-card-actions>
            </md-card>

            <md-snackbar :md-active.sync="userSaved">Сообщение {{ lastUser }} отправлено!</md-snackbar>
        </form>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import {
        required,
    } from 'vuelidate/lib/validators'

    export default {
        name: 'inputTextArea',
        mixins: [validationMixin],
        data: () => ({
            form: {
                firstName: null,
            },
            userSaved: false,
            sending: false,
            lastUser: null
        }),
        validations: {
            form: {
                firstName: {
                    required,
                }
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
                this.form.firstName = null
            },
            saveUser () {
                this.sending = true

                // Instead of this timeout, here you can call your API
                window.setTimeout(() => {
                    this.lastUser = `${this.form.firstName}`
                    this.userSaved = true
                    this.sending = false
                    this.clearForm()
                }, 1500)
            },
            validateUser () {
                this.$v.$touch()

                if (!this.$v.$invalid) {
                    this.saveUser()
                }
            }
        }
    }
</script>

<style scoped>

</style>
