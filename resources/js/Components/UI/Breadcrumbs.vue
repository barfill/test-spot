<template>
    <nav class="pl-2">
        <ul class="flex items-center space-x-2 text-orange-500 dark:text-orange-400 font-bold text-md">
            <li>
                <Link :href="route('dashboard.index', { locale })" class="hover:text-orange-400 dark:hover:text-orange-300">{{ translations.dashboards }}</Link>
            </li>
            <template v-for="(crumb, index) in breadcrumbs" :key="index">
                <li class="text-gray-500">/</li>
                <li><Link :href="crumb.link" class="hover:text-orange-400 dark:hover:text-orange-300">{{ crumb.label }}</Link></li>
            </template>
        </ul>
    </nav>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';

    defineProps({
        breadcrumbs: Array,
        translations: Object,
        locale: String
    });
</script>

