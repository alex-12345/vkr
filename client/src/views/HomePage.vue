<template>
    <div class="home">
        <Header 
            v-bind:headerItemArr="headerItemArr"
        />
    </div>
</template>

<script>
    import Header from '@/components/Header'

    export default {
        data() {
            return{
                headerItemArr: [
                    {id: 1, title: 'О нас', important: false, path: "#"},
                    {id: 2, title: 'Как начать', important: false, path: "#"},
                    {id: 3, title: 'Авторизоваться', important: true, path: "/authorization"},
                    {id: 4, title: 'Зарегестрироваться', important: false, path: "/registration"}
                ]
            }
        },
        components: {
            Header
        }
    }
</script>

<style scoped>
    
</style>