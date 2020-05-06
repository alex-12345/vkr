<template>
    <li v-bind:class="{important: menuItem.important}">
        <router-link :to="menuItem">{{menuItem.title}}</router-link>
    </li>
</template>

<script>
    export default {
        props: ['menuItem']
    }
</script>

<style scoped>
    a:link, a:visited {
        display: block;
        text-decoration: none;
        box-sizing: border-box;
        height: 100%;
        margin: 0px 20px;
        padding: 17px 2px 0px 2px;
        color: #2c3e50;
    }

    a:hover, a:active {
        color: #11304e;
    }

    .important {
        margin-left: auto;
    }
</style>