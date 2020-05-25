<template>
    <button 
        class="addButton" 
        v-on:click="addChat" 
    >
        <img src="@/images/plus.png" alt="add button">
    </button>
</template>

<script>
    import {mapActions} from 'vuex'

    export default {
        props: ['chatId', 'type'],
        methods: {
            ...mapActions(['changeShowChatInput', 'addSubchat', 'changeShowSubChatInput']),
            addChat() {
                if (this.type === 'subChat') {
                    this.changeShowSubChatInput(this.chatId - 1)
                }
                else if (this.type === 'chat') {
                    this.changeShowChatInput()
                }
            }
        }
    }
</script>

<style scoped>
    .addButton {
        background-color: inherit;
        padding-top: 9px;
        margin-right: 8px;
        cursor: pointer;
        box-sizing: padding-box;
        border: none;
        outline: none;
    }

    .addButton img{
        width: 25px;
    }
    
</style>