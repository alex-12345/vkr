<template>
    <div id="wrapper">
        <Sidebar />
        <router-view />
    </div>
</template>

<script>
    import Sidebar from '@/views/Authenticated/Sidebar/Sidebar'
    
    export default {
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