<template>
    <div class="subChat" v-bind:class="{focusSubChat: subChat.showCross}">
        <button 
            class="subChatTitle"
            v-on:click="changeShowCross"
            v-bind:class="{focus: subChat.showCross}"
        >
            <img src="https://img.icons8.com/ios-filled/50/000000/hashtag.png"/>
            <div class="title">
                {{subChat.title}}
            </div>
        </button>
        <div class="deleteArea" v-show="subChat.showCross">
            <DeleteChatButton 
                v-bind:chatId="chatId"
                v-bind:subChatId="subChat.id"
                v-bind:type="'subChat'"
            />
        </div>
    </div>
</template>

<script>
    import DeleteChatButton from '@/views/Authenticated/Sidebar/DeleteChatButton'
    import {mapActions} from 'vuex'

    export default {
        props: ['subChat', 'chatId'],
        methods: {
            ...mapActions(['changeShowSubCross', 'changeSelectedChat']),
            changeShowCross() {
                this.changeShowSubCross({idChat: this.chatId - 1, idSubChat: this.subChat.id - 1})
                this.changeSelectedChat({chatId: this.chatId, subChatId: this.subChat.id})
            },
        },
        components: {
            DeleteChatButton
        },
    }
</script>

<style scoped>
    .subChat {
        width: 93%;
        height: 40px;
        border-radius: 10px;
        background-color: inherit;
        float: right;
        margin-top: 10px;
    }

    .subChatTitle {
        float: left;
        width: 100%;
        height: 100%;
        color: #2c3e50;
        text-align: left;
        background-color: inherit;
        border-radius: 10px;
        cursor: pointer;
        box-sizing: border-box;
        border: none;
        outline: none;
        padding: 5px;
    }

    .subChatTitle:hover {
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
    
    .title {
        font-size: 16px;
        font-weight: bold;
        text-align: left;
        margin: 4px 0px 0px 10px;
        text-overflow: ellipsis;
        overflow: hidden;
        width: 75%;
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

    .focusSubChat{
        background-color: #97f3ff;
    }
</style>