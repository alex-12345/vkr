<template>
    <div class="inputTextArea">
        <form novalidate class="md-layout" @submit.prevent="validateUser">
            <md-field :class="getValidationClass('firstName')">
                <label for="first-name">Напишите сообщение...</label>
                <md-textarea 
                    name="first-name" 
                    id="first-name" 
                    autocomplete="given-name" 
                    v-model="form.firstName" 
                    :disabled="sending" 
                    v-on:keyup.enter.exact="validateUser"
                >
                </md-textarea>
            </md-field>
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
                console.log(this.form.firstName)

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
            },
        }
    }
</script>

<style scoped>
    .inputTextArea{
        flex: 0 0 auto;
    }

    .md-card{
        margin: 0px auto;
    }

    .md-layout-item{
        padding: 0px;
    }

    .md-field {
        margin: 16px;
    }
</style>
