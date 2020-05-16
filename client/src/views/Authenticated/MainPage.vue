<template>
    <div id="wrapper">
        <Sidebar />
        <Chat />
    </div>
</template>

<script>
    import Sidebar from '@/views/Authenticated/Sidebar/Sidebar'
    import Chat from '@/views/Authenticated/Chat'
    
    export default {
        components: {
            Sidebar,
            Chat
        },
    }
</script>

<style scoped>
    #wrapper {
        width: 100%;
        height: 100vh;
    }
</style>