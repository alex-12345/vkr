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
        <div class="addArea" v-show="showChats">
            <AddChatButton
                v-bind:type="'chat'"
            />
        </div>
        <div class="inputArea" v-show="showChatInput">
            <InputArea v-bind:type="'chat'" />
        </div>
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
    import AddChatButton from '@/views/Authenticated/Sidebar/AddChatButton'
    import InputArea from '@/views/Authenticated/Sidebar/InputArea'
    import {mapGetters, mapActions} from 'vuex'

    export default {
        computed: mapGetters([
            "chats",
            "showChats",
            "showChatInput"
        ]),
        methods: {
            ...mapActions(['changeShowChats']),
            changeShow() {
                this.changeShowChats()
            }
        },
        components: {
            Chats,
            AddChatButton,
            InputArea
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
        border-radius: 10px;
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
        width: 85%;
        background-color: #97f3ff;
        border-radius: 10px 0px 0px 10px;
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

    .addArea {
        width: 15%;
        height: 40px;
        background-color: #97f3ff;
        border-radius: 0px 10px 10px 0px;
        float: right;
        box-sizing: border-box;
        padding: 10px;
    }

    .inputArea {
        width: 90%;
        height: 40px;
        border-radius: 10px;
        background-color: inherit;
        margin-top: 10px;
        padding-right: 10px;
        float: right;
    }
</style>