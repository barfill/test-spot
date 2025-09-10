<template>
    <header class="w-full border-b border-zinc-800 dark:border-zinc-300 bg-white dark:bg-zinc-800">
        <div class="container mx-auto">
            <nav class="flex items-center justify-between py-4 px-8">
                <div class="text-lg font-medium relative w-max">
                    <div v-if="user" ref="dropdownRef">
                        <div
                            class="flex items-center gap-2 cursor-pointer hover:bg-zinc-200 dark:hover:bg-zinc-700 p-2 rounded transition-colors"
                            @click="userDropDown"
                            :class="{ 'bg-zinc-200 dark:bg-zinc-700': userDropdownEnabled }"
                        >
                            <span class="text-zinc-700 dark:text-zinc-200"> {{ user.title ?? '' }} {{ user.first_name }} {{ user.last_name }}</span>
                            <svg
                                :class="{ 'rotate-180': userDropdownEnabled }"
                                class="w-4 h-4 text-zinc-700 dark:text-zinc-200 transition-transform"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            > <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>

                        <div v-if="userDropdownEnabled" class="absolute border  dark:border-zinc-200 p-2 rounded w-full bg-white dark:bg-zinc-700">
                            <Link :href="route('login', { locale })" class="block text-center w-full px-4 py-2 hover:bg-zinc-200 dark:hover:bg-zinc-600 rounded transition-colors">{{ translations.edit_profile }}</Link>
                            <Link :href="route('logout', { locale })" method="delete" as="button"
                                class="block text-center w-full px-4 py-2 hover:bg-zinc-200 dark:hover:bg-zinc-600 rounded transition-colors cursor-pointer"
                            >{{ translations.sign_out }}</Link>
                        </div>
                    </div>

                    <div v-else>
                        <Link :href="route('login', { locale })" class="p-2 hover:text-zinc-500 dark:hover:text-zinc-400 transition-colors">{{ translations.sign_in }}</Link>
                    </div>
                </div>
                <div class="text-heading">
                    <Link :href="route('dashboard.index', { locale })">TestSpot</Link>
                </div>
                <div class="flex gap-2 items-center">
                    <div v-if="(isStudent === false)">
                        <Link v-if="(createAction === 'createDashboard')" :href="route('dashboard.create', { locale })" class="btn-primary">{{ translations.create_dashboard }} +</Link>
                        <Link v-if="(createAction === 'createAssignment')" :href="route('dashboard.index', { locale })" class="btn-primary">{{ translations.create_assignment }} +</Link>
                    </div>
                    <!-- <Link v-if="(isStudent === false)" :href="route('dashboard.create', { locale })" class="btn-primary">{{ translations.create }} +</Link> -->
                    <select :value="locale" @change="changeLanguage" class="select-dropdown">
                        <option value="en">🇺🇸 English</option>
                        <option value="pl">🇵🇱 Polski</option>
                    </select>
                </div>
            </nav>
        </div>
    </header>

    <main class="container mx-auto p-8">
        <div v-if="successMessage" class="alert-success">
            {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="alert-error">
            {{ errorMessage }}
        </div>
        <slot></slot>
    </main>
</template>

<script setup>
    import { Link, usePage, router, useRemember } from '@inertiajs/vue3';
    import { computed, ref, onMounted, onUnmounted, provide } from 'vue';
    import { useLanguage } from '@/Composables/useLanguage';

    defineProps({
        locale: String,
        translations: Object
    });

    const page = usePage();
    const successMessage = computed(() => page.props.flash?.success);
    const errorMessage = computed(() => page.props.flash?.error);
    const user = computed(() => page.props.user);

    const indexActiveView = useRemember('assigned','dashboards.view');
    const isStudent = ref(user.value.type === 'student');

    provide('indexActiveView', indexActiveView);
    provide('isStudent', isStudent);

    const createAction = ref('createDashboard');
    provide('createAction', createAction);

    const { changeLanguage } = useLanguage();

    const userDropdownEnabled = ref(false);
    const dropdownRef = ref(null);

    function userDropDown() {
        userDropdownEnabled.value = !userDropdownEnabled.value;
    }

    function handleClickOutside(element) {
        if (!userDropdownEnabled.value) return;
        if (!dropdownRef.value) return;
        if (!dropdownRef.value.contains(element.target)) {
            userDropdownEnabled.value = false;
        }
        console.log('Clicked outside');
    }

    function handleKeydown(event) {
        if (!userDropdownEnabled.value) return;
        if (event.key === 'Escape' && userDropdownEnabled.value) {
            userDropdownEnabled.value = false;
        }
    }

    let lastScrollY = 0;
    function handleScrollDown() {
        if (!userDropdownEnabled.value) return;
        const currentY = window.scrollY;
        if (currentY > lastScrollY) {
            userDropdownEnabled.value = false;
        }
        lastScrollY = currentY;
    }

    onMounted(() => {
        document.addEventListener('click', handleClickOutside);
        document.addEventListener('keydown', handleKeydown);
        window.addEventListener('scroll', handleScrollDown);
    });

    onUnmounted(() => {
        document.removeEventListener('click', handleClickOutside);
        document.removeEventListener('keydown', handleKeydown);
        window.removeEventListener('scroll', handleScrollDown);
    });

</script>
