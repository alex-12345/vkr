<template>
    <form novalidate class="md-layout" @submit.prevent="saveRoles">
        <md-card class="md-layout-item">
            <md-card-header>
                <div class="md-title">Изменить роль</div>
            </md-card-header>

            <md-card-content>
                <div class="md-layout md-gutter">
                    <div class="md-layout-item md-small-size-100">
                        <md-checkbox v-model="form.roles" value="ROLE_USER" :disabled="sending">Пользователь</md-checkbox>
                        <md-checkbox v-model="form.roles" value="ROLE_ADMIN" :disabled="sending">Администратор</md-checkbox>
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
    import { mapActions } from 'vuex'
    import axios from 'axios'

    export default {
        name: 'ChangeRoles',
        data: () => ({
            form: {
                roles: [],
            },
            sending: false,
            error: {
                show: false,
                body: '',
            },
        }),
        methods: {
            ...mapActions([
                'changeRoles',
                'changeSubmitStatusLogin',
                'authLogout'
            ]),
            clearForm () {
                this.form.roles = null
            },
            saveRoles() {
                this.sending = true
                
                const roles = {
                    id: localStorage.user_id,
                    roles: this.form.roles
                }
                
                axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.userToken}`
                this.changeRoles(roles)
                .then(() => {
                    this.error.show = false
                    this.clearForm()
                })
                .catch((error) => {
                    if (error.response.status == 423) {
                        this.changeSubmitStatusLogin('USER_IS_BLOCKED')
                        this.authLogout()
                        .then(() => {
                            this.$router.push('/authorization')
                        })
                    }
                });

                this.sending = false
                this.clearForm()
            },
        },
    }
</script>

<style scoped>
    .md-layout {
        margin-top: 40px;
    }
</style>