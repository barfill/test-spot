<template>
    <Breadcrumbs
        :breadcrumbs="[
            { label: dashboard.name, link: route('dashboard.show', { id: dashboard.id, locale }) },
            { label: translations.users, link: '#' }
        ]"
        :translations="translations"
        :locale="locale"
    />

    <div class="mx-auto w-8/9 border-1 bg-zinc-50 dark:bg-zinc-700 border-zinc-800 dark:border-zinc-300 rounded-md p-8 py-10 mt-6 shadow-md
                max-h-[calc(100vh-270px)]
                md:max-h-[calc(100vh-230px)]
                grid grid-rows-[auto_1fr] gap-4 overflow-hidden">
        <div class="col-span-full border p-4">search</div>

        <div class="overflow-y-auto grid grid-cols-1 lg:grid-cols-2 gap-8 auto-rows-max pr-5 pb-2">
            <div v-for="user in dashboardUsers" :key="user.id"
                class="user-row"
                :class="'user-selected'"
            >
                <div class="flex flex-row gap-4 justify-between items-center">
                    <div class="flex flex-col min-w-0">
                        <div class="text-md font-md text-zinc-700 dark:text-zinc-100">{{ user.title ? user.title : '' }} {{ user.first_name }} {{ user.last_name }}</div>
                        <div class="text-xs font-normal text-zinc-600 dark:text-zinc-800 leading-tight truncate" :title="user.email">{{ user.email }}</div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-2">
                        <div class="">toggle</div>
                        <div class="">view</div>
                    </div>
                </div>
            </div>
            <div v-for="student in students" :key="student.id"
                class="user-row"
                :class="'user-unselected'"
            >
                <div class="flex flex-row gap-4 justify-between items-center">
                    <div class="flex flex-col min-w-0">
                        <div class="text-md font-md text-zinc-700 dark:text-zinc-100">{{ student.title ? student.title : '' }} {{ student.first_name }} {{ student.last_name }}</div>
                        <div class="text-xs font-normal text-zinc-600 dark:text-zinc-800 leading-tight truncate" :title="student.email">{{ student.email }}</div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-2">
                        <div class="">toggle</div>
                        <div class="">view</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { labelTransition } from '@/Composables/useFormAnimations';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import { defineProps, inject, ref, provide } from 'vue';


    const props = defineProps({
        dashboard: Object,
        dashboardUsers: Array,
        students: Array,
        locale: String,
        translations: Object,
        errors: Object,
        flash: Object
    });

    const form = useForm({
        name: '',
        description: '',
        image: '',
        is_active: false,
    });

    const create = () => form.post(route('dashboard.store', { locale: props.locale }));
    const activeView = inject('indexActiveView');

    const createAction = inject('createAction');
    createAction.value = 'createUser';

</script>
