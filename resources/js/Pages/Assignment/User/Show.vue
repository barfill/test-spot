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

            <div class="mt-6">
                <h5 class="text-zinc-900 dark:text-zinc-100 text-md font-bold mb-2">
                    {{ translations.file_content }}
                </h5>
                <pre v-if="fileContent" class="language-cpp"><code class="language-cpp">{{ fileContent }}</code></pre>
                <div v-else class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded text-yellow-800 dark:text-yellow-200">
                    ⚠️ {{ translations.no_file }}
                </div>
            </div>

            <div class="w-full mt-6">
                <div class="flex flex-wrap justify-center gap-4">
                    <button type="button" class="btn-form-secondary w-full md:w-[calc(50%-0.5rem)] lg:w-[calc(33.333%-0.67rem)]"
                         @click="checkPlagiarism(assignmentUser.id)"
                    >
                        <div class="flex flex-col p-1">
                            <div class="flex justify-between items-center gap-4">
                                <span>{{ translations.plagiarism_check }}</span>
                                <TestStatusIcon
                                    :check_result="assignmentUser.plagiarism_check_result"
                                    :label="translations.plagiarism_check"
                                    :translations="translations"
                                />
                            </div>
                            <!-- <span class="w-full">loading</span> -->
                        </div>
                    </button>
                    <button type="button" class="btn-form-secondary w-full md:w-[calc(50%-0.5rem)] lg:w-[calc(33.333%-0.67rem)]"
                        @click="compile()"
                    >
                        <div class="flex flex-col p-1">
                            <div class="flex justify-between items-center gap-4">
                                <span>{{ translations.compilation_check }}</span>
                                <TestStatusIcon
                                    :check_result="assignmentUser.compilation_check_result"
                                    :label="translations.compilation_check"
                                    :translations="translations"
                                />
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
                    <button type="button" class="btn-form-secondary w-full md:w-[calc(50%-0.5rem)] lg:w-[calc(33.333%-0.67rem)]">
                        <div class="flex flex-col p-1">
                            <div class="flex justify-between items-center gap-4">
                                <span>{{ translations.edge_cases_check }}</span>
                                <TestStatusIcon
                                      :check_result="assignmentUser.edge_cases_check_result"
                                        :label="translations.edge_cases_check"
                                        :translations="translations"
                                />
                            </div>
                            <!-- <span class="w-full">loading</span> -->
                        </div>
                    </button>
                </div>
            </div>
        </Card>

        <Card class="bg-white dark:bg-zinc-700 rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Errors</h3>
            <Card class="bg-white dark:bg-zinc-700 rounded-lg shadow p-6 mb-6">
                <h5 class="text-md text-zinc-500 dark:text-zinc-300 mb-2">{{  translations.plagiarism_check_error }}</h5>
                <div v-if="assignmentUser.plagiarism_check_result">
                    <pre v-if="fileContent" class="language-cpp"><code class="language-cpp">{{ assignmentUser.plagiarism_check_result }}</code></pre>
                </div>
                <div v-else>
                    <EmptyCard :translations="translations" :locale="locale" :name="'errors'"/>
                </div>
            </Card>
            <Card class="bg-white dark:bg-zinc-700 rounded-lg shadow p-6 mb-6">
                <h5 class="text-md text-zinc-500 dark:text-zinc-300 mb-2">{{  translations.compilation_check_error }}</h5>
                <div v-if="assignmentUser.compilation_check_result?.success === false">
                    <pre class="language-json"><code class="language-json">{{ JSON.stringify(assignmentUser.compilation_check_result, null, 2) }}</code></pre>
                </div>
                <div v-else>
                    <EmptyCard :translations="translations" :locale="locale" :name="'errors'"/>
                </div>
            </Card>
            <Card class="bg-white dark:bg-zinc-700 rounded-lg shadow p-6 mb-6">
                <h5 class="text-md text-gray-500 dark:text-zinc-300 mb-2">{{  translations.edge_cases_check_error }}</h5>
                <div v-if="assignmentUser.edge_cases_check_result">
                    <pre v-if="fileContent" class="language-cpp"><code class="language-cpp">{{ assignmentUser.edge_cases_check_result }}</code></pre>
                </div>
                <div v-else>
                    <EmptyCard :translations="translations" :locale="locale" :name="'errors'"/>
                </div>
            </Card>
        </Card>


        <Card class="bg-white dark:bg-zinc-700 rounded-lg shadow p-6 mb-6">
            <form @submit.prevent="submit" >
                <h2 class="text-xl font-semibold mb-6">{{ translations.assignment_assessment }}</h2>

                <div class="mb-6">
                    <label for="grade" class="label label-enabled">{{ translations.grade }}</label>
                    <select id="grade" v-model="form.grade" class="select">
                        <option value="" disabled hidden selected>{{ translations.select_grade }}</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
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
                    <button type="submit" :disabled="form.processing || !form.grade" class="btn-form-primary flex-1" :class="{ 'opacity-50 cursor-not-allowed': form.processing || !form.grade }">
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
    import { useForm, router, Link } from '@inertiajs/vue3';
    import { defineProps, inject, onMounted, ref } from 'vue';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import Card from '@/Components/UI/Card.vue';
    import TestStatusIcon from '@/Components/UI/TestStatusIcon.vue';
    import EmptyCard from '@/Components/UI/EmptyCard.vue';
    import axios from 'axios';

    import Prism from 'prismjs';
    import 'prismjs/themes/prism-tomorrow.css';
    import 'prismjs/components/prism-clike';
    import 'prismjs/components/prism-c';
    import 'prismjs/components/prism-cpp';
    import 'prismjs/components/prism-json';

    const props = defineProps({
        locale: String,
        dashboard: Object,
        assignment: Object,
        assignmentUser: Object,
        fileContent: String,
        translations: Object,
        errors: Object,
        flash: Object
    });

    const form = useForm({
        grade: '',
        review_comment: ''
    });

    const createAction = inject('createAction', null);
    if (createAction) {
        createAction.value = 'null';
    }

    onMounted(() => {
        Prism.highlightAll();
    });

    const submit = () => {
        form.put(route('dashboard.assignment.submissions.update', {
            locale: props.locale,
            dashboard: props.dashboard.id,
            assignment: props.assignment.id,
            assignmentUser: props.assignmentUser.id
        }), {
            onSuccess: () => {
                form.reset();
            }
        });
    };

    const compilationStatus = ref(null);

    const compile = async () => {
        compilationStatus.value = 'loading';

        try {
            const response = await axios.post(route('dashboard.assignment.submission.compile', {
                locale: props.locale,
                dashboard: props.dashboard.id,
                assignment: props.assignment.id,
                assignmentUser: props.assignmentUser.id
            }));

            if (response.data.results.successful) {
                compilationStatus.value = 'success';
            } else {
                compilationStatus.value = 'compilation_error';
            }

            router.reload({
                only: ['assignmentUser'],
                preserveScroll: true,
                onSuccess: () => {
                    setTimeout(() => {
                        Prism.highlightAll();
                    }, 100);
                }
            });
        } catch (error) {
            compilationStatus.value = 'network_error';
            console.error('Network error:', error);
        }
    }
</script>

<style scoped>
pre[class*="language-"] {
    margin: 0;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
}

code[class*="language-"] {
    font-size: 0.875rem;
    line-height: 1.6;
    font-family: 'Fira Code', 'Consolas', 'Monaco', monospace;
}
</style>
