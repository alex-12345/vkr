<template>
    <div>
        <md-dialog :md-active.sync="showDialog">
            <md-dialog-title>{{title}}</md-dialog-title>

            <md-dialog-content>
                <p>{{body}}</p>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button v-if="!logIn" class="md-primary" @click="showDialog = false">Ок</md-button>
                <md-button v-if="logIn" to="/authorization" class="md-primary" @click="showDialog = false">Ок</md-button>
            </md-dialog-actions>
        </md-dialog>
        <form novalidate class="md-layout" @submit.prevent="validateUser">
            <md-card class="md-layout-item md-size-30 md-small-size-100">
                <md-card-header>
                    <div class="md-title">Зарегестрируйтесь</div>
                </md-card-header>

                <md-card-content>
                    <md-field :class="getValidationClass('firstName')">
                        <label for="first-name">Имя</label>
                        <md-input name="first-name" id="first-name" autocomplete="given-name" v-model="form.firstName" :disabled="sending" />
                        <span class="md-error" v-if="!$v.form.firstName.required">Обязательное поле</span>
                    </md-field>
                    
                    <md-field :class="getValidationClass('lastName')">
                        <label for="last-name">Фамилия</label>
                        <md-input name="last-name" id="last-name" autocomplete="family-name" v-model="form.lastName" :disabled="sending" />
                        <span class="md-error" v-if="!$v.form.lastName.required">Обязательное поле</span>
                    </md-field>

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
                        
                    <md-field :class="getValidationClass('repeatPassword')">
                        <label for="repeat-password">Повторите пароль</label>
                        <md-input name="repeat-password" id="repeat-password" type="password" v-model="form.repeatPassword" :disabled="sending" />
                        <span class="md-error" v-if="!$v.form.repeatPassword.required">Обязательное поле</span>
                        <span class="md-error" v-else-if="!$v.form.repeatPassword.sameAsPassword">Пароли должны быть идентичны</span>
                    </md-field>
                </md-card-content>

                <md-progress-bar md-mode="indeterminate" v-if="expectation" />

                <md-card-actions>
                    <md-button type="submit" class="md-primary" :disabled="sending">Зарегестрироваться</md-button>
                </md-card-actions>
            </md-card>
        </form>
  </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import { required, minLength, email, sameAs } from 'vuelidate/lib/validators'

    export default {
        data: () => ({
            form: {
                firstName: null,
                lastName: null,
                email: null,
                password: null,
                repeatPassword: null,
            },
            sending: false,
            title: 'Супер админ не подтверждён',
            body: 'Проверьте почту и подтвердите его или создайте заново.',
            showDialog: false,
            isActive: null,
            expectation: false,
            logIn: false,
        }),
        validations: {
            form: {
                firstName: {
                    required,
                },
                lastName: {
                    required,
                },
                email: {
                    required,
                    email
                },
                password: {
                    required,
                    minLength: minLength(6)
                },
                repeatPassword: {
                    required,
                    sameAsPassword: sameAs('password')
                }
            }
        },
        methods: {
            ...mapActions([ 
                'superAdminInfo',
                'putSuperAdmin',
                'createSuperAdmin'
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
                this.form.firstName = null
                this.form.lastName = null
                this.form.email = null
                this.form.password = null
                this.form.repeatPassword = null
            },
            saveUser () {
                this.sending = true
                this.expectation = true

                const superAdmin = {
                    first_name: this.form.firstName,
                    second_name: this.form.lastName,
                    email: this.form.email,
                    link: 'http://client.sapechat.ru/confirm',
                    password: this.form.password
                }
                console.log('superAdmin: ', superAdmin)

                if (this.isActive == false) {
                    this.putSuperAdmin({key: localStorage.workspaceKey, admin: superAdmin})
                    .then(() => {
                        this.title = 'Письмо отправлено'
                        this.body = 'Проверьте почту.'
                        this.showDialog = true
                    })
                    /*.catch(error => {
                        if (error.status == 403) {}
                    });*/
                }
                else if (this.isActive == undefined) {
                    this.createSuperAdmin({key: localStorage.workspaceKey, admin: superAdmin})
                    .then(() => {
                        this.title = 'Письмо отправлено'
                        this.body = 'Проверьте почту.'
                        this.showDialog = true
                    })
                }             

                this.expectation = false
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
        created() {
            this.superAdminInfo(localStorage.workspaceKey)
            .then(response => {
                if (response.data.is_active == true) {
                    this.isActive = true
                    this.title = 'Ошибка'
                    this.body = 'Супер пользователь уже существует. Можете авторизоваться.'
                    this.logIn = true
                    this.showDialog = true
                }
                else if (response.data.is_active == false) {
                    console.log('Супер админ не подтверждён')
                    this.showDialog = true
                    this.isActive = false
                    this.form.firstName = response.data.first_name
                    this.form.lastName = response.data.second_name
                    this.form.email = response.data.email
                }
            })
            .catch(error => {
                console.log('error: ', error)
                if (error.status == 404) {
                    console.log('Суперпользователя ещё нет')
                }
                else if (error.status == 403) {
                    this.isActive = undefined
                    this.title = 'Площадка не зарегистрирована'
                    this.body = 'Перед тем как создать суперпользователя, необходимо зарегистрировать площадку.'
                    this.sending = true
                    this.showDialog = true
                }
            });
            
        }
    }
</script>

<style scoped>
    .md-card{
        margin: 80px auto;
    }
</style>