<template>
    <button 
        class="subChat"
        v-on:click="changeShowCross"
    >
        <img src="https://img.icons8.com/ios-filled/50/000000/hashtag.png"/>
        <div class="title">
            {{subChat.title}}
        </div>
        <DeleteChatButton 
            v-show="subChat.showCross"
        />
    </button>
</template>

<script>
    import DeleteChatButton from '@/views/Authenticated/Sidebar/DeleteChatButton'
    import {mapActions} from 'vuex'

    export default {
        props: ['subChat', 'chatId'],
        methods: {
            ...mapActions(['changeShowSubCross']),
            changeShowCross() {
                this.changeShowSubCross({idChat: this.chatId - 1, idSubChat: this.subChat.id - 1})
            },
        },
        components: {
            DeleteChatButton
        }
    }
</script>

<style scoped>
    .subChat {
        float: right;
        width: 93%;
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
        padding: 5px;
    }

    .subChat:hover {
        background-color: #89e6f1;
    }

    .subChat:focus {
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
        margin: 4px 0px 0px 10px;
        text-overflow: ellipsis;
        overflow: hidden;
        width: 50%;
        float: left;
    }
</style>