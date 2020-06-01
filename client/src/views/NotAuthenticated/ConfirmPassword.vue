<template>
    <div>
        <md-dialog :md-active.sync="error.show">
            <md-dialog-title>Ошибка</md-dialog-title>

            <md-dialog-content>
                <p>{{error.body}}</p>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="error = false">Ок</md-button>
            </md-dialog-actions>
        </md-dialog>

        <form v-if="expectation" novalidate class="md-layout" @submit.prevent="validatePassword">
            <md-card class="md-layout-item md-size-30 md-small-size-100">
                <md-card-header>
                    <div class="md-title">Обновить пароль</div>
                </md-card-header>

                <md-card-content>                        
                    <md-field :class="getValidationClass('password')">
                        <label for="pass">Введите новый пароль</label>
                        <md-input name="pass" type="password" v-model="form.password" :disabled="sending"></md-input>
                        <span class="md-error" v-if="!$v.form.password.required">Поле обязательно</span>
                        <span class="md-error" v-else-if="!$v.form.password.minLength">Пароль должен содержать минимум 6 символов</span>
                    </md-field>
                        
                    <md-field :class="getValidationClass('repeatPassword')">
                        <label for="repeatPass">Повторите пароль</label>
                        <md-input name="repeatPass" type="password" v-model="form.repeatPassword" :disabled="sending"></md-input>
                        <span class="md-error" v-if="!$v.form.repeatPassword.required">Поле обязательно</span>
                        <span class="md-error" v-else-if="!$v.form.repeatPassword.sameAsPassword">Пароль должен быть идентичен</span>
                    </md-field>

                    <span class="md-error" v-if="error.show">{{error.body}}</span>
                </md-card-content>

                <md-progress-bar md-mode="indeterminate" v-if="sending" />

                <md-card-actions>
                    <md-button type="submit" class="md-primary" :disabled="sending">Сохранить</md-button>
                </md-card-actions>
            </md-card>
        </form>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, sameAs, minLength } from 'vuelidate/lib/validators'
    import { mapActions } from 'vuex'

    export default {
        name: 'ConfirmPassword',
        mixins: [validationMixin],
        data: () => ({
            form: {
                password: null,
                repeatPassword: null,
            },
            error: {
                show: false,
                body: '',
            },
            domainName: null,
            sending: false,
            expectation: false,
            params: '',
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
            ...mapActions([ 
                'checkRecovey',
                'changeSubmitStatusLogin',
                'confirmPasswordChange',
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
                this.form.password = null
                this.form.repeatPassword = null
            },
            savePassword () {
                this.sending = true

                this.confirmPasswordChange({id: this.params.id, hash: this.params.hash, password: this.form.password}) 
                .then(() => {
                    localStorage.removeItem('idRequestPasswordChange')
                    this.getCurrentUserInfo()
                    this.$router.push("/chat");
                })

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
        created() {
            this.params = window
                .location
                .search
                .replace('?','')
                .split('&')
                .reduce(
                    function(p,e){
                        var a = e.split('=');
                        p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                        return p;
                    },
                    {}
                );
            this.domainName = window.location.hostname
            this.domainName = this.domainName.substr(7, this.domainName.length)
            localStorage.domainName = this.domainName
            this.checkRecovey(this.params)
                .then(() => {
                    this.expectation = true
                })
                .catch(error => {
                    if (error.response.data.errors.title == 'Bad hash!') {
                        this.error.show = true
                        this.error.body = 'Ссылка не активна.'
                    }
                    else if (error.response.status == 423) {
                        this.changeSubmitStatusLogin('USER_IS_BLOCKED')
                        this.$router.push("/authorization");
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