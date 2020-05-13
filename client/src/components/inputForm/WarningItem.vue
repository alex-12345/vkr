<template>
    <div class="error" v-if="!warningItem.error">{{warningItem.title}}</div>
</template>

<script>
    export default {
        props: ['warningItem']
    }
</script>

<style coped>
    .form-item div.error {
        display: none;
    }

    div.errorInput div.error {
        display: block;
        font-size: 12px;
        text-align: left;
        color: red;
        margin-top: 2px;
    }
</style>