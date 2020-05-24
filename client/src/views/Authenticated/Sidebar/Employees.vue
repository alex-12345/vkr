<template>
    <button 
        class="employees"
        v-on:click="processAction"
    >
        <div class="icon">
            <img src="@/images/employees.png" />
        </div>
        <div class="text">
            сотрудники
        </div>
    </button>
</template>

<script>

    export default {
        methods: {
            processAction() {
                console.log('Сотрудники')
            },
        },
    }
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

    .employees {
        display: block;
        position: relative;
        width: 320px;
        min-height: 45px;
        margin: 5px auto;
        color: #b4b2bb;
        border-radius: 10px;
        background-color: inherit;
        cursor: pointer;
        box-sizing: border-box;
        border: none;
        outline: none;
    }

    .employees:hover {
        background-color: #262336;
    }

    .employees:focus {
        background-color: #262336;
    }

    .icon {
        position: absolute;
        left: 8px;
        top: 8px;
    }

    .icon img {
        width: 30px;
    }

    .text {
        min-height: inherit;
        margin-left: 42px;
        font-family: 'Roboto', sans-serif;
        font-size: 26px;
        text-align: left;
        font-weight: lighter;
        padding-top: 5px;
    }

</style>