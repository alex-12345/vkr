<template>
    <div class="chats">
        <button class="chat" v-on:click="changeShow" v-bind:class="{focus: chat.showSubChat}">
            <img src="https://img.icons8.com/ios-filled/50/000000/hashtag.png"/>
            <div class="title">
                {{chat.title}}
            </div>
        </button>
        <div class="chatsArea" v-if="chat.showSubChat">
            <SubChats
                v-for="subChat of chat.subChats" :key="subChat.id"
                v-bind:subChat="subChat" 
            />
        </div>
    </div>
</template>

<script>
    import SubChats from '@/views/Authenticated/Sidebar/SubChats'
    import {mapGetters, mapActions} from 'vuex'

    export default {
        props: ['chat'],
        components: {
            SubChats
        },
        computed: mapGetters([
            "subChats",
        ]),
        methods: {
            ...mapActions(['changeShowSubChats']),
            changeShow() {
                console.log('array: ', this.chat.subChats)
                this.changeShowSubChats(this.chat.id - 1)
            },
        }
    }
</script>

<style scoped>
    .chat {
        float: right;
        width: 90%;
        height: 40px;
        color: #2c3e50;
        text-align: left;
        background-color: inherit;
        border-radius: 10px;
        cursor: pointer;
        box-sizing: border-box;
        border: none;
        outline: none;
        margin: 10px 0px 0px 0px;
        padding-left: 15px;
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
    
    .title {
        font-size: 16px;
        font-weight: bold;
        text-align: left;
        margin: 4px 0px 0px 30px;
    }
</style>