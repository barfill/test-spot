<template>
    <h1>Dashboard</h1>
    <p>Welcome to your dashboard!</p>
    <Link :href="route('dashboard.index')">Go to Dashboard</Link>

    <div v-for="dashboard in dashboards" :key="dashboard.id">
        <div>
            <Link :href="route('dashboard.show', dashboard.id)">
                <DashboardCard :dashboard="dashboard"/>
            </Link>
        </div>
        <div>
            <Link :href="route('dashboard.edit', dashboard.id)">Edit</Link>
            <Link :href="route('dashboard.destroy', dashboard.id)" method="delete" as="button">Delete</Link>
        </div>
    </div>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';
    import DashboardCard from '@/Components/UI/DashboardCard.vue';

    defineProps({
        dashboards: Array
    });
</script>
