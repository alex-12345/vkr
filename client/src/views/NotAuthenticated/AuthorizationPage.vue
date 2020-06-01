<template>
    <div class="authorizationPage">
        <div class="authorizationForm">
            <h1>Sapechat</h1>
            <h2>Авторизуйтесь</h2>
            <form class="a-form" @submit.prevent="onSubmit">
                <FormItem 
                    v-for="formItem of formItemArrLogin" :key="formItem.id"
                    v-bind:formItem="formItem"
                    v-on:input="processValue"
                />
                <ButtonItem
                    v-bind:submitStatus="submitStatusLogin"
                />
            </form>
            <button class="forgotPassword">
                <router-link 
                    to="forgotPassword"
                >
                    Забыли пароль?
                </router-link>
            </button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import FormItem from '@/components/inputForm/FormItem'
    import ButtonItem from '@/components/inputForm/Button'
    import { required, minLength, email } from 'vuelidate/lib/validators'
    import {mapGetters, mapActions} from 'vuex'

    export default {
        computed: mapGetters([
            "formItemArrLogin", 
            "emailLogin", 
            "passwordLogin", 
            "submitStatusLogin",
            "isAuthenticated",
            "getUser"
        ]),
        validations: {
            emailLogin: {
                required,
                email
            },
            passwordLogin: {
                required,
                minLength: minLength(6)
            }
        },
        components: {
            FormItem,
            ButtonItem
        },
        methods: {
            ...mapActions([
                'changeValidationEmailLogin', 
                'changeEmailLogin', 
                'changeValidationPasswordLogin', 
                'changePasswordLogin', 
                'changeValidationIpAddressLogin',  
                'changeSubmitStatusLogin',
                'authRequest',
                'addUser',
                'getCurrentUserInfo'
            ]),
            processValue: function (answer) {
                if (answer.title === 'Имя')
                {
                    this.changeEmailLogin(answer.value)
                    this.changeValidationEmailLogin({invalid: this.$v.emailLogin.$invalid, required: this.$v.emailLogin.required, email: this.$v.emailLogin.email})
                }
                else if (answer.title === 'Пароль') {
                    this.changePasswordLogin(answer.value)
                    this.changeValidationPasswordLogin({invalid: this.$v.passwordLogin.$invalid, required: this.$v.passwordLogin.required, minLength: this.$v.passwordLogin.minLength})
                }
            },
            onSubmit: function () {
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.changeSubmitStatusLogin('ERROR')
                    if (this.$v.emailLogin.$invalid) {
                        this.changeValidationEmailLogin({invalid: this.$v.emailLogin.$invalid, required: this.$v.emailLogin.required, email: this.$v.emailLogin.email})
                    }
                    if (this.$v.passwordLogin.$invalid) {
                        this.changeValidationPasswordLogin({invalid: this.$v.passwordLogin.$invalid, required: this.$v.passwordLogin.required, minLength: this.$v.passwordLogin.minLength})
                    }
                } else {
                    // do your submit logic here
                    this.changeSubmitStatusLogin('PENDING')
                    
                    this.addUser({name: this.emailLogin, pass: this.passwordLogin})
                    this.authRequest(this.getUser).then(() => {
                        this.changeSubmitStatusLogin('')
                        axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.userToken}`
                        this.getCurrentUserInfo()
                        this.$router.push("/chat");
                    })
                }
            }
        },
    }
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@600&family=Roboto&display=swap');

    *{ 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
    }

    .authorizationForm{
        margin: 150px auto;
        height: 330px;
        width: 370px;
        border: 1px solid #dbdbdb;
        background-color: white;
        text-align: center;
    }

    h1{
        margin: 15px 0px;
    }

    .a-form{
        margin: 10px auto;
        padding: 0px 40px;
    }

    .forgotPassword {
        float: right;
        margin-right: 40px;
        font-family: 'Roboto', sans-serif;
        background-color: inherit;
        cursor: pointer;
        border: none;
        outline: none;
    }

    .forgotPassword a {
        text-decoration: none;
    }
    
</style>