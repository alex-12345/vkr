<template>
    <input 
        type="text"
        placeholder="Введите название"
        v-on:keyup.enter="action($event.target.value)"
    >
</template>

<script>
    import {mapActions} from 'vuex'

    export default {
        props: ['type', 'chatId'],
        methods: {
            ...mapActions(['changeShowChatInput', 'addChat', 'changeShowSubChatInput', 'addSubChat']),
            action(value) {
                if (this.type === 'chat') {
                    this.addChat(value)
                    this.changeShowChatInput()
                }
                else if (this.type === 'subChat') {
                    this.addSubChat({id: this.chatId - 1, name: value})
                    this.changeShowSubChatInput(this.chatId - 1)
                }
            }
        }
    }
</script>

<style scoped>
    input {
        width: 100%;
        height: 100%;
        padding-left: 10px;
        border-radius: 5px;        
        border: none;
        outline: none;
        background-color: #fafafa;
    }
</style>