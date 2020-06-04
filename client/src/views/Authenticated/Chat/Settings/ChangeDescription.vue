<template>
    <form novalidate class="md-layout" @submit.prevent="validateDescription">
        <md-card class="md-layout-item">
            <md-card-header>
                <div class="md-title">Изменить описание</div>
            </md-card-header>

            <md-card-content>
                <div class="md-layout md-gutter">
                    <div class="md-layout-item md-small-size-100">
                        <md-field :class="getValidationClass('description')">
                            <label for="description">Введите описание</label>
                            <md-textarea 
                                name="description" 
                                id="description" 
                                autocomplete="given-name" 
                                v-model="form.description" 
                                :disabled="sending"
                            ></md-textarea>
                            <span class="md-error" v-if="!$v.form.description.required">Поле обязательно</span>
                        </md-field>
                    </div>
                </div>
            </md-card-content>

            <md-progress-bar md-mode="indeterminate" v-if="sending" />

            <md-card-actions>
                <md-button type="submit" class="md-primary" :disabled="sending">Сохранить</md-button>
            </md-card-actions>
        </md-card>
    </form>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import axios from 'axios'
    import { required } from 'vuelidate/lib/validators'
    import { mapActions } from 'vuex'

    export default {
        name: 'ChangeDescription',
        mixins: [validationMixin],
        data: () => ({
            form: {
                description: null,
            },
            sending: false,
        }),
        validations: {
            form: {
                description: {
                    required,
                },
            }
        },
        methods: {
            ...mapActions([
                'changeDescription',
                'removeToken',
                'removeRefreshToken',
                'removeUser'
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
                this.form.description = null
            },
            saveDescriptiond() {
                this.sending = true

                axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.userToken}`
                this.changeDescription(this.form.description)
                .catch(error => {
                    if (error.response.status == 423) {
                        this.removeToken()
                        this.removeRefreshToken()
                        this.removeUser()
                        delete axios.defaults.headers.common['Authorization']
                        this.$router.push('/authorization')
                    }
                })

                this.sending = false
                this.clearForm()
            },
            validateDescription () {
                this.$v.$touch()

                if (!this.$v.$invalid) {
                    this.saveDescriptiond()
                }
            },
        },
    }
</script>

<style scoped>
    .md-layout {
        margin-top: 40px;
    }
</style>