<template>
    <div class="registrationPage">
        <Header 
            v-bind:headerItemArr="headerItemArr"
        />
        <div class="registrationForm">
            <h1>Наш чат</h1>
            <h2>Зарегестрируйтесь</h2>
            <form class="r-form" @submit.prevent="onSubmit">
                
                <div class="form-item" :class="{ 'errorInput': $v.name.$error }">
                    <input 
                        type="text" 
                        placeholder="Имя" 
                        class="form__input"
                        :class="{ 'error': $v.name.$error }"
                        v-model="name"
                        @change="$v.name.$touch()"
                    >
                    <div class="error" v-if="!$v.name.alpha">Имя должно состоять только из букв</div>
                    <div class="error" v-if="!$v.name.required">Поле обязательно для заполнения</div>
                </div>
                
                <div class="form-item" :class="{ 'errorInput': $v.secondName.$error}">
                    <input 
                        type="text" 
                        placeholder="Фамилия" 
                        class="form__input"
                        :class="{ 'error': $v.secondName.$error }"
                        v-model="secondName"
                        @change="$v.secondName.$touch()"
                    >
                    <div class="error" v-if="!$v.secondName.alpha">Фамилия должна состоять из букв</div>
                    <div class="error" v-if="!$v.secondName.required">Поле обязательно для заполнения</div>
                </div>

                <div class="form-item" :class="{ 'errorInput': $v.email.$error}">
                    <input 
                        type="text" 
                        placeholder="Email" 
                        class="form__input"
                        :class="{ 'error': $v.email.$error }"
                        v-model="email"
                        @change="$v.email.$touch()"
                    >
                    <div class="error" v-if="!$v.email.email">Email некорректен</div>
                    <div class="error" v-if="!$v.email.required">Поле обязательно для заполнения</div>
                </div>

                <div class="form-item" :class="{ 'errorInput': $v.password.$error}">
                    <input 
                        type="password" 
                        placeholder="Пароль" 
                        class="form__input"
                        :class="{ 'error': $v.password.$error }"
                        v-model="password"
                        @change="$v.password.$touch()"
                    >
                    <div class="error" v-if="!$v.password.minLength">Пароль должен иметь как минимум {{$v.password.$params.minLength.min}} символов</div>
                    <div class="error" v-if="!$v.password.required">Поле обязательно для заполнения</div>
                </div>

                <div class="form-item" :class="{ 'errorInput': $v.repeatPassword.$error}">
                    <input 
                        type="password" 
                        placeholder="Повторите пароль" 
                        class="form__input"
                        :class="{ 'error': $v.repeatPassword.$error }"
                        v-model="repeatPassword"
                        @change="$v.repeatPassword.$touch()"
                    >
                    <div class="error" v-if="!$v.repeatPassword.sameAsPassword">Пароли должны быть идентичны</div>
                    <div class="error" v-if="!$v.repeatPassword.required">Поле обязательно для заполнения</div>
                </div>

                <button class="button" type="submit" :disabled="submitStatus === 'PENDING'">Отправить</button>
                <p class="typo__p" v-if="submitStatus === 'OK'">Спасибо за регистрацию!</p>
                <p class="typo__p" v-if="submitStatus === 'ERROR'">Пожалуйста, заполните форму правильно.</p>
                <p class="typo__p" v-if="submitStatus === 'PENDING'">Отправка...</p>
            </form>
        </div>
    </div>
</template>

<script>
    import Header from '@/components/Header'
    //import axios from 'axios'
    import { required, minLength, email, sameAs, alpha } from 'vuelidate/lib/validators'

    export default {
        data() {
            return{
                headerItemArr: [
                    {id: 1, title: 'О нас', important: false, path: "#"},
                    {id: 2, title: 'Как начать', important: false, path: "#"},
                    {id: 3, title: 'На главную', important: false, path: "/"},
                    {id: 4, title: 'Авторизоваться', important: true, path: "/authorization"}
                ],
                name: '',
                secondName: '',
                email: '',
                password: '',
                repeatPassword: '',
                submitStatus: null
            }
        },
        validations: {
            name: {
                required,
                alpha
            },
            secondName: {
                required,
                alpha
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
        },
        components: {
            Header
        },
        methods: {
            onSubmit() {
            this.$v.$touch()
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR'
            } else {
                console.log('submit!')
                const user = {
                    name: this.name,
                    secondName: this.secondName,
                    email: this.email,
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

    .registrationForm{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        height: 515px;
        width: 370px;
        border: 1px solid #dbdbdb;
        background-color: white;
        text-align: center;
    }

    h1{
        margin: 15px 0px;
    }

    .r-form{
        margin: 10px auto;
        padding: 0px 40px;
    }

    .form-item{
        display: block;
        width: 100%;
        height: 40px;
        margin-bottom: 30px;
    }

    .form-item div.error {
        display: none;
    }

    div.errorInput div.error {
        display: block;
        font-size: 12px;
        text-align: left;
        color: red;
        margin-top: 2px;
    }

    .form__input {
        width: 100%;
        height: 100%;
        border: 1px solid #dbdbdb;
        border-radius: 5px;
        outline: none;
        background-color: #fafafa;
        padding-left: 10px;

    }

    .form__input:focus {
        border-color: blue;
    }

    input.error {
        border-color: red;
    }

    .button{
        display: block;
        width: 100%;
        height: 40px;
        background-color: #5e90ff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 0px 40px;
    }

    button:hover{
        cursor: pointer; 
    }

    .typo__p {
        font-size: 12px;
        text-align: left;
        color: red;
        margin-top: 2px;
    }
    
</style>