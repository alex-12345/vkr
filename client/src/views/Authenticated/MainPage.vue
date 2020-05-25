<template>
    <div id="wrapper">
        <Sidebar />
        <router-view />
    </div>
</template>

<script>
    import Sidebar from '@/views/Authenticated/Sidebar/Sidebar'
    import {mapGetters} from 'vuex'
    
    export default {
        computed: mapGetters([
            "selectedChat",
        ]),
        components: {
            Sidebar,
        },
    }
</script>

<style scoped>
    #wrapper {
        width: 100%;
        height: 100%;
    }
</style>