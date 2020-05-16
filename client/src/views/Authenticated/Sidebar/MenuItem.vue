<template>
    <button class="dropButton" v-on:click="$emit('action', {id: dropDownItem.id - 1})">{{dropDownItem.title}}</button>
</template>

<script>
    export default {
        props: ['dropDownItem'],
    }
</script>

<style scoped>
    .dropButton {
        width: 100%;
        background-color: #546e7a;
        color: white;
        border-radius: 5px;
        padding: 12px 16px;
        text-align: left;
        border: none;
        display: block;
        cursor: pointer;
    }

    .dropButton:hover, .dropButton:focus {
        background-color: #B00020;
    }
</style>