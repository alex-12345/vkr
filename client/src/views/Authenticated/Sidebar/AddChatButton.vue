<template>
    <button 
        class="addButton" 
        v-on:click="addChat" 
    >
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
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: inherit;
        background-image: url(https://img.icons8.com/ios-glyphs/30/000000/plus-math.png);
        background-size: 20px 20px;
        display: block;
        float: right;
        cursor: pointer;
        box-sizing: padding-box;
        border: none;
        outline: none;
    }
</style>