<template>
    <div class="flex justify-between items-center p-2 mb-8">
        <Breadcrumbs
                :breadcrumbs="[
                    { label: dashboard.name, link: route('dashboard.show', { locale, dashboard }) },
                    { label: assignment.name, link: '#' }
                ]"
                :translations="translations"
                :locale="locale"
        />
    </div>

    <div class="grid sm:grid-cols-2 gap-8">
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
    import { defineProps, inject } from 'vue';

    defineProps({
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
</script>
