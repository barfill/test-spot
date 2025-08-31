<template>
    <header class="w-full border-b border-zinc-800 dark:border-zinc-300 bg-white dark:bg-zinc-800">
        <div class="container mx-auto">
            <nav class="flex items-center justify-between p-4">
                <div class="text-lg font-medium">
                    <Link :href="route('dashboard.index', { locale })">{{ translations.dashboards }}</Link>
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

    <main class="container mx-auto">
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
    import { computed } from 'vue';

    defineProps({
        locale: String,
        translations: Object
    });

    const page = usePage();
    const successMessage = computed(() => page.props.flash?.success);
    const errorMessage = computed(() => page.props.flash?.error);

    const changeLanguage = (event) => {
        const newLocale = event.target.value;
        const currentPath = window.location.pathname.replace(/^\/(en|pl)/, '') || '/dashboard';
        router.visit(`/${newLocale}${currentPath}`);
    };
</script>
