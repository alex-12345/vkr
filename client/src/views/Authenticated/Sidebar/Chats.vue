<template>
    <div class="chats">
        <button 
            class="chat" 
            v-on:click="changeShow" 
            v-bind:class="{focus: chat.showSubChat}"
        >
            <img 
                src="https://img.icons8.com/ios-filled/50/000000/hashtag.png" 
                v-if="!chat.thereIsSsubChats"
            >
            <img 
                src="@/images/listIcon.png" 
                alt="list icon" 
                v-bind:class="{expand: chat.showSubChat}" 
                v-if="chat.thereIsSsubChats"
            >
            <div class="title">
                {{chat.title}}
            </div>
            <DeleteChatButton 
                v-show="chat.showCross"
            />
        </button>
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
    .chat {
        float: right;
        width: 97%;
        height: 40px;
        color: #2c3e50;
        text-align: left;
        background-color: inherit;
        border-radius: 10px;
        cursor: pointer;
        box-sizing: padding-box;
        border: none;
        outline: none;
        margin: 10px 0px 0px 0px;
        padding: 5px;
    }

    .chat:hover {
        background-color: #89e6f1;
    }

    .focus, .focus:hover {
        background-color: #97f3ff;
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
        width: 50%;
        float: left;
    }
</style>