<template>
    <div class="flex flex-col md:flex-row gap-6 md:gap-0 justify-between items-center p-2 mb-8">
        <Breadcrumbs
                :breadcrumbs="[
                    { label: dashboard.name, link: route('dashboard.show', { locale, dashboard }) },
                    { label: assignment.name, link: '#' }
                ]"
                :translations="translations"
                :locale="locale"
        />

        <div class="flex flex-row gap-2">
            <button type="button" class="btn-teacher-secondary border-1 w-full"
                @click="plagiaristCheckAll()"
                :class="{
                    'border-orange-500 dark:border-orange-400 animate-pulse': plagiaristCheckStatus === 'loading',
                    'border-zinc-300 dark:border-zinc-400': plagiaristCheckStatus !== 'loading',
                }"
            >
                <div class="flex flex-col p-1">
                    <div class="flex justify-between items-center gap-4">
                        <span>{{ translations.plagiarism_check }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                        </svg>
                    </div>
                    <div v-if="plagiaristCheckStatus" class="w-full mt-2 h-1 rounded-full transition-all duration-300"
                        :class="{
                            'bg-zinc-400 dark:bg-zinc-100 animate-pulse': plagiaristCheckStatus === 'loading',
                            'bg-green-500 dark:bg-green-400': plagiaristCheckStatus === 'success',
                            'bg-red-500 dark:bg-red-400': plagiaristCheckStatus === 'error',
                        }">
                    </div>
                    <div v-else class="w-full mt-2 h-1 rounded-full transition-all duration-300"
                        :class="{
                            'bg-zinc-300': plagiaristCheckStatus === null
                        }">
                    </div>
                </div>
            </button>
            <button type="button" class="btn-teacher-secondary border-1 w-full"
                @click="compileAll()"
                :class="{
                    'border-orange-500 dark:border-orange-400 animate-pulse': compilationStatus === 'loading',
                    'border-zinc-300 dark:border-zinc-400': compilationStatus !== 'loading',
                }"
            >
                <div class="flex flex-col p-1">
                    <div class="flex justify-between items-center gap-4">
                        <span>{{ translations.compilation_check }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75a4.5 4.5 0 0 1-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 1 1-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 0 1 6.336-4.486l-3.276 3.276a3.004 3.004 0 0 0 2.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.867 19.125h.008v.008h-.008v-.008Z" />
                        </svg>
                    </div>
                    <div v-if="compilationStatus" class="w-full mt-2 h-1 rounded-full transition-all duration-300"
                        :class="{
                            'bg-zinc-400 dark:bg-zinc-100 animate-pulse': compilationStatus === 'loading',
                            'bg-green-500 dark:bg-green-400': compilationStatus === 'success',
                            'bg-red-500 dark:bg-red-400': compilationStatus === 'compilation_error',
                            'bg-orange-500 dark:bg-orange-400': compilationStatus === 'network_error'
                        }">
                    </div>
                    <div v-else class="w-full mt-2 h-1 rounded-full transition-all duration-300"
                        :class="{
                            'bg-zinc-300': compilationStatus === null
                        }">
                    </div>
                </div>
            </button>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <Card v-if="userAssignments" v-for="userAssignment in userAssignments" :key="userAssignment.id" class="flex flex-col justify-between p-4">
            <div>
                <Link :href="route('dashboard.assignment.submissions.show', { locale, dashboard: dashboard.id, assignment: assignment.id, assignmentUser: userAssignment.id })" class="block card-hover">
                    <Card>
                        <div class="flex flex-row justify-between gap-2 p-2">
                            <div class="flex flex-col justify-center items-center p-2">
                                <h4 class="text-lg font-semibold">{{ userAssignment.user.first_name }} {{ userAssignment.user.last_name }}</h4>
                                <p class="text-sm text-gray-500">{{ userAssignment.user.email }}</p>
                            </div>
                            <div class="flex flex-col justify-between items-end p-2">
                                <p class="text-sm text-gray-500">{{ translations.submitted }} : {{ userAssignment.submitted_at_formatted }}</p>
                                <span class="flex items-center gap-4 mt-2">
                                    <TestStatusIcon
                                        :check_result="userAssignment.plagiarism_check_result"
                                        :label="translations.plagiarism_check"
                                        :translations="translations"
                                    />
                                    <TestStatusIcon
                                        :check_result="userAssignment.compilation_check_result"
                                        :label="translations.compilation_check"
                                        :translations="translations"
                                    />
                                    <TestStatusIcon
                                        :check_result="userAssignment.edge_cases_check_result"
                                        :label="translations.edge_cases_check"
                                        :translations="translations"
                                    />
                                </span>
                            </div>
                        </div>
                    </Card>
                </Link>
            </div>
        </Card>
        <Card v-else>
            <p>No submissions yet.</p>
        </Card>
    </div>
</template>

<script setup>
    import { Link, router, useForm } from '@inertiajs/vue3';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import Card from '@/Components/UI/Card.vue';
    import TestStatusIcon from '@/Components/UI/TestStatusIcon.vue';
    import { defineProps, inject, onMounted, ref } from 'vue';
    import axios from 'axios';

    const props = defineProps({
        locale: String,
        dashboard: Object,
        assignment: Object,
        translations: Object,
        userAssignments: Object,
        errors: Object,
        flash: Object
    });

    const createAction = inject('createAction', null);
    if (createAction) {
        createAction.value = 'null';
    }

    const compilationStatus = ref(null);
    const compileAll = async () => {
        compilationStatus.value = 'loading';

        try {
            const response = await axios.post(route('dashboard.assignment.compile', {
                locale: props.locale,
                dashboard: props.dashboard.id,
                assignment: props.assignment.id
            }));

            if (response.data.results.successful) {
                compilationStatus.value = 'success';
            } else {
                compilationStatus.value = 'compilation_error';
            }

            router.reload();
        } catch (error) {
            console.error('Compilation error:', error);
        }
    }

    const plagiaristCheckStatus = ref(null);
    const plagiaristCheckAll = async() => {
        plagiaristCheckStatus.value = 'loading';

        try {
            const response = await axios.post(route('dashboard.assignment.check-plagiarism', {
                locale: props.locale,
                dashboard: props.dashboard.id,
                assignment: props.assignment.id
            }));

            if (response.data.results.successful) {
                plagiaristCheckStatus.value = 'success';
            } else {
                plagiaristCheckStatus.value = 'error';
            }

            router.reload();
        } catch (error) {
            console.error('Plagiarism check error:', error);
        }

    }


</script>
