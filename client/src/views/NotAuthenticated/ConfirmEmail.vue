<template>
    <div>
        <md-dialog :md-active.sync="error">
            <md-dialog-title>Ошибка</md-dialog-title>

            <md-dialog-content>
                <p>Ссылка недействительна.</p>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="error = false">Ок</md-button>
            </md-dialog-actions>
        </md-dialog>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        data: () => ({
            params: '',
            error: false
        }),
        methods: {
            ...mapActions([ 
                'confirmChangeEmail',
                'changeSubmitStatusLogin',
                'getCurrentUserInfo'
            ]),
        },
        created() {
            this.params = window
                .location
                .search
                .replace('?','')
                .split('&')
                .reduce(
                    function(p,e){
                        var a = e.split('=');
                        p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                        return p;
                    },
                    {}
                );
            console.log('params: ', this.params)
            this.confirmChangeEmail(this.params)
                .then(() => {
                    this.getCurrentUserInfo()
                    this.$router.push("/chat");
                })
                .catch(error => {
                    if (error.response.status == 403) {
                        this.error = true
                    }
                    else if (error.response.status == 423) {
                        this.changeSubmitStatusLogin('USER_IS_BLOCKED')
                        this.$router.push("/authorization");
                    }
                });
        }
    }
</script>

<style scoped>
    .md-card{
        margin: 80px auto;
    }
</style>