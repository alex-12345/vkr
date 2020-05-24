<template>
    <button 
        class="deleteButton" 
        v-on:click="deleteChat"
    >
        <img src="@/images/plus.png" alt="delete button">
    </button>
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
        background-color: inherit;
        padding-top: 9px;
        margin-right: 8px;
        cursor: pointer;
        box-sizing: padding-box;
        border: none;
        outline: none;
    }

    .deleteButton img{
        width: 25px;
        transform: rotate(45deg);
    }
</style>