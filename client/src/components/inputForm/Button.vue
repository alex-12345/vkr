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
        <p class="typo__p" v-if="submitStatus === 'AUTH_ERROR'">Не верный email или пароль.</p>
        <p class="typo__p" v-if="submitStatus === 'EMAIL_NOT_CONFIRM'">Email не подтверждён.</p>
        <p class="typo__p" v-if="submitStatus === 'USER_IS_BLOCKED'">Пользователь заблокирован.</p>
        <p class="typo__p" v-if="submitStatus === 'WORKSPACE_NOT_FOUND'">Рабочее пространство не найдено.</p>
        <div class="lds-ellipsis" v-if="submitStatus === 'PENDING'">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
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
        background-color: #2c2843;
        color: #ebe8f0;
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

    .lds-ellipsis {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }
    .lds-ellipsis div {
        position: absolute;
        top: 23px;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: #cef;
        animation-timing-function: cubic-bezier(0, 1, 1, 0);
    }
    .lds-ellipsis div:nth-child(1) {
        left: 8px;
        animation: lds-ellipsis1 0.6s infinite;
    }
    .lds-ellipsis div:nth-child(2) {
        left: 8px;
        animation: lds-ellipsis2 0.6s infinite;
    }
    .lds-ellipsis div:nth-child(3) {
        left: 32px;
        animation: lds-ellipsis2 0.6s infinite;
    }
    .lds-ellipsis div:nth-child(4) {
        left: 56px;
        animation: lds-ellipsis3 0.6s infinite;
    }
    @keyframes lds-ellipsis1 {
        0% {
            transform: scale(0);
        }
        100% {
            transform: scale(1);
        }
    }
    @keyframes lds-ellipsis3 {
        0% {
            transform: scale(1);
        }
        100% {
            transform: scale(0);
        }
    }
    @keyframes lds-ellipsis2 {
        0% {
            transform: translate(0, 0);
        }
        100% {
            transform: translate(24px, 0);
        }
    }
</style>