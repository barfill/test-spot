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
        <div class="col-span-full">
            <form @submit.prevent>
                <TextInput
                    v-model="searchQuery"
                    :name="'search_users'"
                    :placeholder="translations.search_users"
                    autocomplete="off"
                />
            </form>
        </div>

        <div class="overflow-y-auto grid grid-cols-1 lg:grid-cols-2 gap-8 auto-rows-max pr-5 pb-2">
              <div v-for="user in filteredUsers" :key="user.id"
                class="user-row"
                :class="user.is_in_dashboard ? 'user-selected' : 'user-unselected'"
            >
                <div class="flex flex-row gap-4 justify-between items-center">
                    <div class="flex flex-col min-w-0">
                        <div class="text-md font-md text-zinc-700 dark:text-zinc-100">{{ user.title ? user.title : '' }} {{ user.first_name }} {{ user.last_name }}</div>
                        <div class="text-xs font-normal text-zinc-600 dark:text-zinc-800 leading-tight truncate" :title="user.email">{{ user.email }}</div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-2">
                        <Toggle
                            :is-checked="user.is_in_dashboard"
                            @toggle="(value) => handleUserToggle(user, user.is_in_dashboard, value)"
                        />
                        <div class="">view</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
    import { Form, useForm } from '@inertiajs/vue3';
    import { labelTransition } from '@/Composables/useFormAnimations';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import TextInput from '@/Components/UI/TextInput.vue';
    import Toggle from '@/Components/UI/Toggle.vue';
    import { defineProps, inject, ref, provide, computed } from 'vue';

    const props = defineProps({
        dashboard: Object,
        usersCollection: Array,
        locale: String,
        translations: Object,
        errors: Object,
        flash: Object
    });

    const searchQuery = ref('');

    const filteredUsers = computed(() => {
        if (!searchQuery.value.trim()) {
            return props.usersCollection;
        }

        const query = searchQuery.value.toLowerCase().trim();
        return props.usersCollection.filter(user => {
            const fullName = `${user.title || ''} ${user.first_name} ${user.last_name}`.toLowerCase();
            const email = user.email.toLowerCase();

            return fullName.includes(query) || email.includes(query);
        });
    });

    const handleUserToggle = (user, isCurrentlyInDashboard, newValue) => {
        const form = useForm({
            action: newValue ? 'attach' : 'detach'
        });

        form.put(route('dashboard.users.update', {
            dashboard: props.dashboard.id,
            user: user.id
        }), {
            onSuccess: () => {
                user.is_in_dashboard = newValue;
                // console.log(`User ${newValue ? 'added to' : 'removed from'} dashboard`);
            },
            onError: (errors) => {
                console.error('Toggle failed:', errors);
            }
        });
    };
    const createAction = inject('createAction');
    createAction.value = 'createUser';

 </script>
