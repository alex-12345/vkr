<template>
    <div class="home">
        <Header />
        <router-view></router-view>
    </div>
</template>

<script>
    import Header from '@/components/Header'
    import {mapActions} from 'vuex'

    export default {
        beforeUpdate: function () {
            this.changeHeaderItems(2)
        },
        methods: mapActions(['changeHeaderItems']),
        components: {
            Header
        }
    }
</script>

<style scoped>
    .home {
        height: 100%;
    }
</style>