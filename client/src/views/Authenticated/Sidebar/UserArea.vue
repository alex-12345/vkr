<template>
    <div class="userArea">
        <div class="user">
            {{secondNameUser}} {{nameUser}}
        </div>
        <button 
            class="settings" 
            v-on:mouseenter="show(true)"
            v-on:mouseleave="show(false)"
        >
            <img src="@/images/gear.png" alt="gear">
        </button>
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
    .userArea {
        height: 50px;
        width: 100%;
        background: #007c88;
        position: relative;
        display: inline-block;
    }
    
    .user {
        float: left;
        color: white;
        height: 35px;
        box-sizing: content-box;
        font-size: 20px;
        padding: 15px 0px 0px 10px;
    }

    .settings {
        background: #007c88;
        width: 40px;
        height: 40px;
        padding: 5px;
        box-sizing: content-box;
        border: none;
        outline: none;
        cursor: pointer;
        display: block;
        margin-left: auto;
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