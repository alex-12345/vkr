<template>
    <aside class="aside">
        <h2>Sapechat</h2>
        <LineSidebar />
        <Navigation />
        <LineSidebar />
        <div class="channels">
        </div>
        <UserArea />
    </aside>
</template>

<script>
    import UserArea from '@/views/Authenticated/Sidebar/UserArea'
    import LineSidebar from '@/views/Authenticated/Sidebar/LineSidebar'
    import Navigation from '@/views/Authenticated/Sidebar/Navigation'

    export default {
        components: {
            UserArea,
            LineSidebar,
            Navigation
        }
    }
</script>

<style scoped>
    .aside {   
        display: flex;
        flex-direction: column;     
        width: 300px;
        height: 100%;
        float: left;
        background-color: #7edeea;
    }

    .channels {
        flex: 1 0 auto;
    }

    h2 {
        margin: 10px auto;
        font-size: 28px;
    }
</style>