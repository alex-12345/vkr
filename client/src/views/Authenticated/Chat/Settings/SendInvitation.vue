<template>
    <form novalidate class="md-layout" @submit.prevent="validatePassword">
        <md-card class="md-layout-item">
            <md-card-header>
                <div class="md-title">Отправить приглашение</div>
            </md-card-header>

            <md-card-content>
                <md-field :class="getValidationClass('firstName')">
                    <label for="first-name">Имя</label>
                    <md-input name="first-name" id="first-name" autocomplete="given-name" v-model="form.firstName" :disabled="sendingCreateUser" />
                    <span class="md-error" v-if="!$v.form.firstName.required">Обязательное поле</span>
                </md-field>
                    
                <md-field :class="getValidationClass('lastName')">
                    <label for="last-name">Фамилия</label>
                    <md-input name="last-name" id="last-name" autocomplete="family-name" v-model="form.lastName" :disabled="sendingCreateUser" />
                    <span class="md-error" v-if="!$v.form.lastName.required">Обязательное поле</span>
                </md-field>

                <md-field :class="getValidationClass('email')">
                    <label for="email">Email</label>
                    <md-input type="email" name="email" id="email" autocomplete="email" v-model="form.email" :disabled="sendingCreateUser" />
                    <span class="md-error" v-if="!$v.form.email.required">Обязательное поле</span>
                    <span class="md-error" v-else-if="!$v.form.email.email">Некоректный email</span>
                </md-field>

                <md-field :class="getValidationClass('roles')">
                    <label for="roles">Роль</label>
                    <md-select name="roles" id="roles" v-model="form.roles" md-dense :disabled="sending">
                        <md-option></md-option>
                        <md-option value="ROLE_USER">Пользователь</md-option>
                        <md-option value="ROLE_ADMIN">Модератор</md-option>
                    </md-select>
                    <span class="md-error" v-if="!$v.form.roles.required">Обязательное поле</span>
                </md-field>
            </md-card-content>

            <md-progress-bar md-mode="indeterminate" v-if="sendingCreateUser" />

            <md-card-actions>
                <md-button type="submit" class="md-primary" :disabled="sendingCreateUser">Сохранить</md-button>
            </md-card-actions>
        </md-card>
    </form>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import axios from 'axios'
    import { required, email } from 'vuelidate/lib/validators'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        name: 'ChangePassword',
        mixins: [validationMixin],
        computed: mapGetters([
            "sendingCreateUser",
        ]),
        data: () => ({
            form: {
                firstName: null,
                lastName: null,
                email: null,
                roles: null,
            },
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
                roles: {
                    required,
                }
            }
        },
        methods: {
            ...mapActions([
                'createUser',
                'changeSendingCreateUser'
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
                this.form.roles = null
            },
            savePassword() {
                this.changeSendingCreateUser()

                const admin = {
                    first_name: this.form.firstName,
                    second_name: this.form.lastName,
                    email: this.form.email,
                    link: 'http://client.sapechat.ru/confirm',
                    roles: [this.form.roles]
                }

                axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.userToken}`
                this.createUser(admin)
                .then(() => {
                    this.clearForm()
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