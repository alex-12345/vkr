<template>
    <aside class="aside">
        <h2>Sapechat</h2>
        <div class="channels">
        </div>
        <UserArea />
    </aside>
</template>

<script>
    import UserArea from '@/views/Authenticated/Sidebar/UserArea'

    export default {
        components: {
            UserArea
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