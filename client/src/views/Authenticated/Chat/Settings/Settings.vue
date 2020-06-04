<template>
    <div class="settings">
        <SendInvitation v-if="rolesUser == 'ROLE_SUPER_ADMIN'"/>
        <ChangeEmail />
        <ChangePassword />
        <ChangeDescription />
        <ChangeAvatar />
        <button class="logout" v-on:click="logout">Выйти</button>
    </div>
</template>

<script>
    import ChangeAvatar from '@/views/Authenticated/Chat/Settings/ChangeAvatar';
    import ChangePassword from '@/views/Authenticated/Chat/Settings/ChangePassword';
    import ChangeDescription from '@/views/Authenticated/Chat/Settings/ChangeDescription';
    import ChangeEmail from '@/views/Authenticated/Chat/Settings/ChangeEmail';
    import SendInvitation from '@/views/Authenticated/Chat/Settings/SendInvitation';
    import { mapGetters, mapActions } from 'vuex'

    export default {
        name: 'Settings',
        computed: mapGetters([
            "rolesUser",
        ]),
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
        },
        components: {
            ChangeAvatar,
            ChangePassword,
            ChangeDescription,
            ChangeEmail,
            SendInvitation,
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

    .settings {
        margin-left: 360px;
        height: 100%;
        padding: 0px 40px 40px 40px;
        overflow: auto;
        background-color: white;
    }
</style>