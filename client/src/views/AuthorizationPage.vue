<template>
    <div class="authorizationPage">
        <Header 
            v-bind:headerItemArr="headerItemArr"
        />
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
    import Header from '@/components/Header'
    import FormItem from '@/components/inputForm/FormItem'
    import ButtonItem from '@/components/inputForm/Button'
    import { required, minLength, ipAddress, alpha } from 'vuelidate/lib/validators'
    //import axios from 'axios'

    export default {
        data() {
            return{
                headerItemArr: [
                    {id: 1, title: 'О нас', important: false, path: "#"},
                    {id: 2, title: 'Как начать', important: false, path: "#"},
                    {id: 3, title: 'На главную', important: false, path: "/"},
                    {id: 4, title: 'Зарегестрироваться', important: true, path: "/registration"}
                ],
                formItemArr: [
                    {id: 1, title: 'Имя', type: 'text', required: true, error: false, alpha: true,
                        warningItemArr: [
                            {id: 1, title: "Имя должно состоять только из букв", error: true},
                            {id: 2, title: "Поле обязательно для заполнения", error: true},
                        ]
                    },
                    {id: 2, title: 'Пароль', type: 'password', required: true, error: false, minLength: true,
                        warningItemArr: [
                            {id: 1, title: "Пароль должен иметь как минимум 6 сиволов", error: true},
                            {id: 2, title: "Поле обязательно для заполнения", error: true},
                        ]
                    },
                    {id: 3, title: 'Ip адрес', type: 'text', required: true, error: false, ipAddress: true,
                        warningItemArr: [
                            {id: 1, title: "Ip адрес должен быть формата 0.0.0.0", error: true},
                            {id: 2, title: "Поле обязательно для заполнения", error: true},
                        ]
                    },
                ],
                name: '',
                ipAddress: '',
                password: '',
                submitStatus: null
            }
        },
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
            Header,
            FormItem,
            ButtonItem
        },
        beforeUpdate: function () {

        },
        methods: {
            processValue: function (answer) {
                if (answer.title === 'Имя')
                {
                    this.name = answer.value
                    this.formItemArr[answer.id].error = this.$v.name.$invalid
                    this.formItemArr[answer.id].required = this.$v.name.required
                    this.formItemArr[answer.id].warningItemArr[1].error = this.$v.name.required
                    this.formItemArr[answer.id].alpha = this.$v.name.alpha
                    this.formItemArr[answer.id].warningItemArr[0].error = this.$v.name.alpha
                }
                else if (answer.title === 'Пароль') {
                    this.password = answer.value
                    this.formItemArr[answer.id].error = this.$v.password.$invalid
                    this.formItemArr[answer.id].required = this.$v.password.required
                    this.formItemArr[answer.id].warningItemArr[1].error = this.$v.password.required
                    this.formItemArr[answer.id].minLength = this.$v.password.minLength
                    this.formItemArr[answer.id].warningItemArr[0].error = this.$v.password.minLength
                }
                else if (answer.title === 'Ip адрес') {
                    this.ipAddress = answer.value
                    this.formItemArr[answer.id].error = this.$v.ipAddress.$invalid
                    this.formItemArr[answer.id].required = this.$v.ipAddress.required
                    this.formItemArr[answer.id].warningItemArr[1].error = this.$v.ipAddress.required
                    this.formItemArr[answer.id].ipAddress = this.$v.ipAddress.ipAddress
                    this.formItemArr[answer.id].warningItemArr[0].error = this.$v.ipAddress.ipAddress
                }
            },
            onSubmit: function () {
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                    if (this.$v.name.$invalid) {
                        this.formItemArr[0].error = !(this.$v.name.required && this.$v.name.alpha)
                        this.formItemArr[0].required = this.$v.name.required
                        this.formItemArr[0].warningItemArr[1].error = this.$v.name.required
                        this.formItemArr[0].alpha = this.$v.name.alpha
                        this.formItemArr[0].warningItemArr[0].error = this.$v.name.alpha
                    }
                    if (this.$v.password.$invalid) {
                        this.formItemArr[1].error = !(this.$v.password.required && this.$v.password.minLength)
                        this.formItemArr[1].required = this.$v.password.required
                        this.formItemArr[1].warningItemArr[1].error = this.$v.password.required
                        this.formItemArr[1].minLength = this.$v.password.minLength
                        this.formItemArr[1].warningItemArr[0].error = this.$v.password.minLength
                    }
                    if (this.$v.ipAddress.$invalid) {
                        this.formItemArr[2].error = !(this.$v.ipAddress.required && this.$v.ipAddress.ipAddress)
                        this.formItemArr[2].required = this.$v.ipAddress.required
                        this.formItemArr[2].warningItemArr[1].error = this.$v.ipAddress.required
                        this.formItemArr[2].ipAddress = this.$v.ipAddress.ipAddress
                        this.formItemArr[2].warningItemArr[0].error = this.$v.ipAddress.ipAddress
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
                    this.submitStatus = 'PENDING'
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
                        this.submitStatus = 'OK'
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