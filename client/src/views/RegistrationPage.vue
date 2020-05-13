<template>
    <div class="registrationPage">
        <div class="choice">
            <div class="registrationPlatform">
                <div class="text">
                    <h2>Попробуйте Sapechat со своей командой</h2>
                    <p>Зарегистрируйте новое рабочее пространство для вас и вашей команды.</p>
                </div>                
                <button type="button" v-on:click="send('/registrationPlatform')">Зарегистрировать рабочее пространство</button>
            </div>
            <div class="registrationUser">
                <div class="text">
                    <h2>Ваша команда уже использует Sapechat?</h2>
                    <p>Зарегистрируйтесь сами.</p>
                </div>                
                <button type="button" v-on:click="send('/registrationUser')">Зарегистрироваться</button>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex'

    export default {
        methods: {
            ...mapActions(['changeHeaderItems']),
            send: function (path) {
                this.$router.push(path)
            }
        },
        mounted: function () {
            this.changeHeaderItems(4)
        }
    }
</script>

<style scoped>
    *{ 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
    }

    .choice {
        height: 300px;
        width: 700px;
        border: 1px solid #9e9e9e;
        margin: 150px auto;
        border-radius: 5px;
    }

    .registrationPlatform, .registrationUser{
        width: 50%;
        height: 100%;
        text-align: left;
        padding: 30px 30px 70px 30px;
    }

    .registrationPlatform{
        float: left;
        border-right: 1px solid #9e9e9e;
        background-color: #5e90ff;
        color: white;
    }

    .registrationUser{
        float: right;
    }

    .text{
        height: 100%;
    }

    p{
        font-size: 18px;
        margin-top: 25px;
    }

    button{
        width: 100%;
        height: 40px;
        border: none;
        border-radius: 5px;
    }

    button:hover{
        cursor: pointer; 
    }

    .registrationPlatform button {
        background-color: white;
    }

    .registrationUser button {
        background-color: #5e90ff;
        color: white;
    }
</style>