<template>
    <Breadcrumbs
        :breadcrumbs="[
            { label: dashboard.name, link: route('dashboard.show', { id: dashboard.id, locale }) },
            { label: translations.users, link: '#' }
        ]"
        :translations="translations"
        :locale="locale"
    />

    <div class="mx-auto w-3/4 border-1 border-zinc-800 dark:border-zinc-300 rounded-md p-8 py-10 mt-6 shadow-md flex flex-col gap-2 overflow-y-scroll">
        <div class="col-span-12">{{ dashboard.name }}</div>
        <div v-for="user in dashboardUsers" :key="user.id"
            class="grid grid-cols-12 gap-2 col-span-12 border-b border-zinc-300 dark:border-zinc-700 py-2"
            :class="'user-selected'">
            <div class="col-span-8">{{ user.first_name }} {{ user.last_name }}, {{ user.email }}</div>
            <div class="col-span-2">toggle</div>
            <div class="col-span-2">view</div>
        </div>
        <div v-for="student in students" :key="student.id" class="grid grid-cols-12 gap-2 col-span-12 border-b border-zinc-300 dark:border-zinc-700 py-2">
            <div class="col-span-8">{{ student.first_name }} {{ student.last_name }}, {{ student.email }}</div>
            <div class="col-span-2">toggle</div>
            <div class="col-span-2">view</div>
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
