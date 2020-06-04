<template>
    <div>
        <md-dialog :md-active.sync="error.status">
            <md-dialog-title>{{error.title}}</md-dialog-title>

            <md-dialog-content>
                <p>{{error.body}}</p>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="error.status = false">Ок</md-button>
            </md-dialog-actions>
        </md-dialog>
        <form novalidate class="md-layout" @submit.prevent="validateUser">
            <md-card class="md-layout-item md-size-30 md-small-size-100">
                <md-card-header>
                    <div class="md-title">Авторизуйтесь</div>
                </md-card-header>

                <md-card-content>
                    <md-field :class="getValidationClass('email')">
                        <label for="email">Email</label>
                        <md-input type="email" name="email" id="email" autocomplete="email" v-model="form.email" :disabled="sending" />
                        <span class="md-error" v-if="!$v.form.email.required">Обязательное поле</span>
                        <span class="md-error" v-else-if="!$v.form.email.email">Некоректный email</span>
                    </md-field>

                    
                    <md-field :class="getValidationClass('password')">
                        <label for="password">Пароль</label>
                        <md-input name="password" id="passworde" type="password" v-model="form.password" :disabled="sending" />
                        <span class="md-error" v-if="!$v.form.password.required">Обязательное поле</span>
                        <span class="md-error" v-else-if="!$v.form.password.minlength">Invalid password</span>
                    </md-field>
                        
                    <md-field :class="getValidationClass('domainName')">
                        <label for="domain-name">Доменное имя</label>
                        <md-input name="domain-name" id="domain-name" v-model="form.domainName" :disabled="sending" />
                        <span class="md-error" v-if="!$v.form.domainName.required">Обязательное поле</span>
                        <span class="md-error" v-else-if="!$v.form.domainName.url">Некоректное доменное имя</span>
                    </md-field>
                </md-card-content>

                <md-progress-bar md-mode="indeterminate" v-if="sending" />

                <md-card-actions>
                    <md-button class="md-primary" to="forgotPassword" :disabled="sending">Забыли пароль?</md-button>
                    <md-button type="submit" class="md-primary" :disabled="sending">Авторизоваться</md-button>
                </md-card-actions>
            </md-card>
        </form>
    </div>
</template>

<script>
    import axios from 'axios'
    import { required, minLength, email, helpers } from 'vuelidate/lib/validators'
    import { mapActions } from 'vuex'
    const url = helpers.regex('url', /^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/)

    export default {
        data: () => ({
            form: {
                email: null,
                password: null,
                domainName: localStorage.domainName || null,
            },
            error: {
                title: null,
                body: null,
                status: false,
            },
            sending: false,
        }), 
        validations: {
            form: {
                email: {
                    required,
                    email
                },
                password: {
                    required,
                    minLength: minLength(6)
                },
                domainName: {
                    required,
                    url
                }
            }
        },
        methods: {
            ...mapActions([
                'authRequest',
                'getCurrentUserInfo'
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
                this.form.password = null
                this.form.domainName = null
            },
            saveUser () {
                this.sending = true

                this.authRequest({username: this.form.email, password: this.form.password, domainName: this.form.domainName })
                .then(() => {
                    axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.userToken}`
                    this.getCurrentUserInfo()
                    this.$router.push("/chat");
                })
                .catch(error => {
                    console.log('error: ', error)
                    if (error.response.status == 401) {
                        this.error.title = 'Ошибка'
                        this.error.body = 'Не верный email или пароль.'
                        this.error.status = true
                    }
                    else if (error.response.status == 403) {
                        this.error.title = 'Ошибка'
                        this.error.body = 'Email не подтверждён.'
                        this.error.status = true
                    }
                    else if (error.response.status == 423) {
                        this.error.title = 'Ошибка'
                        this.error.body = 'Пользователь заблокирован.'
                        this.error.status = true
                    }
                });            

                this.sending = false
                this.clearForm()
            },
            validateUser () {
                this.$v.$touch()

                if (!this.$v.$invalid) {
                    this.saveUser()
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