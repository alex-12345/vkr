<template>
    <div class="navigation">
        <NavSidebarItem 
            v-for="navSidebarItem of navSidebarArr" :key="navSidebarItem.id"
            v-bind:navSidebarItem="navSidebarItem"
            v-on:action="processAction"
        />
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'
    import NavSidebarItem from '@/views/Authenticated/Sidebar/NavSidebarItem'

    export default {
        computed: mapGetters([
            "navSidebarArr",
        ]),
        methods: {
            ...mapActions([

            ]),
            processAction(answer) {
                console.log('Сотрудники', answer)
            },
        },
        components: {
            NavSidebarItem
        },
    }
</script>

<style scoped>
    .navigation {
        width: 95%;
        margin: 10px auto;
    }
</style>