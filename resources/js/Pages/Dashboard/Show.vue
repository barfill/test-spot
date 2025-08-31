<template>
    <Breadcrumbs
        :breadcrumbs="[
            { label: dashboard.name, link: '#' }
        ]"
        :translations="translations"
        :locale="locale"
    />

    <h2>{{ dashboard.name }}</h2>
    <p>{{ dashboard.description }}</p>
</template>

<script setup>
import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';

defineProps({
    dashboard: Object,
    locale: String,
    translations: Object
});
</script>
