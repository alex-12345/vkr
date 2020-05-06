<template>
    <header>
        <h1>Наш чат</h1>
        <nav>
            <ul>
                <MenuItem 
                    v-for="menuItem of headerItemArr" :key="menuItem.id"
                    v-bind:menuItem="menuItem"
                />
            </ul>
        </nav>
    </header>
</template>

<script>
    import MenuItem from '@/components/MenuItem'

    export default {
        props: ['headerItemArr'],
        components: {
            MenuItem
        }
    }
</script>

<style scoped>
    ul {
        list-style: none;
        display: flex;
        height: 50px;
    }

    h1 {
        margin: 7px 10px 0px 10px;
        float: left;
    }

    header {
        box-sizing: inherit;
        text-align: justify;
        height: 50px;
        width: 100%;
        background-color: #e7e6fc;
        border-bottom: 1px solid #9e9e9e;
    }


</style>