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
                v-on:mouseenter="show(true)"
                v-on:mouseleave="show(false)"
            >
                <img src="@/images/gear.png" alt="gear">
            </button>
        </div>
        <div class="user">
            <p class="fullName">{{secondNameUser}} {{nameUser}}</p>
            <p class="tag">#что_то_будет</p>
        </div>
        
        <div class="dropdown" 
            v-if="showMenuSidebar"
            v-on:mouseenter="show(true)"
            v-on:mouseleave="show(false)"
        >
            <MenuItem
                v-for="dropDownItem of dropDownArr" :key="dropDownItem.id"
                v-bind:dropDownItem="dropDownItem"
                v-on:action="processAction"
            />
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'
    import MenuItem from '@/views/Authenticated/Sidebar/MenuItem'

    export default {
        computed: mapGetters([
            "showMenuSidebar",
            "dropDownArr",
            "nameUser",
            "secondNameUser"
        ]),
        methods: {
            ...mapActions([
                'authLogout',
                'changeShow'
            ]),
            processAction(answer) {
                this.show(false)
                if (this.dropDownArr[answer.id].function === 'logout') {
                    this.logout()
                }
            },
            logout() {
                this.authLogout()
                .then(() => {
                this.$router.push('/authorization')
                })
            },
            show(value) {
                this.changeShow(value)
            }
        },
        components: {
            MenuItem
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

    .dropdown {
        position: absolute;
        bottom: 100%;
        left: 10%;
        width: 90%;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }
</style>