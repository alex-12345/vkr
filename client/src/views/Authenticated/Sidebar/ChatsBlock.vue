<template>
    <div class="chatsBlock">
        <button class="title" v-on:click="changeShow" v-bind:class="{focus: showChats}">
            <div class="img">
                <img src="@/images/listIcon.png" alt="list icon" v-bind:class="{show: showChats}">
            </div>
            <div class="name">
                Чаты
            </div>
        </button>
        <div class="chatsArea" v-if="showChats">
            <Chats
                v-for="chat of chats" :key="chat.id"
                v-bind:chat="chat" 
            />
        </div>
    </div>
</template>

<script>
    import Chats from '@/views/Authenticated/Sidebar/Chats'
    import {mapGetters, mapActions} from 'vuex'

    export default {
        computed: mapGetters([
            "chats",
            "showChats",
        ]),
        methods: {
            ...mapActions(['changeShowChats']),
            changeShow() {
                this.changeShowChats()
            }
        },
        components: {
            Chats
        }
    }
</script>

<style scoped>
    *{
        margin: 0px;
        padding: 0px;
    }

    .chatsBlock {
        width: 95%;
        height: 100%;
        margin: 10px auto;
        overflow: auto;
    }

    .chatsBlock::-webkit-scrollbar {
        width: 3%;
        padding: 0px;
        margin: 0px;
    }

    .chatsBlock::-webkit-scrollbar-thumb {
        background: #f5f5f6;
        border-radius: 10px;
    }

    .title {
        width: 100%;
        height: 40px;
        padding: 5px;
        color: #2c3e50;
        background-color: inherit;
        border-radius: 10px;
        cursor: pointer;
        box-sizing: padding-box;
        border: none;
        outline: none;
    }

    .title:hover {
        background-color: #89e6f1;
    }

    .focus, .focus:hover {
        background-color: #97f3ff;
    }

    img {
        float: left;
        height: 25px;
    }

    .show {
        transform: rotate(90deg);
    }

    .name {
        font-size: 16px;
        font-weight: bold;
        text-align: left;
        margin: 4px 0px 0px 40px;
    }

    
</style>