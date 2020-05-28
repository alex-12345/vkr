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
    import FormItem from '@/components/inputForm/FormItem'
    import ButtonItem from '@/components/inputForm/Button'
    import { mapGetters, mapActions } from 'vuex'
    import { required, minLength } from 'vuelidate/lib/validators'

    export default {
        computed: mapGetters([ 
            "formItemArrRegisterPage",  
            "accessKeyRegisterPage", 
            "submitStatusRegisterPage"
        ]),
        validations: {
            accessKeyRegisterPage: {
                required,
                minLength: minLength(4)
            },
        },
        components: {
            FormItem,
            ButtonItem
        },
        methods: {
            ...mapActions([ 
                'changeValidationAccessKeyRegisterPage', 
                'changeAccessKeyRegisterPage',
                'changeSubmitStatusRegisterPage',
                'workspaceInfo'
            ]),
            processValue: function (answer) {
                if (answer.title === 'Ключ доступа') {
                    this.changeAccessKeyRegisterPage(answer.value)
                    this.changeValidationAccessKeyRegisterPage({invalid: this.$v.accessKeyRegisterPage.$invalid, required: this.$v.accessKeyRegisterPage.required, minLength: this.$v.accessKeyRegisterPage.minLength})
                }
            },
            onSubmit: function () {
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.changeSubmitStatusRegisterPage('ERROR')
                    if (this.$v.accessKeyRegisterPage.$invalid) {
                        this.changeValidationAccessKeyRegisterPage({invalid: this.$v.accessKeyRegisterPage.$invalid, required: this.$v.accessKeyRegisterPage.required, minLength: this.$v.accessKeyRegisterPage.minLength})
                    }
                } else {
                    console.log('submit!')
                    
                    // do your submit logic here
                    this.changeSubmitStatusRegisterPage('PENDING')
                    
                    this.workspaceInfo(this.accessKeyRegisterPage).then(() => {
                        this.$router.push("/registrationUser");
                    })
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
        margin: 150px auto;
        height: 260px;
        width: 370px;
        border: 1px solid #dbdbdb;
        background-color: white;
        text-align: center;
    }

    h1{
        margin: 15px 0px;
    }

    .r-form{
        margin: 20px auto;
        padding: 0px 40px;
    }
</style>