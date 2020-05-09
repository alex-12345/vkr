<template>
    <div class="authorizationPage">
        <div class="authorizationForm">
            <h1>Sapechat</h1>
            <h2>Авторизуйтесь</h2>
            <form class="a-form" @submit.prevent="onSubmit">
                <FormItem 
                    v-for="formItem of formItemArr" :key="formItem.id"
                    v-bind:formItem="formItem"
                    v-on:input="processValue"
                />
                <ButtonItem
                    v-bind:submitStatus="submitStatus"
                />
            </form>
        </div>
    </div>
</template>

<script>
    import FormItem from '@/components/inputForm/FormItem'
    import ButtonItem from '@/components/inputForm/Button'
    import { required, minLength, ipAddress, alpha } from 'vuelidate/lib/validators'
    import {mapGetters, mapActions} from 'vuex'
    //import axios from 'axios'

    export default {
        computed: mapGetters(["formItemArr", "name", "ipAddress", "password", "submitStatus"]),
        validations: {
            name: {
                required,
                alpha
            },
            ipAddress: {
                required,
                ipAddress
            },
            password: {
                required,
                minLength: minLength(6)
            }
        },
        components: {
            FormItem,
            ButtonItem
        },
        methods: {
            ...mapActions(['changeValidationName', 'changeName', 'changeValidationPassword', 'changePassword', 'changeValidationIpAddress', 'changeIpAddress', 'changeSubmitStatus']),
            processValue: function (answer) {
                if (answer.title === 'Имя')
                {
                    this.changeName(answer.value)
                    this.changeValidationName({invalid: this.$v.name.$invalid, required: this.$v.name.required, alpha: this.$v.name.alpha})
                }
                else if (answer.title === 'Пароль') {
                    this.changePassword(answer.value)
                    this.changeValidationPassword({invalid: this.$v.password.$invalid, required: this.$v.password.required, minLength: this.$v.password.minLength})
                }
                else if (answer.title === 'Ip адрес') {
                    this.changeIpAddress(answer.value)
                    this.changeValidationIpAddress({invalid: this.$v.ipAddress.$invalid, required: this.$v.ipAddress.required, ipAddress: this.$v.ipAddress.ipAddress})
                }
            },
            onSubmit: function () {
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.changeSubmitStatus('ERROR')
                    if (this.$v.name.$invalid) {
                        this.changeValidationName({invalid: this.$v.name.$invalid, required: this.$v.name.required, alpha: this.$v.name.alpha})
                    }
                    if (this.$v.password.$invalid) {
                        this.changeValidationPassword({invalid: this.$v.password.$invalid, required: this.$v.password.required, minLength: this.$v.password.minLength})
                    }
                    if (this.$v.ipAddress.$invalid) {
                        this.changeValidationIpAddress({invalid: this.$v.ipAddress.$invalid, required: this.$v.ipAddress.required, ipAddress: this.$v.ipAddress.ipAddress})
                    }
                } else {
                    console.log('submit!')
                    const user = {
                        name: this.name,
                        ipAddress: this.ipAddress,
                        password: this.password
                    }
                    console.log(user)
                    // do your submit logic here
                    this.changeSubmitStatus('PENDING')
                    /*axios.post('https://', platform)
                    .then(response => {
                        console.log(response);
                        this.submitStatus = 'OK'
                    })
                    .catch(error => {
                        console.log(error);
                        this.submitStatus = 'ERROR'
                    });*/
                    setTimeout(() => {
                        this.changeSubmitStatus('OK')
                        this.$router.push('/')
                    }, 500)
                }
            }
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