<template>
    <div class="message">
        <div class="avatar" v-if="!message.replay">
            <md-avatar>
                <img :src="message.avatar" alt="People">
            </md-avatar>
        </div>
        <div class="fullName" v-if="!message.replay">{{message.lastName}} {{message.firstName}}</div>        
        <div class="text">
            <p>{{message.body}}</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['message']
    }
</script>

<style scoped>
    .message {
        width: 100%;
        padding-left: 72px;
        padding-right: 48px;
        margin-top: 16px;
        position: relative;
    }

    .avatar {
        position: absolute;
        left: 16px;
        top: 4px;
    }
</style>