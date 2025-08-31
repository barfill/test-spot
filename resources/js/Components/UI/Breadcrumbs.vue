<template>
    <nav>
        <ul class="flex items-center space-x-2 text-indigo-600">
            <li>
                <a :href="route('dashboard.index', { locale })" class="hover:text-indigo-800">{{ translations.dashboards }}</a>
            </li>
            <template v-for="(crumb, index) in breadcrumbs" :key="index">
                <li class="text-gray-500">/</li>
                <li><a :href="crumb.link" class="hover:text-indigo-800">{{ crumb.label }}</a></li>
            </template>
        </ul>
    </nav>
</template>

<script setup>
    defineProps({
        breadcrumbs: Array,
        translations: Object,
        locale: String
    });
</script>

