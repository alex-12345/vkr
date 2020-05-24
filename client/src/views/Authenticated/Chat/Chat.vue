<template>
    <main>
        <OutputMessagesArea v-bind:messageArr="messageArr"/>
        <InputMessageArea />
    </main>
</template>

<script>
    import OutputMessagesArea from '@/views/Authenticated/Chat/OutputMessagesArea'
    import InputMessageArea from '@/views/Authenticated/Chat/InputMessageArea'

    export default {
        props: ['messageArr'],
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