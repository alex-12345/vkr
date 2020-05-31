<template>
    <div>
        <md-dialog :md-active.sync="error">
            <md-dialog-title>Ошибка</md-dialog-title>

            <md-dialog-content>
                <p>Ссылка недействительна. Попробуйте зарегестрироваться заново.</p>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="error = false">Ок</md-button>
            </md-dialog-actions>
        </md-dialog>
        <form v-if="expectation" novalidate class="md-layout" @submit.prevent="validateUser">
            <md-card class="md-layout-item md-size-30 md-small-size-100">
                <md-card-header>
                    <div class="md-title">Зарегестрируйтесь</div>
                </md-card-header>

                <md-card-content>                    
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

                <md-progress-bar md-mode="indeterminate" v-if="sending" />

                <md-card-actions>
                    <md-button type="submit" class="md-primary" :disabled="sending">Зарегестрироваться</md-button>
                </md-card-actions>
            </md-card>
        </form>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import { required, minLength, sameAs } from 'vuelidate/lib/validators'

    export default {
        data: () => ({
            form: {
                password: null,
                repeatPassword: null,
            },
            sending: false,
            expectation: false,
            params: '',
            error: false
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
                }
            }
        },
        methods: {
            ...mapActions([ 
                'getInvitesStatus',
                'confirmInviteAdmin',
                'confirmInviteUser',
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
            saveUser () {
                this.sending = true

                this.confirmInviteUser({id: this.params.id, hash: this.params.hash, password: this.form.password})
                .then(() => {
                    this.getCurrentUserInfo()
                    this.$router.push("/chat");
                })

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
            this.getInvitesStatus(this.params)
            .then(response => {
                console.log('response: ', response)
                if (response.data.isActive == false && this.params.status == 1) {
                    this.confirmInviteAdmin(this.params)
                    .then(() => {
                        this.$router.push("/chat");
                    })
                }
                else if (response.data.isActive == false && this.params.status != 1) {
                    this.expectation = true
                }
                else if (response.data.isActive == true) {
                    this.$router.push("/authorization");
                }
            })
            .catch(() => {
                this.error = true
            });
        }
    }
</script>

<style scoped>
    .md-card{
        margin: 80px auto;
    }
</style>