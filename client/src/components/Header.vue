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

    export default {
        data() {
            return{
                headerItemArr: [
                    {id: 0, title: 'О нас', important: false, visibility: true, path: "#"},
                    {id: 1, title: 'Как начать', important: false, visibility: true, path: "#"},
                    {id: 2, title: 'На главную', important: false, visibility: false, path: "/"},
                    {id: 3, title: 'Авторизоваться', important: true, visibility: true, path: "/authorization"},
                    {id: 4, title: 'Зарегестрироваться', important: false, visibility: true, path: "/registration"}
                ]
            }
        },
        components: {
            MenuItem
        },
        methods: {
            changeHeader: function (answer) {
                if (answer.id === 0) {
                    this.headerItemArr[0].visibility = false
                    this.headerItemArr[1].visibility = true
                    this.headerItemArr[2].visibility = true
                }
                else if (answer.id === 1) {
                    this.headerItemArr[0].visibility = true
                    this.headerItemArr[1].visibility = false
                    this.headerItemArr[2].visibility = true
                }
                else if (answer.id === 2) {
                    this.headerItemArr[0].visibility = true
                    this.headerItemArr[1].visibility = true
                    this.headerItemArr[2].visibility = false
                    this.headerItemArr[3].visibility = true
                    this.headerItemArr[4].visibility = true
                    this.headerItemArr[4].important = false
                }
                else if (answer.id === 3) {
                    this.headerItemArr[2].visibility = true
                    this.headerItemArr[3].visibility = false
                    this.headerItemArr[4].visibility = true
                    this.headerItemArr[4].important = true
                }
                else if (answer.id === 4) {
                    this.headerItemArr[2].visibility = true
                    this.headerItemArr[3].visibility = true
                    this.headerItemArr[4].visibility = false
                    this.headerItemArr[4].important = false
                }
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