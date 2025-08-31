<template>
    <Breadcrumbs
        :translations="translations"
        :locale="locale"
    />
    <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <Card v-for="dashboard in dashboards" :key="dashboard.id" class="flex flex-col justify-between p-4">
            <div>
                <Link :href="route('dashboard.show', { locale, dashboard: dashboard.id })" class="block card-hover">
                    <DashboardCard :dashboard="dashboard" :translations="translations"/>
                </Link>
            </div>
            <div class="border-t-3 border-zinc-300 flex justify-between pt-3">
                <Link :href="route('dashboard.edit', { locale, dashboard: dashboard.id })" class="btn-secondary">{{ translations.edit }}</Link>
                <Link :href="route('dashboard.destroy', { dashboard: dashboard.id, locale })" method="delete" as="button" class="btn-danger">{{ translations.delete }}</Link>
            </div>
        </Card>
    </div>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';
    import DashboardCard from '@/Components/UI/DashboardCard.vue';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import Card from '@/Components/UI/Card.vue';

    defineProps({
        dashboards: Array,
        locale: String,
        translations: Object
    });
</script>
