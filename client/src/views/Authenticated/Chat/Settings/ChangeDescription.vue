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

            <md-card-actions>
                <md-button type="submit" class="md-primary" :disabled="sending">Сохранить</md-button>
            </md-card-actions>
        </md-card>
    </form>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required } from 'vuelidate/lib/validators'

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
                console.log('description: ', this.form.description)
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