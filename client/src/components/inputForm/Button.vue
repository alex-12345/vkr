<template>
    <div>
        <button 
            class="button" 
            type="submit" 
            :disabled="submitStatus === 'PENDING'"
            v-on:click="$emit('submit')"
        >
            Отправить
        </button>
        <p class="typo__p" v-if="submitStatus === 'OK'">Спасибо за регистрацию!</p>
        <p class="typo__p" v-if="submitStatus === 'ERROR'">Пожалуйста, заполните форму правильно.</p>
        <p class="typo__p" v-if="submitStatus === 'PENDING'">Отправка...</p>
    </div>
</template>

<script>
    export default {
        props: ['submitStatus']
    }
</script>

<style scoped>
    .button{
        display: block;
        width: 100%;
        height: 40px;
        background-color: #5e90ff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 0px 40px;
    }

    button:hover{
        cursor: pointer; 
    }

    .typo__p {
        font-size: 12px;
        text-align: left;
        margin-top: 2px;
        color: red;
        width: 100%;
    }
</style>