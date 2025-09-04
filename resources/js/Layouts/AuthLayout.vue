<template>
    <header class="w-full border-b border-zinc-800 dark:border-zinc-300 bg-white dark:bg-zinc-800">
        <div class="container mx-auto">
            <nav class="flex items-center justify-between py-4 px-8">
                <div class="text-heading">
                    <Link :href="route('login', { locale })">TestSpot</Link>
                </div>
                <div class="flex gap-2">
                    <select :value="locale" @change="changeLanguage" class="select-dropdown">
                        <option value="en">🇺🇸 English</option>
                        <option value="pl">🇵🇱 Polski</option>
                    </select>
                </div>
            </nav>
        </div>
    </header>

    <main class="container mx-auto p-8 w-full">
        <div class="w-1/2 mx-auto">
            <div v-if="successMessage" class="alert-success">
                {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="alert-error">
                {{ errorMessage }}
            </div>
        </div>
        <slot></slot>
    </main>
</template>

<script setup>
    import { Link, usePage, router } from '@inertiajs/vue3';
    import { computed } from 'vue';
    import { useLanguage } from '@/Composables/useLanguage';

    defineProps({
        locale: String,
        translations: Object
    });

    const page = usePage();
    const successMessage = computed(() => page.props.flash?.success);
    const errorMessage = computed(() => page.props.flash?.error);

    const { changeLanguage } = useLanguage();
</script>
