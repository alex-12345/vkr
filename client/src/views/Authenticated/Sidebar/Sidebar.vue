<template>
    <aside class="aside">
        <Search />
        <Employees />
        <ChatsBlock />
        <UserArea />
    </aside>
</template>

<script>
    import UserArea from '@/views/Authenticated/Sidebar/UserArea'
    import Employees from '@/views/Authenticated/Sidebar/Employees'
    import ChatsBlock from '@/views/Authenticated/Sidebar/ChatsBlock'
    import Search from '@/views/Authenticated/Sidebar/Search'

    export default {
        components: {
            UserArea,
            Employees,
            ChatsBlock,
            Search
        }
    }
</script>

<style scoped>
    .aside {   
        display: flex;
        flex-direction: column;     
        width: 360px;
        height: 100%;
        float: left;
        background-color: #2c2843;
    }
</style>