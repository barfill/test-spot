<template>
    <div class="flex justify-between items-center mb-6">
        <Breadcrumbs
            :breadcrumbs="[
                { label: dashboard.name, link: '#' }
            ]"
            :translations="translations"
            :locale="locale"
        />
        <div class="flex flex-row gap-2">
            <Link
                v-if="isStudent === false && isOwner"
                @click="activeView ='created'"
                class="btn-primary cursor-pointer"
                :title="translations.assign_users"
                :href="route('dashboard.users.index', { locale, dashboard: dashboard.id})"
            >
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                </span>
            </Link>
        </div>
    </div>

    <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <Card v-for="assignment in assignments" :key="assignment.id" class="flex flex-col justify-between p-4">
            <h2>{{ assignment.name }}</h2>
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
        dashboard: Object,
        assignments: Array,
        locale: String,
        translations: Object
    });

    const user = usePage().props.user;
    const currentDashboard = usePage().props.dashboard;

    const createAction = inject('createAction');

    const activeView = inject('indexActiveView');
    const isStudent = inject('isStudent');
    const isOwner = currentDashboard.owner.id === user.id;

    if ( isOwner ) {
        createAction.value = 'createAssignment';
    } else {
        createAction.value = 'null';
    }
</script>
