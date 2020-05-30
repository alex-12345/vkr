<template>
    <div class="chatsBlock">
        <div class="title">
            <button class="open" v-on:click="changeShow">
                <div class="iconChat">
                    <img src="@/images/chatIcon.png" alt="icon chat">
                </div>
                <div class="name">
                    чаты
                    <div class="iconList">
                        <img src="@/images/listIconChat.png" alt="list icon" v-bind:class="{show: showChats}">
                    </div>
                </div>
            </button>
            <div class="addArea">
                <AddChatButton
                    v-bind:type="'chat'"
                />
            </div>
        </div>
        <InputArea 
            v-bind:type="'chat'" 
            v-show="showChatInput"
        />
        <div class="chatsArea" v-show="showChats">
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
                this.$router.push("/chat/messages").catch(() => {});
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
    @import url('https://fonts.googleapis.com/css2?family=Nunito&family=Roboto&display=swap');
    .chatsBlock {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        margin: 0px auto;
        overflow: auto;
        text-align: center;
    }

    .chatsBlock::-webkit-scrollbar {
        width: 3px;
        padding: 0px;
        margin: 0px;
    }

    .chatsBlock::-webkit-scrollbar-thumb {
        background: #b4b2bb;
        border-radius: 10px;
    }

    .title {
        width: 320px;
        height: 45px;
        position: relative;
        display: block;
        margin: 0px auto;
        background-color: inherit;
        border-radius: 10px;
    }

    .open {
        width: 287px;
        margin-top: 3px;
        background-color: inherit;
        color: #b4b2bb;
        border-radius: 10px;
        text-align: left;
        cursor: pointer;
        border: none;
        outline: none;
    }

    .title:hover, .title:focus {
        background-color: #262336;
        color: #f2f2f4;
    }

    .iconChat {
        position: absolute;
        left: 9px;
        top: 9px;
    }

    .iconChat img {
        width: 25px;
    }

    .name {
        display: inline-block;
        height: 100%;
        margin-left: 42px;
        font-family: 'Roboto', sans-serif;
        font-size: 26px;
        text-align: left;
        font-weight: lighter;
        padding-top: 3px;
    }

    .iconList {
        display: inline-block;
        height: 100%;
        margin-left: 10px;
        transform: rotate(-90deg);
    }

    .iconList img {
        width: 18px;
    }

    .show {
        transform: rotate(90deg);
    }

    .addArea {
        float: right;
        height: 100%;
    }

    .chatsArea {
        max-width: 100%;
    }
</style>