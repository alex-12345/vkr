<template>
    <div class="outputMessageArea">
        <EmptyState v-show="messageArr === undefined" v-bind:report="{label: 'Чат не выбран', description: 'Выберете его в левом окне.'}"/>
        <EmptyState v-show="messageArr != undefined && messageArr.messageArray.length === 0" v-bind:report="{label: 'Сообщений нет', description: 'Напишите его первым'}"/>
        <div v-if="messageArr != undefined">
            <Message 
                v-for="message of messageArr.messageArray" :key="message.id"
                v-bind:message="message"
            />
        </div>
    </div>
</template>

<script>
    import Message from '@/views/Authenticated/Chat/Message'
    import EmptyState from '@/views/Authenticated/Chat/EmptyState'

    export default {
        props: ['messageArr'],
        components: {
            Message,
            EmptyState
        },
        created() {
            console.log('Длина:', )
        }
    }
</script>

<style scoped>
    *{
        margin: 0px;
        padding: 0px;
    }

    .outputMessageArea {
        width: 100%;
        height: 100%;
        overflow: auto;
    }

    .outputMessageArea::-webkit-scrollbar {
        width: 2%;
        padding: 0px;
        margin: 0px;
    }

    .outputMessageArea::-webkit-scrollbar-thumb {
        background: #f5f5f6;
        border-radius: 10px;
    }
</style>