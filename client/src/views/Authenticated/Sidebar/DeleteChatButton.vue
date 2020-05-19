<template>
    <button class="deleteButton" v-on:click="deleteChat"></button>
</template>

<script>
    import {mapActions} from 'vuex'

    export default {
        props: ['chatId', 'type', 'subChatId'],
        methods: {
            ...mapActions(['delChat', 'delSubchats']),
            deleteChat() {
                if (this.type === 'subChat') {
                    this.delSubchats({idChat: this.chatId - 1, idSubChat: this.subChatId - 1})
                }
                else if (this.type === 'chat') {
                    this.delChat(this.chatId - 1)
                }
            }
        }
    }
</script>

<style scoped>
    .deleteButton {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: inherit;
        background-image: url(https://img.icons8.com/ios-glyphs/30/000000/multiply.png);
        background-size: 20px 20px;
        display: block;
        float: right;
        cursor: pointer;
        box-sizing: padding-box;
        border: none;
        outline: none;
    }
</style>