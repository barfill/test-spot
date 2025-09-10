<template>
    <div class="flex justify-between items-center mb-6">
        <Breadcrumbs
            :translations="translations"
            :locale="locale"
        />
        <div class="flex flex-row gap-2">
            <div
                @click="activeView ='assigned'"
                class="btn-primary cursor-pointer"
                :title="translations.assigned_dashboards"
            >
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                </span>
            </div>
            <div
                v-if="isStudent === false"
                @click="activeView ='created'"
                class="btn-primary cursor-pointer"
                :title="translations.created_dashboards"
            >
                <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                </span>
            </div>
        </div>
    </div>

    <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <Card v-for="dashboard in (activeView === 'assigned' ? assignedDashboards : createdDashboards)" :key="dashboard.id" class="flex flex-col justify-between p-4">
            <div>
                <Link :href="route('dashboard.show', { locale, dashboard: dashboard.id })" class="block card-hover">
                    <DashboardCard :dashboard="dashboard" :translations="translations"/>
                </Link>
            </div>
            <div v-if="dashboard.can.delete && dashboard.can.update" class="border-t-3 border-zinc-300 flex justify-between pt-3">
                <Link :href="route('dashboard.edit', { locale, dashboard: dashboard.id })" class="btn-secondary">{{ translations.edit }}</Link>
                <Link :href="route('dashboard.destroy', { dashboard: dashboard.id, locale })" method="delete" as="button" class="btn-danger">{{ translations.delete }}</Link>
            </div>
            <div v-else class="border-t-3 border-zinc-300 flex justify-between pt-3">
                <span class="text-sm text-zinc-500">{{ translations.created_by }}: {{ dashboard.owner.first_name }} {{ dashboard.owner.last_name }}</span>
            </div>
        </Card>
    </div>
</template>

<script setup>
    import { Link, usePage, useRemember } from '@inertiajs/vue3';
    import DashboardCard from '@/Components/UI/DashboardCard.vue';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import Card from '@/Components/UI/Card.vue';
    import { ref, inject } from 'vue';


    defineProps({
        assignedDashboards: Array,
        createdDashboards: Array,
        locale: String,
        translations: Object
    });

    const user = usePage().props.user;

    const createAction = inject('createAction');
    createAction.value = 'createDashboard';

    const activeView = inject('indexActiveView');
    const isStudent = inject('isStudent');



</script>
