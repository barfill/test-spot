<template>
    <Link :href="route('dashboard.index')">MainLayout is here</Link>
    <div v-if="successMessage" class="alert-success">
        {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="alert-error">
        {{ errorMessage }}
    </div>
    <slot></slot>
</template>

<script setup>
    import { Link, usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';

    const page = usePage();
    const successMessage = computed(() => page.props.flash?.success);
    const errorMessage = computed(() => page.props.flash?.error);
</script>
