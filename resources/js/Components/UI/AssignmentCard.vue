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
        <!-- <div v-if="assignment.status === 'in_progress'" class="col-span-12">
            <Bar :duration="assignment.duration_days" :remainingDays="assignment.ends_in" />
        </div>
        <div v-if="assignment.status === 'pending'" class="col-span-12">
            <div class="w-full flex justify-between">
                <div class="bg-yellow-500 dark:bg-yellow-500 p-2 font-medium rounded-md text-zinc-50 dark:text-zinc-100">
                    {{translations.status_pending}}
                </div>
                <div class="border border-zinc-200 dark:border-zinc-50 text-zinc-500 dark:text-zinc-50 bg-zinc-100 dark:bg-zinc-600 p-2 font-light rounded-md">
                    Przesłano 06.12.2025
                </div>
            </div>
        </div>
        <div v-if="assignment.status === 'completed'" class="col-span-12">
            <div class="w-full flex justify-between">
                <div class="bg-green-500 p-2 rounded-md text-zinc-50 dark:text-zinc-100">
                    {{ translations.status_completed }}
                </div>
                <div class="border border-zinc-200 dark:border-zinc-50 text-zinc-500 dark:text-zinc-50 bg-zinc-100 dark:bg-zinc-600 p-2 font-light rounded-md">
                    Ocena...[wartość]
                </div>
            </div>
        </div> -->
    </div>

</template>

<script setup>
    import { Link, usePage, useRemember } from '@inertiajs/vue3';
    import DashboardCard from '@/Components/UI/DashboardCard.vue';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import Bar from '@/Components/UI/Bar.vue';
    import Card from '@/Components/UI/Card.vue';
    import { ref, inject } from 'vue';

    defineProps({
        dashboard: Object,
        assignment: Object,
        locale: String,
        translations: Object
    });

</script>
