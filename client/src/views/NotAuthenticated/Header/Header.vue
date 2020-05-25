<template>
    <md-tabs class="default" md-sync-route md-active-tab="tab-to-the-main" md-alignment="fixed">
        <md-tab id="tab-to-the-main" md-label="На главную" to="/" exact></md-tab>
        <md-tab id="tab-log-in" md-label="Авторизоваться" to="/authorization"></md-tab>
        <md-tab id="tab-to-register" md-label="Зарегистрироваться" to="/registration"></md-tab>
    </md-tabs>
</template>

<script>
    export default {
        name: 'Header'
    }
</script>