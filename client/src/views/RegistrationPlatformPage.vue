<template>
    <div class="registrationPage">
        <Header 
            v-bind:headerItemArr="headerItemArr"
        />
        <div class="registrationForm">
            <h1>Наш чат</h1>
            <h2>Зарегестрируйте вашу площадку</h2>
            <form class="r-form" @submit.prevent="onSubmit">
                
                <div class="form-item" :class="{ 'errorInput': $v.ipAddress.$error }">
                    <input 
                        type="text" 
                        placeholder="IP адрес" 
                        class="form__input"
                        :class="{ 'error': $v.ipAddress.$error }"
                        v-model="ipAddress"
                        @change="$v.ipAddress.$touch()"
                    >
                    <div class="error" v-if="!$v.ipAddress.minLength">Ip адрес должен быть формата 0.0.0.0</div>
                    <div class="error" v-if="!$v.ipAddress.required">Поле обязательно для заполнения</div>
                </div>
                
                <div class="form-item" :class="{ 'errorInput': $v.accessKey.$error}">
                    <input 
                        type="text" 
                        placeholder="Ключ доступа" 
                        class="form__input"
                        :class="{ 'error': $v.accessKey.$error }"
                        v-model="accessKey"
                        @change="$v.accessKey.$touch()"
                    >
                    <div class="error" v-if="!$v.accessKey.minLength">Ключ доступа должен состоять минимум из 4 цифр</div>
                    <div class="error" v-if="!$v.accessKey.required">Поле обязательно для заполнения</div>
                </div>

                <div class="form-item" :class="{ 'errorInput': $v.title.$error}">
                    <input 
                        type="text" 
                        placeholder="Название" 
                        class="form__input"
                        :class="{ 'error': $v.title.$error }"
                        v-model="title"
                        @change="$v.title.$touch()"
                    >
                    <div class="error" v-if="!$v.title.required">Поле обязательно для заполнения</div>
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
    import { required, minLength, ipAddress } from 'vuelidate/lib/validators'

    export default {
        data() {
            return{
                headerItemArr: [
                    {id: 1, title: 'О нас', important: false, path: "#"},
                    {id: 2, title: 'Как начать', important: false, path: "#"},
                    {id: 3, title: 'На главную', important: false, path: "/"},
                    {id: 4, title: 'Авторизоваться', important: true, path: "/authorization"}
                ],
                ipAddress: '',
                accessKey: '',
                title: '',
                submitStatus: null
            }
        },
        validations: {
            ipAddress: {
                required,
                ipAddress
            },
            accessKey: {
                required,
                minLength: minLength(4)
            },
            title: {
                required
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
                const platform = {
                    ipAddress: this.ipAddress,
                    accessKey: this.accessKey,
                    title: this.title
                }
                console.log(platform)
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
                    this.$router.push('/registrationUser')
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
        height: 405px;
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