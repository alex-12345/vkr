<template>
    <div class="chats">
        <button 
            class="chat" 
            v-on:click="changeShow" 
            v-bind:class="{focus: chat.showSubChat}"
        >
            <img 
                src="@/images/listIcon.png" 
                alt="list icon" 
                v-bind:class="{expand: chat.showSubChat}" 
            >
            <div class="title">
                {{chat.title}}
            </div>
        </button>
        <div class="deleteArea" v-show="chat.showCross">
            <DeleteChatButton 
                v-bind:chatId="chat.id"
                v-bind:type="'chat'"
            />
        </div>
        <div class="subChatsArea" v-if="chat.showSubChat">
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
    import DeleteChatButton from '@/views/Authenticated/Sidebar/DeleteChatButton'
    import {mapGetters, mapActions} from 'vuex'

    export default {
        props: ['chat'],
        components: {
            SubChats,
            DeleteChatButton
        },
        computed: mapGetters([
            "subChats",
            "showCross"
        ]),
        methods: {
            ...mapActions(['changeShowSubChats', 'changeShowCross']),
            changeShow() {
                this.changeShowSubChats(this.chat.id - 1)
                this.changeShowCross(this.chat.id - 1)
            },
        }
    }
</script>

<style scoped>
    .chats {
        width: 97%;
        border-radius: 10px;
        background-color: inherit;
        float: right;
        margin-top: 10px;
    }

    .chat {
        float: left;
        width: 100%;
        height: 40px;
        color: #2c3e50;
        text-align: left;
        background-color: inherit;
        border-radius: 10px;
        cursor: pointer;
        box-sizing: padding-box;
        border: none;
        outline: none;
        padding: 5px;
    }

    .chat:hover {
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

    .expand {
        transform: rotate(90deg);
    }
    
    .title {
        font-size: 16px;
        font-weight: bold;
        text-align: left;
        text-overflow: ellipsis;
        overflow: hidden;
        margin: 4px 0px 0px 10px;
        width: 70%;
        float: left;
        white-space: nowrap;
    }

    .deleteArea {
        width: 15%;
        height: 100%;
        background-color: #97f3ff;
        border-radius: 0px 10px 10px 0px;
        float: right;
        box-sizing: border-box;
        padding: 10px;
    }
</style>