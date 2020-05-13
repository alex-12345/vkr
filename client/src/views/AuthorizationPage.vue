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
        </div>
    </div>
</template>

<script>
    import FormItem from '@/components/inputForm/FormItem'
    import ButtonItem from '@/components/inputForm/Button'
    import { required, minLength, ipAddress, email } from 'vuelidate/lib/validators'
    import {mapGetters, mapActions} from 'vuex'
    import Request from "@/services/Request.js";
    //import axios from 'axios'

    export default {
        computed: mapGetters(["formItemArrLogin", "emailLogin", "ipAddressLogin", "passwordLogin", "submitStatusLogin"]),
        validations: {
            emailLogin: {
                required,
                email
            },
            ipAddressLogin: {
                required,
                ipAddress
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
            ...mapActions(['changeValidationEmailLogin', 'changeEmailLogin', 'changeValidationPasswordLogin', 'changePasswordLogin', 'changeValidationIpAddressLogin', 'changeIpAddressLogin', 'changeSubmitStatusLogin', 'changeHeaderItems']),
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
                else if (answer.title === 'Ip адрес') {
                    this.changeIpAddressLogin(answer.value)
                    this.changeValidationIpAddressLogin({invalid: this.$v.ipAddressLogin.$invalid, required: this.$v.ipAddressLogin.required, ipAddress: this.$v.ipAddressLogin.ipAddress})
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
                    if (this.$v.ipAddressLogin.$invalid) {
                        this.changeValidationIpAddressLogin({invalid: this.$v.ipAddressLogin.$invalid, required: this.$v.ipAddressLogin.required, ipAddress: this.$v.ipAddressLogin.ipAddress})
                    }
                } else {
                    console.log('submit!')
                    const user = {
                        username: this.emailLogin,
                        password: this.passwordLogin
                    }
                    console.log(user)
                    // do your submit logic here
                    this.changeSubmitStatusLogin('PENDING')
                    Request.postUser()
                }
            }
        },
        mounted: function () {
            this.changeHeaderItems(3)
        }
    }
</script>

<style scoped>
    *{ 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
    }

    .authorizationForm{
        margin: 150px auto;
        height: 405px;
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
    
</style>