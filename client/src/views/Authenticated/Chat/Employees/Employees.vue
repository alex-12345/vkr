<template>
    <div class="employess">
        <EmployeeCard
            v-for="employee of employees" :key="employee.id"
            v-bind:employee="employee"
        />
    </div>
</template>

<script>
    import EmployeeCard from '@/views/Authenticated/Chat/Employees/EmployeeCard'
    import {mapGetters} from 'vuex'

    export default {
        computed: mapGetters([
            "employees",
        ]),
        components: {
            EmployeeCard,
        },
    }
</script>

<style scoped>
    .employess {
        margin-left: 360px;
        height: 100%;
        overflow: auto;
        background-color: white;
    }
</style>