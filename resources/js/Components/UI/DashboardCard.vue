<template>
    <span>
        <img :src="dashboard.image" alt="" class="w-16 h-16 rounded-md"/>
        {{ dashboard.name }}
        {{ dashboard.description }}
    </span>
</template>

<script setup>
    defineProps({
        dashboard: Object
    });
</script>
