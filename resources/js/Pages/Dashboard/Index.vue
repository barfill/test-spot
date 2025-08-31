<template>
    <Breadcrumbs
        :translations="translations"
        :locale="locale"
    />
    <div v-for="dashboard in dashboards" :key="dashboard.id">
        <div>
            <Link :href="route('dashboard.show', { locale, dashboard: dashboard.id })">
                <DashboardCard :dashboard="dashboard"/>
            </Link>
        </div>
        <div>
            <Link :href="route('dashboard.edit', { locale, dashboard: dashboard.id })">{{ translations.edit }}</Link>
            <Link :href="route('dashboard.destroy', { dashboard: dashboard.id, locale })" method="delete" as="button">{{ translations.delete }}</Link>
        </div>
    </div>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';
    import DashboardCard from '@/Components/UI/DashboardCard.vue';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';

    defineProps({
        dashboards: Array,
        locale: String,
        translations: Object
    });
</script>
