<template>
    <div class="flex justify-between items-center mb-6">
        <Breadcrumbs
            :breadcrumbs="[
                { label: translations.users, link: '#' }
            ]"
            :translations="translations"
            :locale="locale"
        />
        <div class="flex flex-row gap-2">
            <Link
                @click="activeView ='created'"
                class="btn-primary cursor-pointer"
                :title="translations.create_user"
                :href="route('user.create', { locale})"
            >
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                </span>
            </Link>
        </div>
    </div>

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
                class="user-row user-selected"
            >
                <div class="flex flex-row gap-4 justify-between items-center">
                    <div class="flex flex-col min-w-0">
                        <div class="text-md font-md text-zinc-700 dark:text-zinc-100">{{ user.title ?? '' }} {{ user.first_name }} {{ user.last_name }}</div>
                        <div class="text-xs font-normal text-zinc-600 dark:text-zinc-800 leading-tight truncate" :title="user.email">{{ user.email }}</div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-2">
                        <Link :href="route('user.show', { locale, user })"
                            class="btn-edit">
                            {{ translations.edit }}
                        </Link>
                        <Link
                            :href="route('user.destroy', { locale, user })"
                            method="delete"
                            as="button"
                            :disabled="user.id === $page.props.user.id"
                            :class="$page.props.user.id === user.id ? 'btn-danger-disabled' : 'btn-danger'"
                        >
                            {{ translations.delete }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { Link, usePage, useRemember, Form, useForm } from '@inertiajs/vue3';
    import DashboardCard from '@/Components/UI/DashboardCard.vue';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import TextInput from '@/Components/UI/TextInput.vue';
    import { defineProps, inject, ref, provide, computed } from 'vue';


    const props = defineProps({
        users: Object,
        locale: String,
        translations: Object,
        errors: Object,
        flash: Object
    });

    const searchQuery = ref('');

    const filteredUsers = computed(() => {
        if (!searchQuery.value.trim()) {
            return props.users;
        }

        const query = searchQuery.value.toLowerCase().trim();
        return props.users.filter(user => {
            const fullName = `${user.title ?? ''} ${user.first_name} ${user.last_name}`.toLowerCase();
            const email = user.email.toLowerCase();

            return fullName.includes(query) || email.includes(query);
        });
    });


    const createAction = inject('createAction');
    createAction.value = 'createUser';


</script>


