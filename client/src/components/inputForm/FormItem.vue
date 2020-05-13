<template>
    <div class="form-item" :class="{ 'errorInput': formItem.error }">
        <input 
            :type="formItem.type"
            :placeholder="formItem.title"
            class="form__input"
            :class="{ 'error': formItem.error }"
            v-on:input="$emit('input', {id: formItem.id - 1, value: $event.target.value, title: formItem.title})"
        >
        <WarningItem
            v-for="warningItem of formItem.warningItemArr" :key="warningItem.id"
            v-bind:warningItem="warningItem"
        />
    </div>
</template>

<script>
    import WarningItem from '@/components/inputForm/WarningItem'

    export default {
        props: ['formItem'],
        components: {
            WarningItem
        }      
    }
</script>

<style scoped>
    *{ 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
    }

    .form-item{
        display: block;
        width: 100%;
        height: 40px;
        margin-bottom: 30px;
    }

    .form__input {
        width: 100%;
        height: 100%;
        border: 1px solid #dbdbdb;
        border-radius: 5px;
        outline: none;
        background-color: #fafafa;
        padding-left: 10px;

    }

    .form__input:focus {
        border-color: blue;
    }

    input.error {
        border-color: red;
    }
</style>