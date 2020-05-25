<template>
    <div class="subChat">
        <button 
            class="subChatTitle"
            v-on:click="changeShowCross"
        >
            <div class="iconChat">
                <img src="@/images/tag.png" alt="tag">
            </div>
            <div class="title">
                {{subChat.title}}
            </div>
            <div class="deleteArea">
                <DeleteChatButton 
                    v-bind:chatId="chatId"
                    v-bind:subChatId="subChat.id"
                    v-bind:type="'subChat'"
                />
            </div>
        </button>
    </div>
</template>

<script>
    import DeleteChatButton from '@/views/Authenticated/Sidebar/DeleteChatButton'
    import {mapActions} from 'vuex'

    export default {
        props: ['subChat', 'chatId'],
        methods: {
            ...mapActions(['changeSelectedChat', 'changeSelectedChatId']),
            changeShowCross() {
                this.changeSelectedChatId({chatId: this.chatId, subChatId: this.subChat.id})
                this.changeSelectedChat({chatId: this.chatId, subChatId: this.subChat.id})
            },
        },
        components: {
            DeleteChatButton
        },
    }
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Nunito&family=Roboto&display=swap');

    .subChat {
        width: 100%;
        background-color: inherit;
    }

    .subChatTitle {
        width: 290px;
        height: 45px;
        position: relative;
        display: block;
        margin: 0px auto;
        background-color: inherit;
        border-radius: 10px;
        cursor: pointer;
        border: none;
        outline: none;
        text-align: left;
    }

    .subChatTitle:hover, .subChatTitle:focus {
        background-color: #262336;
    }

    .iconChat {
        position: absolute;
        left: 8px;
        top: 12px;
    }

    .iconChat img {
        width: 23px;
    }
    
    .title {
        display: inline-block;
        max-width: 200px;
        height: 100%;
        margin-left: 42px;
        font-family: 'Nunito', sans-serif;
        font-size: 20px;
        text-align: left;
        color: #b4b2bb;
        padding-top: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .deleteArea {
        float: right;
        height: 100%;
    }
</style>