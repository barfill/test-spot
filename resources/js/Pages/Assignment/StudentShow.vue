<template>
    <Breadcrumbs
        :breadcrumbs="[
            { label: dashboard.name, link: route('dashboard.show', { locale, dashboard: dashboard.id }) },
            { label: assignment.name, link: '#' }
        ]"
        :translations="translations"
        :locale="locale"
    />
    <div class="max-w-4xl mx-auto p-6">
        <Card class="bg-white dark:bg-zinc-700 rounded-lg shadow p-6 mb-6">
            <h1 class="text-zinc-900 dark:text-zinc-100 text-3xl font-bold mb-4">{{ assignment.name }}</h1>
            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ assignment.description }}</p>
        </Card>


        <Card v-if="!assignmentUser.submitted_at && assignmentUser.status === 'in_progress'" class="bg-white dark:bg-zinc-700 rounded-lg shadow p-6 mb-6">
            <form @submit.prevent="submit" >
                <h2 class="text-xl font-semibold mb-6">{{ translations.add_file }}</h2>

                <div class="mb-6">
                    <input ref="fileInput" type="file" accept=".cpp,.cc,.cxx,.c" @change="handleFileChange" class="hidden"/>

                    <div class="flex items-center gap-3 flex-col">
                        <button
                            type="button"
                            @click="$refs.fileInput.click()"
                            class="btn-form-secondary w-full"
                        >
                            📁 {{ selectedFileName || translations.choose_file }}
                        </button>

                        <div v-if="selectedFileName" class="flex items-center gap-2">
                            <span class="text-sm text-zinc-600 dark:text-zinc-300">{{ selectedFileName }}</span>
                            <button type="button" @click="clearFile" class="text-red-500 hover:text-red-700"
                            >
                                ✕
                            </button>
                        </div>
                    </div>

                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">
                        {{ translations.accepted_formats }}: .cpp, .cc, .cxx, .c
                    </p>

                    <div v-if="form.errors.code_file" class="text-error mt-1">
                        {{ form.errors.code_file }}
                    </div>
                </div>

                <div class="mb-6">
                    <label for="user_comment" class="label label-enabled">
                        {{ translations.comment }} ({{ translations.optional }})
                    </label>
                    <textarea id="user_comment" v-model="form.user_comment" class="input h-32" :placeholder="translations.user_comment_placeholder" maxlength="1000"></textarea>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        {{ form.user_comment?.length || 0 }}/1000
                    </p>
                    <div v-if="form.errors.user_comment" class="text-error mt-1">
                        {{ form.errors.user_comment }}
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" :disabled="form.processing || !form.code_file" class="btn-form-primary flex-1" :class="{ 'opacity-50 cursor-not-allowed': form.processing || !form.code_file }">
                        <span v-if="form.processing">⏳ {{ translations.submitting }}...</span>
                        <span v-else>✓ {{ translations.submit }}</span>
                    </button>

                    <button type="button" @click="$inertia.visit(route('dashboard.show', { locale, dashboard: dashboard.id }))" class="btn-form-secondary">
                        {{ translations.cancel }}
                    </button>
                </div>
            </form>
        </Card>


    </div>
</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { labelTransition } from '@/Composables/useFormAnimations';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import { defineProps, inject, ref } from 'vue';
    import Card from '@/Components/UI/Card.vue';

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
        code_file: null,
        user_comment: ''
    });

    const selectedFileName = ref('');
    const fileInput = ref(null);

    const handleFileChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            form.code_file = file;
            selectedFileName.value = file.name;
        }
    };

    const clearFile = () => {
        form.code_file = null;
        selectedFileName.value = '';
        if (fileInput.value) {
            fileInput.value.value = '';
        }
    };

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

    const activeView = inject('indexActiveView', null);
    const createAction = inject('createAction', null);
    if (createAction) {
        createAction.value = 'null';
    }
</script>


