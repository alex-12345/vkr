<template>
    <header>
        <h1>Sapechat</h1>
        <nav>
            <ul>
                <MenuItem 
                    v-for="menuItem of headerItemArr" :key="menuItem.id"
                    v-bind:menuItem="menuItem"
                    v-on:clickItem="changeHeader"
                />
            </ul>
        </nav>
    </header>
</template>

<script>
    import MenuItem from '@/components/MenuItem'
    import {mapGetters, mapActions} from 'vuex'

    export default {
        components: {
            MenuItem
        },
        computed: mapGetters(["headerItemArr"]),
        methods: {
            ...mapActions(['changeHeaderItems']),
            changeHeader: function (answer) {
                this.changeHeaderItems(answer.id)
            }
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