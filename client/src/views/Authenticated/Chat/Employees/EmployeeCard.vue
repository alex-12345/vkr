<template>
    <md-card>
        <md-card-header>
            <md-avatar>
                <img src="https://vuematerial.io/assets/examples/avatar.png" alt="Avatar">
            </md-avatar>

            <div class="md-title">{{employee.surname}} {{employee.firstName}}</div>
            <div class="md-subhead">Subtitle here</div>
        </md-card-header>

        <md-card-content>
            {{employee.description}}
        </md-card-content>

        <md-card-actions>
            <md-button v-on:click="openDialog">Написать</md-button>
        </md-card-actions>
    </md-card>
</template>

<script>
    import {mapActions} from 'vuex'

    export default {
        props: ['employee'],
        methods: {
            ...mapActions([
                'changeSelectedChat',
                'changeSelectedChatId',
                'changeShowSubChats',
                'changeShowChats'
            ]),
            openDialog() {
                this.changeSelectedChatId({chatId: this.employee.dialogId, subChatId: undefined})
                this.changeSelectedChat({chatId: this.employee.dialogId, subChatId: undefined})
                this.changeShowChats()
                this.$router.push("/chat/messages");
            }
        }
    }
</script>

<style lang="scss" scoped>
    .md-card {
        width: 320px;
        margin: 40px 0px 0px 40px;
        display: inline-block;
        vertical-align: top;
    }

    .md-card-example {
        .md-subhead {
            .md-icon {
                $size: 16px;

                width: $size;
                min-width: $size;
                height: $size;
                font-size: $size !important;
            }
        span {
            vertical-align: middle;
        }
    }

    .md-button-group {
        display: flex;

        .md-button {
            min-width: 60px;
            border-radius: 2px;
        }
    }
  }
</style>