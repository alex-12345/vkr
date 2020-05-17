<template>
    <button 
        class="employees"
        v-on:click="$emit('action', {id: navSidebarItem.id - 1})"
    >
        <img :src="navSidebarItem.img" />
        <p>{{navSidebarItem.title}}</p>
    </button>
</template>

<script>
    export default {
        props: ['navSidebarItem'],
    }
</script>

<style scoped>
    .employees {
        width: 100%;
        height: 50px;
        font-size: 16px;
        font-weight: bold;
        color: #2c3e50;
        background-color: inherit;
        border-radius: 10px;
        padding: 5px;
        cursor: pointer;
        box-sizing: border-box;
        border: none;
        outline: none;
    }

    .employees:hover {
        background-color: #89e6f1;
    }

    .employees:focus {
        background-color: #97f3ff;
    }

    .employees img {
        float: left;
        height: 40px;
        width: 40px;
    }

    .employees p {
        margin: 14px 60px;
    }
</style>