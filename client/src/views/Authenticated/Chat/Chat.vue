<template>
    <main>
        <InputTextArea />
    </main>
</template>

<script>
    import InputTextArea from '@/views/Authenticated/Chat/InputTextArea'
    export default {
        components: {
            InputTextArea
        }
    }
</script>

<style scoped>
    main {
        margin-left: 300px;
        height: 100%;
        background-color: #f5f5f6;
    }
</style>