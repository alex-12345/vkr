<template>
    <md-empty-state
        md-rounded
        :md-label="report.label"
        :md-description="report.description">
    </md-empty-state>
</template>

<script>
    export default {
        props: ['report'],
        name: 'EmptyStateRounded'
    }
</script>

<style scoped>
    .md-empty-state {
        margin: 16px auto;
    }
</style>