<template>
    <button class="logout" v-on:click="logout">Выйти</button>
</template>

<script>
    import {mapActions} from 'vuex'

    export default {
        methods: {
            ...mapActions([
                'authLogout',
            ]),
            logout() {
                this.authLogout()
                .then(() => {
                    this.$router.push('/authorization')
                })
            },
        }
    }
</script>

<style scoped>
    .logout {
        width: 300px;
        background-color: #546e7a;
        color: white;
        border-radius: 5px;
        padding: 12px 16px;
        text-align: left;
        border: none;
        display: block;
        cursor: pointer;
    }

    .dropButton:hover, .dropButton:focus {
        background-color: #B00020;
    }
</style>