<template>
    <header class="w-full border-b border-zinc-800 dark:border-zinc-300 bg-white dark:bg-zinc-800">
        <div class="container mx-auto">
            <nav class="flex items-center justify-between py-4 px-8">
                <div class="text-lg font-medium relative">
                    <div v-if="user">
                        <div
                            @click="toggleUserDropdown"
                            class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-zinc-700 p-2 rounded-md transition-colors"
                        >
                            <span class="text-zinc-700 dark:text-zinc-200"> {{ user.title ?? '' }} {{ user.first_name }} {{ user.last_name }}</span>
                            <svg :class="{ 'rotate-180': userDropdownOpen }" class="w-4 h-4 transition-transform text-zinc-700 dark:text-zinc-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>

                        <div v-if="userDropdownOpen"
                            @click.stop
                            class="absolute top-full left-0 mt-2 min-w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md shadow-lg z-50"
                        >
                            <div>
                                <Link :href="route('login', { locale })" class="block px-4 py-2 hover:bg-gray-100">{{ translations.edit_profile }}</Link>

                                <button @click="logout" type="button" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                    {{ translations.sign_out }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <Link :href="route('login', { locale })">{{ translations.sign_in }}</Link>
                    </div>
                </div>
                <div class="text-heading">
                    <Link :href="route('dashboard.index', { locale })">TestSpot</Link>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('dashboard.create', { locale })" class="btn-primary">{{ translations.create }} +</Link>
                    <select :value="locale" @change="changeLanguage" class="select-dropdown">
                        <option value="en">🇺🇸 English</option>
                        <option value="pl">🇵🇱 Polski</option>
                    </select>
                </div>
            </nav>
        </div>
    </header>

    <div
        v-if="userDropdownOpen"
        @click="userDropdownOpen = false"
        class="fixed inset-0 z-40"
    ></div>

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
    import { Link, usePage, router } from '@inertiajs/vue3';
    import { computed, ref, onMounted, onUnmounted } from 'vue';
    import { useLanguage } from '@/Composables/useLanguage';

    defineProps({
        locale: String,
        translations: Object
    });

    const page = usePage();
    const successMessage = computed(() => page.props.flash?.success);
    const errorMessage = computed(() => page.props.flash?.error);
    const user = computed(() => page.props.user);

    const { changeLanguage } = useLanguage();

    const userDropdownOpen = ref(false);

    const toggleUserDropdown = () => {
        userDropdownOpen.value = !userDropdownOpen.value;
    }

    const logout = () => {
        router.post(route('logout', { locale: page.props.locale }));
    };

    const handleEscape = (e) => {
        if (e.key === 'Escape') {
            userDropdownOpen.value = false;
        }
    };

    onMounted(() => {
        document.addEventListener('keydown', handleEscape);
    });

    onUnmounted(() => {
        document.removeEventListener('keydown', handleEscape);
    });
</script>
