<template>
    <input 
        type="text"
        placeholder="Введите название"
        v-on:keyup.enter="action()"
        class="reset"
    >
</template>

<script>
    import {mapActions} from 'vuex'

    export default {
        props: ['type', 'chatId'],
        methods: {
            ...mapActions(['changeShowChatInput', 'addChat', 'changeShowSubChatInput', 'addSubChat']),
            action() {
                if (this.type === 'chat') {
                    this.addChat(event.target.value)
                    event.target.value = ''
                    this.changeShowChatInput()
                }
                else if (this.type === 'subChat') {
                    this.addSubChat({id: this.chatId - 1, name: event.target.value})
                    event.target.value = ''
                    this.changeShowSubChatInput(this.chatId - 1)
                }
            }
        }
    }
</script>

<style scoped>
    input {
        width: 320px;
        height: 40px;
        padding-left: 10px;
        margin: 0px auto;
        border-radius: 5px;        
        border: none;
        outline: none;
        background-color: #fafafa;
    }
</style>