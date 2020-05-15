<template>
    <div class="registrationPage">
        <div class="registrationForm">
            <h1>Sapechat</h1>
            <h2>Зарегистрируйтесь</h2>
            <form class="r-form" @submit.prevent="onSubmit">
                <FormItem 
                    v-for="formItem of formItemArrRegisterUser" :key="formItem.id"
                    v-bind:formItem="formItem"
                    v-on:input="processValue"
                />
                <ButtonItem
                    v-bind:submitStatus="submitStatusRegisterUser"
                />
            </form>
        </div>
    </div>
</template>

<script>
    //import axios from 'axios'
    import FormItem from '@/components/inputForm/FormItem'
    import ButtonItem from '@/components/inputForm/Button'
    import { mapGetters, mapActions } from 'vuex'
    import { required, minLength, email, sameAs, alpha } from 'vuelidate/lib/validators'

    export default {
        computed: mapGetters([ 
            "formItemArrRegisterUser", 
            "nameRegisterUser", 
            "secondNameRegisterUser", 
            "emailRegisterUser", 
            "passwordRegisterUser", 
            "repeatPasswordRegisterUser", 
            "submitStatusRegisterUser"
        ]),
        validations: {
            nameRegisterUser: {
                required,
                alpha
            },
            secondNameRegisterUser: {
                required,
                alpha
            },
            emailRegisterUser: {
                required,
                email
            },
            passwordRegisterUser: {
                required,
                minLength: minLength(6)
            },
            repeatPasswordRegisterUser: {
                required,
                sameAsPassword: sameAs('passwordRegisterUser')
            }
        },
        components: {
            FormItem,
            ButtonItem
        },
        methods: {
            ...mapActions([ 
                'changeValidationNameRegisterUser', 
                'changeNameRegisterUser', 
                'changeValidationSecondNameRegisterUser', 
                'changeSecondNameRegisterUser', 
                'changeValidationEmailRegisterUser', 
                'changeEmailRegisterUser',
                'changeValidationPasswordRegisterUser', 
                'changePasswordRegisterUser', 
                'changeValidationRepeatPasswordRegisterUser', 
                'changeRepeatPasswordRegisterUser', 
                'changeSubmitStatusRegisterUser',
                'changeHeaderItems'
            ]),
            processValue: function (answer) {
                if (answer.title === 'Имя')
                {
                    this.changeNameRegisterUser(answer.value)
                    this.changeValidationNameRegisterUser({invalid: this.$v.nameRegisterUser.$invalid, required: this.$v.nameRegisterUser.required, alpha: this.$v.nameRegisterUser.alpha})
                }
                else if (answer.title === 'Фамилия') {
                    this.changeSecondNameRegisterUser(answer.value)
                    this.changeValidationSecondNameRegisterUser({invalid: this.$v.secondNameRegisterUser.$invalid, required: this.$v.secondNameRegisterUser.required, alpha: this.$v.secondNameRegisterUser.alpha})
                }
                else if (answer.title === 'Email') {
                    this.changeEmailRegisterUser(answer.value)
                    this.changeValidationEmailRegisterUser({invalid: this.$v.emailRegisterUser.$invalid, required: this.$v.emailRegisterUser.required, email: this.$v.emailRegisterUser.email})
                }
                else if (answer.title === 'Пароль') {
                    this.changePasswordRegisterUser(answer.value)
                    this.changeValidationPasswordRegisterUser({invalid: this.$v.passwordRegisterUser.$invalid, required: this.$v.passwordRegisterUser.required, minLength: this.$v.passwordRegisterUser.minLength})
                }
                else if (answer.title === 'Повторите пароль') {
                    this.changeRepeatPasswordRegisterUser(answer.value)
                    this.changeValidationRepeatPasswordRegisterUser({invalid: this.$v.repeatPasswordRegisterUser.$invalid, required: this.$v.repeatPasswordRegisterUser.required, sameAsPassword: this.$v.repeatPasswordRegisterUser.sameAsPassword})
                }
            },
            onSubmit() {
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.changeSubmitStatusRegisterUser('ERROR')
                    if (this.$v.nameRegisterUser.$invalid) {
                        this.changeValidationNameRegisterUser({invalid: this.$v.nameRegisterUser.$invalid, required: this.$v.nameRegisterUser.required, alpha: this.$v.nameRegisterUser.alpha})
                    }
                    if (this.$v.secondNameRegisterUser.$invalid) {
                        this.changeValidationSecondNameRegisterUser({invalid: this.$v.secondNameRegisterUser.$invalid, required: this.$v.secondNameRegisterUser.required, alpha: this.$v.secondNameRegisterUser.alpha})
                    }
                    if (this.$v.emailRegisterUser.$invalid) {
                        this.changeValidationEmailRegisterUser({invalid: this.$v.emailRegisterUser.$invalid, required: this.$v.emailRegisterUser.required, email: this.$v.emailRegisterUser.email})
                    }
                    if (this.$v.passwordRegisterUser.$invalid) {
                        this.changeValidationPasswordRegisterUser({invalid: this.$v.passwordRegisterUser.$invalid, required: this.$v.passwordRegisterUser.required, minLength: this.$v.passwordRegisterUser.minLength})
                    }
                    if (this.$v.repeatPasswordRegisterUser.$invalid) {
                        this.changeValidationRepeatPasswordRegisterUser({invalid: this.$v.repeatPasswordRegisterUser.$invalid, required: this.$v.repeatPasswordRegisterUser.required, sameAsPassword: this.$v.repeatPasswordRegisterUser.sameAsPassword})
                    }
                } else {
                    console.log('submit!')
                    const user = {
                        name: this.nameRegisterUser,
                        secondName: this.secondNameRegisterUser,
                        email: this.emailRegisterUser,
                        password: this.passwordRegisterUser,
                        repeatPassword: this.repeatPasswordRegisterUser
                    }
                    console.log(user)
                    // do your submit logic here
                    this.changeSubmitStatusRegisterUser('PENDING')
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
                        this.changeSubmitStatusRegisterUser('OK')
                        this.$router.push('/')
                    }, 500)
                }
            }
        },
        mounted: function () {
            this.changeHeaderItems(4)
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
        margin: 150px auto;
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
</style>