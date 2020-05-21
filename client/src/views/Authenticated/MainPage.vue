<template>
    <div id="wrapper">
        <Sidebar />
        <Chat v-bind:messageArr="selectedChat"/>
    </div>
</template>

<script>
    import Sidebar from '@/views/Authenticated/Sidebar/Sidebar'
    import Chat from '@/views/Authenticated/Chat/Chat'
    import {mapGetters} from 'vuex'
    
    export default {
        computed: mapGetters([
            "selectedChat",
            "messages"
        ]),
        components: {
            Sidebar,
            Chat
        },
    }
</script>

<style scoped>
    #wrapper {
        width: 100%;
        height: 100%;
    }
</style>