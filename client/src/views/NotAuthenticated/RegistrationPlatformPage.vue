<template>
    <div class="registrationPage">
        <div class="registrationForm">
            <h1>Sapechat</h1>
            <h2>Зарегистрируйте вашу площадку</h2>
            <form class="r-form" @submit.prevent="onSubmit">
                <FormItem 
                    v-for="formItem of formItemArrRegisterPage" :key="formItem.id"
                    v-bind:formItem="formItem"
                    v-on:input="processValue"
                />
                <ButtonItem
                    v-bind:submitStatus="submitStatusRegisterPage"
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
    import { required, minLength, ipAddress } from 'vuelidate/lib/validators'

    export default {
        computed: mapGetters([ 
            "formItemArrRegisterPage", 
            "titleRegisterPage", 
            "ipAddressRegisterPage", 
            "accessKeyRegisterPage", 
            "submitStatusRegisterPage"
        ]),
        validations: {
            ipAddressRegisterPage: {
                required,
                ipAddress
            },
            accessKeyRegisterPage: {
                required,
                minLength: minLength(4)
            },
            titleRegisterPage: {
                required
            }
        },
        components: {
            FormItem,
            ButtonItem
        },
        methods: {
            ...mapActions([ 
                'changeValidationTitleRegisterPage', 
                'changeTitleRegisterPage', 
                'changeValidationAccessKeyRegisterPage', 
                'changeAccessKeyRegisterPage', 
                'changeValidationIpAddressRegisterPage', 
                'changeIpAddressRegisterPage', 
                'changeSubmitStatusRegisterPage',
                'changeHeaderItems'
            ]),
            processValue: function (answer) {
                if (answer.title === 'Название')
                {
                    this.changeTitleRegisterPage(answer.value)
                    this.changeValidationTitleRegisterPage({invalid: this.$v.titleRegisterPage.$invalid, required: this.$v.titleRegisterPage.required})
                }
                else if (answer.title === 'Ключ доступа') {
                    this.changeAccessKeyRegisterPage(answer.value)
                    this.changeValidationAccessKeyRegisterPage({invalid: this.$v.accessKeyRegisterPage.$invalid, required: this.$v.accessKeyRegisterPage.required, minLength: this.$v.accessKeyRegisterPage.minLength})
                }
                else if (answer.title === 'Ip адрес') {
                    this.changeIpAddressRegisterPage(answer.value)
                    this.changeValidationIpAddressRegisterPage({invalid: this.$v.ipAddressRegisterPage.$invalid, required: this.$v.ipAddressRegisterPage.required, ipAddress: this.$v.ipAddressRegisterPage.ipAddress})
                }
            },
            onSubmit: function () {
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.changeSubmitStatusRegisterPage('ERROR')
                    if (this.$v.titleRegisterPage.$invalid) {
                        this.changeValidationTitleRegisterPage({invalid: this.$v.titleRegisterPage.$invalid, required: this.$v.titleRegisterPage.required})
                    }
                    if (this.$v.accessKeyRegisterPage.$invalid) {
                        this.changeValidationAccessKeyRegisterPage({invalid: this.$v.accessKeyRegisterPage.$invalid, required: this.$v.accessKeyRegisterPage.required, minLength: this.$v.accessKeyRegisterPage.minLength})
                    }
                    if (this.$v.ipAddressRegisterPage.$invalid) {
                        this.changeValidationIpAddressRegisterPage({invalid: this.$v.ipAddressRegisterPage.$invalid, required: this.$v.ipAddressRegisterPage.required, ipAddress: this.$v.ipAddressRegisterPage.ipAddress})
                    }
                } else {
                    console.log('submit!')
                    const user = {
                        title: this.titleRegisterPage,
                        ipAddress: this.ipAddressRegisterPage,
                        accessKey: this.accessKeyRegisterPage
                    }
                    console.log(user)
                    // do your submit logic here
                    this.changeSubmitStatusRegisterPage('PENDING')
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
                        this.changeSubmitStatusRegisterPage('OK')
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
</style>