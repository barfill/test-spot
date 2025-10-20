<template>
    <div class="flex justify-between items-center p-2 mb-8">
        <Breadcrumbs
            :breadcrumbs="[
                { label: dashboard.name, link: route('dashboard.show', { locale, dashboard: dashboard.id }) },
                { label: assignment.name, link: route('dashboard.assignments.show', { locale, dashboard: dashboard.id, assignment: assignment.id }) },
                { label: assignmentUser.user.first_name + ' ' + assignmentUser.user.last_name, link: '#' }
            ]"
            :translations="translations"
            :locale="locale"
        />
    </div>

    <div class="max-w-4xl mx-auto p-6">
        <Card class="bg-white dark:bg-zinc-700 rounded-lg shadow p-6 mb-6">
            <h5 class="text-zinc-900 dark:text-zinc-100 text-xl font-bold mb-4">{{ translations.assignment_description }}</h5>
            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ assignment.description }}</p>
        </Card>

        <Card class="bg-white dark:bg-zinc-700 rounded-lg shadow p-6 mb-6">
            <h1 class="text-zinc-900 dark:text-zinc-100 text-lg font-bold mb-2">{{ assignmentUser.user.first_name }} {{ assignmentUser.user.last_name }}</h1>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
                <span class="font-medium">{{ translations.email }}: </span>
                <span>{{ assignmentUser.user.email }}</span>
            </p>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
                <span class="font-medium">{{ translations.submitted }}: </span>
                <span>{{ assignmentUser.submitted_at_formatted || translations.not_submitted }}</span>
            </p>

            <div v-if="assignmentUser.user_comment" class="mt-6">
                <h5 class="text-zinc-900 dark:text-zinc-100 text-md font-bold mb-2">{{ translations.student_comment }}</h5>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ assignmentUser.user_comment }}</p>
            </div>

            <pre class="bg-gray-100 p-4"><code>test</code></pre>

            <div class="w-full mt-6">
                <span class="w-full flex items-center justify-center gap-4 mt-2">
                     <Link class="btn-secondary">
                        <div class="flex flex-col p-1">
                            <div class="flex justify-between items-center gap-4">
                                <span>{{ translations.plagiarism_check }}</span>
                                <TestStatusIcon
                                    :check_result="assignmentUser.plagiarism_check_result"
                                    :label="translations.plagiarism_check"
                                    :translations="translations"
                                />
                            </div>
                            <span class="w-full">loading</span>
                        </div>
                    </Link>
                    <Link class="btn-secondary">
                        <div class="flex flex-col p-1">
                            <div class="flex justify-between items-center gap-4">
                                <span>{{ translations.compilation_check }}</span>
                                <TestStatusIcon
                                    :check_result="assignmentUser.compilation_check_result"
                                    :label="translations.compilation_check"
                                    :translations="translations"
                                />
                            </div>
                            <span class="w-full">loading</span>
                        </div>
                    </Link>
                      <Link class="btn-secondary">
                        <div class="flex flex-col p-1">
                            <div class="flex justify-between items-center gap-4">
                                <span>{{ translations.edge_cases_check }}</span>
                                <TestStatusIcon
                                      :check_result="assignmentUser.edge_cases_check_result"
                                        :label="translations.edge_cases_check"
                                        :translations="translations"
                                />
                            </div>
                            <span class="w-full">loading</span>
                        </div>
                    </Link>
                </span>
            </div>
        </Card>




        <Card class="bg-white dark:bg-zinc-700 rounded-lg shadow p-6 mb-6">
            <form @submit.prevent="submit" >
                <h2 class="text-xl font-semibold mb-6">{{ translations.assignment_assessment }}</h2>

                <div class="mb-6">
                    <label for="grade" class="label label-enabled">{{ translations.grade }}</label>
                    <input type="number" min="2" max="5" id="grade" name="grade" v-model="form.grade" class="input"/>
                    <div v-if="form.errors.grade" class="text-error mt-1">
                        {{ form.errors.grade }}
                    </div>
                </div>

                <div class="mb-6">
                    <label for="review_comment" class="label label-enabled">
                        {{ translations.comment }} ({{ translations.optional }})
                    </label>
                    <textarea id="review_comment" v-model="form.review_comment" class="input h-32" :placeholder="translations.user_comment_placeholder" maxlength="1000"></textarea>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        {{ form.review_comment?.length || 0 }}/1000
                    </p>
                    <div v-if="form.errors.review_comment" class="text-error mt-1">
                        {{ form.errors.review_comment }}
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" :disabled="form.processing || !form.grade" class="btn-form-primary flex-1" :class="{ 'opacity-50 cursor-not-allowed': form.processing || !form.code_file }">
                        <span v-if="form.processing">⏳ {{ translations.submitting }}...</span>
                        <span v-else>✓ {{ translations.submit }}</span>
                    </button>

                    <button type="button" @click="$inertia.visit(route('dashboard.assignments.show', { locale, dashboard: dashboard.id, assignment: assignment.id }))" class="btn-form-secondary">
                        {{ translations.cancel }}
                    </button>
                </div>
            </form>
        </Card>
    </div>

</template>

<script setup>
    import { useForm, Link,   } from '@inertiajs/vue3';
    import { defineProps, inject } from 'vue';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import Card from '@/Components/UI/Card.vue';
    import TestStatusIcon from '@/Components/UI/TestStatusIcon.vue';

    const props = defineProps({
        locale: String,
        dashboard: Object,
        assignment: Object,
        assignmentUser: Object,
        translations: Object,
        errors: Object,
        flash: Object
    });

    const form = useForm({
        grade: null,
        review_comment: ''
    });

    const createAction = inject('createAction', null);
    if (createAction) {
        createAction.value = 'null';
    }

     const submit = () => {
        form.post(route('assignment.submit', {
            locale: props.locale,
            dashboard: props.dashboard.id,
            assignment: props.assignment.id
        }), {
            onSuccess: () => {
                form.reset();
                clearFile();
            }
        });
    };
</script>
