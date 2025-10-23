<template>
    <div class="grid grid-cols-12 gap-6 pb-4">
        <div class="col-span-4 flex items-center justify-center w-full h-24">
             <img v-if="dashboard.image"
                 :src="dashboard.image"
                 alt="#"
                 class="w-full h-full object-cover rounded-md"/>
            <div v-else
                 class="w-full h-full flex items-center justify-center bg-zinc-100 dark:bg-zinc-600 rounded-md text-zinc-400">
                {{ translations.no_images }}
            </div>
        </div>
        <div class="col-span-8 flex flex-col justify-center h-24 gap-3 pr-3">
            <div class="text-center flex flex-col gap-1">
                <span :title="assignment.name" class="font-bold text-zinc-700 dark:text-zinc-100 text-lg leading-tight truncate">{{ assignment.name }}</span>
                <span class="text-sm text-zinc-500">{{ dashboard.name }}</span>
            </div>
        </div>

        <template v-if="assignment.status !== 'closed' && userAssignment">
            <div v-if="userAssignment?.status === 'in_progress'" class="col-span-12">
                <Bar :duration="assignment.duration_days" :remainingDays="assignment.ends_in" />
            </div>

            <div v-if="!userAssignment" class="col-span-12">
                <div class="w-full flex justify-center">
                    <div class="bg-gray-400 dark:bg-gray-500 p-2 font-medium rounded-md text-zinc-50 dark:text-zinc-100">
                        {{ translations.not_sent }}
                    </div>
                </div>
            </div>

            <div v-if="userAssignment?.status === 'pending'" class="col-span-12 mx-2">
                <div class="w-full flex justify-between">
                    <div class="bg-yellow-500 dark:bg-yellow-500 p-2 font-medium rounded-md text-zinc-50 dark:text-zinc-100">
                        {{ translations.status_pending }}
                    </div>
                    <div class="border border-zinc-200 dark:border-zinc-50 text-zinc-500 dark:text-zinc-50 bg-zinc-100 dark:bg-zinc-600 p-2 font-light rounded-md">
                        {{ translations.sent }} {{ new Date(userAssignment.submitted_at).toLocaleDateString('pl-PL') }}
                    </div>
                </div>
            </div>

            <div v-if="userAssignment?.status === 'graded_passed' || userAssignment?.status === 'graded_failed'" class="col-span-12 mx-2">
                <div class="w-full flex justify-between">
                    <div
                        class="p-2 rounded-md text-zinc-50 dark:text-zinc-100"
                        :class="userAssignment.status === 'graded_passed' ? 'bg-green-500' : 'bg-red-500'"
                    >
                        {{ userAssignment.status === 'graded_passed' ? translations.status_completed : translations.status_failed }}
                    </div>
                    <div class="border border-zinc-200 dark:border-zinc-50 text-zinc-500 dark:text-zinc-50 bg-zinc-100 dark:bg-zinc-600 p-2 font-light rounded-md">
                        {{ translations.grade }}: {{ userAssignment.grade || translations.not_sent }}
                    </div>
                </div>
            </div>
        </template>
    </div>

</template>

<script setup>
    import { Link, usePage, useRemember } from '@inertiajs/vue3';
    import DashboardCard from '@/Components/UI/DashboardCard.vue';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import Bar from '@/Components/UI/Bar.vue';
    import Card from '@/Components/UI/Card.vue';
    import { ref, inject } from 'vue';

    const props = defineProps({
        dashboard: Object,
        assignment: Object,
        userAssignment: Object,
        locale: String,
        translations: Object
    });


</script>
