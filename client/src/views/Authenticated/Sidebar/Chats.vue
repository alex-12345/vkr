<template>
    <div class="chats">
        <div 
            class="title" 
        >
            <button class="open" v-on:click="changeShow">
                <div class="iconChat">
                    <img src="@/images/strelka.png" alt="icon chat" v-bind:class="{show: chat.showSubChat}" >
                </div>
                <div class="text">
                    {{chat.title}}
                </div>
            </button>
            <div class="deleteArea">
                <DeleteChatButton 
                    v-bind:chatId="chat.id"
                    v-bind:type="'chat'"
                />
            </div>
            <div class="addArea">
                <AddChatButton
                    v-bind:chatId="chat.id"
                    v-bind:type="'subChat'"
                />
            </div>
        </div>
        <InputArea 
            v-bind:type="'subChat'"
            v-bind:chatId="chat.id"
            v-show="chat.showSubChatInput" 
        />
        <div class="subChatsArea" v-show="chat.showSubChat">
            <SubChats
                v-for="subChat of chat.subChats" :key="subChat.id"
                v-bind:subChat="subChat"
                v-bind:chatId="chat.id" 
            />
        </div>
    </div>
</template>

<script>
    import SubChats from '@/views/Authenticated/Sidebar/SubChats'
    import AddChatButton from '@/views/Authenticated/Sidebar/AddChatButton'
    import DeleteChatButton from '@/views/Authenticated/Sidebar/DeleteChatButton'
    import InputArea from '@/views/Authenticated/Sidebar/InputArea'
    import {mapGetters, mapActions} from 'vuex'

    export default {
        props: ['chat'],
        components: {
            SubChats,
            DeleteChatButton,
            AddChatButton,
            InputArea
        },
        computed: mapGetters([
            "subChats",
            "showCross"
        ]),
        methods: {
            ...mapActions(['changeShowSubChats', 'changeSelectedChat', 'changeSelectedChatId']),
            changeShow() {
                this.changeShowSubChats(this.chat.id - 1)
                this.changeSelectedChatId({chatId: this.chat.id, subChatId: undefined})
                this.changeSelectedChat({chatId: this.chat.id, subChatId: undefined})
            }
        }
    }
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Nunito&family=Roboto&display=swap');

    .chats, .chat, .inputArea {
        border-radius: 10px;
        background-color: inherit;
    }

    .chats {
        width: 100%;
    }

    .title {
        width: 290px;
        height: 45px;
        position: relative;
        display: block;
        margin: 0px auto;
        background-color: inherit;
        border-radius: 10px;
    }

    .title:hover, .title:focus {
        background-color: #262336;
    }

    .open {
        width: 224px;
        margin-top: 3px;
        background-color: inherit;
        color: #b4b2bb;
        border-radius: 10px;
        cursor: pointer;
        border: none;
        outline: none;
        text-align: left;
    }

    .iconChat {
        position: absolute;
        left: 8px;
        top: 12px;
        transform: rotate(-90deg);
    }

    .iconChat img {
        width: 20px;
    }

    .show {
        transform: rotate(90deg);
    }
    
    .text {
        display: inline-block;
        max-width: 180px;
        height: 100%;
        margin-left: 42px;
        font-family: 'Nunito', sans-serif;
        font-size: 24px;
        text-align: left;
        font-weight: bold;
        color: #b4b2bb;
        padding-top: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .addArea {
        float: right;
        height: 100%;
    }

    .deleteArea {
        float: right;
        height: 100%;
    }
</style>