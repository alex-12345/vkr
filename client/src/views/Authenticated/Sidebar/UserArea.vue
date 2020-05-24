<template>
    <div class="userArea">
        <div class="avatar">
            <md-avatar>
                <img src="https://placeimg.com/40/40/people/5" alt="People">
            </md-avatar>
        </div>
        <div class="settings">
            <button
                class="settingsButton"
                v-on:click="settings()"
            >
                <img src="@/images/gear.png" alt="gear">
            </button>
        </div>
        <div class="user">
            <p class="fullName">{{secondNameUser}} {{nameUser}}</p>
            <p class="tag">#что_то_будет</p>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'

    export default {
        computed: mapGetters([
            "showChats",
            "secondNameUser",
            "nameUser"
        ]),
        methods: {
            ...mapActions([
                'changeShowChats',
            ]),
            settings() {
                if (this.showChats) {
                    this.changeShowChats()
                }
                this.$router.push("/chat/settings");
            }
        },
    }
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@600&family=Roboto&display=swap');

    .userArea {
        height: 80px;
        width: 100%;
        background: #1e142c;
        position: relative;
        display: inline-block;
    }

    .avatar {
        position: absolute;
        left: 30px;
        top: 15px;
    }

    .md-avatar {
        width: 50px;
        height: 50px;
    }
    
    .user {
        width: 180px;
        height: 100%;
        font-family: 'Roboto', sans-serif;
        font-size: 17px;
        color: #e2dde3;
        margin-left: 95px;
        padding-top: 24px;
    }

    .tag {
        color: #7f7982;
        font-family: 'Nunito', sans-serif;
        font-size: 16px;
    }

    .settings {
        float: right;
        width: 85px;
        height: 100%;
        background: inherit;
    }

    .settingsButton {
        background: inherit;
        width: 30px;
        height: 30px;
        margin: 25px 0px 0px 30px;
        box-sizing: content-box;
        border: none;
        outline: none;
        cursor: pointer;
    }

    .settings img {
        width: 100%;
        height: 100%;
    }
</style>