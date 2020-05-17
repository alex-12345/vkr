<template>
    <aside class="aside">
        <h2>Sapechat</h2>
        <LineSidebar />
        <Navigation />
        <LineSidebar />
        <ChatsBlock />
        <UserArea />
    </aside>
</template>

<script>
    import UserArea from '@/views/Authenticated/Sidebar/UserArea'
    import LineSidebar from '@/views/Authenticated/Sidebar/LineSidebar'
    import Navigation from '@/views/Authenticated/Sidebar/Navigation'
    import ChatsBlock from '@/views/Authenticated/Sidebar/ChatsBlock'

    export default {
        components: {
            UserArea,
            LineSidebar,
            Navigation,
            ChatsBlock
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

    h2 {
        margin: 10px auto;
        font-size: 28px;
    }
</style>