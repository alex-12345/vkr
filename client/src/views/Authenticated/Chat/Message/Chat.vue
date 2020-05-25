<template>
    <main>
        <OutputMessagesArea v-bind:messageArr="selectedChat"/>
        <InputMessageArea />
    </main>
</template>

<script>
    import OutputMessagesArea from '@/views/Authenticated/Chat/Message/OutputMessagesArea'
    import InputMessageArea from '@/views/Authenticated/Chat/Message/InputMessageArea'
    import {mapGetters} from 'vuex'

    export default {
        props: ['messageArr'],
        computed: mapGetters([
            "selectedChat",
        ]),
        components: {
            InputMessageArea,
            OutputMessagesArea
        },        
        created() {
            console.log('messageArr: ', this.messageArr)
        }
    }
</script>

<style scoped>
    main {
        display: flex;
        flex-direction: column; 
        margin-left: 360px;
        height: 100%;
        background-color: white;
    }
</style>