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

    <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-8">
        <div v-for="assignment in filteredAssignments" :key="assignment.id" >
            <div class="py-2 flex justify-between">
                    <div>
                    <span class="text-md font-bold">{{ assignment.end_date_formatted }}</span>
                    <span v-if="assignment.status === 'open'" class="mx-2"> | </span>
                    <span class="font-bold text-red-400" v-if="assignment.status === 'open' && !userAssignments[assignment.id] && !isOwner">{{ translations.not_displayed.toUpperCase() + ' - ' + translations.left.toUpperCase() + ' ' + assignment.ends_in }}</span>
                    <span v-if="assignment.status === 'open' && (userAssignments[assignment.id]?.status === 'in_progress' || isOwner)">{{ translations.left[0].toUpperCase() + translations.left.slice(1) }} : {{ assignment.ends_in }}</span>
                    <span v-if="assignment.status === 'open' && userAssignments[assignment.id]?.status === 'pending'">{{ translations.status_pending }}</span>
                    <span v-if="assignment.status === 'open' && userAssignments[assignment.id]?.status === 'graded_passed'">{{ translations.status_completed }}</span>
                    <span v-if="assignment.status === 'open' && userAssignments[assignment.id]?.status === 'graded_failed'">{{ translations.status_failed }}</span>
                </div>
                <Link
                    v-if="assignment.status === 'closed' && assignment.can.delete && assignment.can.update"
                    class="cursor-pointer hover:underline text-orange-500 dark:text-orange-400"
                    :title="translations.restore"
                    :href="route('dashboard.assignments.restore', { locale, dashboard: dashboard.id, assignment: assignment.id })"
                    method="patch"
                    as="button"
                >
                    {{ translations.restore }}
                </Link>
            </div>
            <Card class="flex flex-col justify-between p-4"
                :class="{'card-disabled': assignment.status === 'closed' }"
            >
                <div>
                    <Link :href="route('dashboard.assignments.show', { locale, dashboard: dashboard.id, assignment: assignment.id })" class="block card-hover">
                        <AssignmentCard :dashboard="dashboard" :assignment="assignment" :userAssignment="userAssignments[assignment.id]" :translations="translations"/>
                    </Link>
                </div>
                <div v-if="assignment.can.delete && assignment.can.update" class="border-t-3 border-zinc-300 flex justify-between pt-3">
                    <Link :href="route('dashboard.assignments.edit', { locale, dashboard: dashboard.id, assignment: assignment.id })" class="btn-secondary">{{ translations.edit }}</Link>
                    <Link :href="route('dashboard.assignments.destroy', { locale, dashboard: dashboard.id, assignment: assignment.id })" method="delete" as="button" class="btn-danger">{{ translations.delete }}</Link>
                </div>
            </Card>
        </div>
    </div>
</template>

<script setup>
    import { Link, usePage, useRemember } from '@inertiajs/vue3';
    import DashboardCard from '@/Components/UI/DashboardCard.vue';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import Card from '@/Components/UI/Card.vue';
    import { ref, computed, inject, } from 'vue';

    import AssignmentCard from '../../Components/UI/AssignmentCard.vue';



    const props = defineProps({
        dashboard: Object,
        assignments: Array,
        userAssignments: Object,
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

    const filteredAssignments = computed(() => {
        if (isOwner) {
            return props.assignments;
        }

        return props.assignments.filter(assignment => {
            const isInTimeRange = assignment.status === 'open' &&
                new Date(assignment.start_time) <= new Date() &&
                new Date(assignment.end_time) >= new Date();


            const isPendingOrCompleted = props.userAssignments[assignment.id] &&
                (props.userAssignments[assignment.id].status === 'pending' ||
                 props.userAssignments[assignment.id].status === 'graded_passed' ||
                 props.userAssignments[assignment.id].status === 'graded_failed');

            return isInTimeRange || isPendingOrCompleted;
        });
    })
</script>
