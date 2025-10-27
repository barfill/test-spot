<template>
    <div class="text-md font-bold p-8 opacity-25 text-center">
        <p class="text-gray-500 dark:text-zinc-400">
            {{ translations.no_items.replace(':items', translations[props.name + '_e']) }}
        </p>
    </div>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';

    const props = defineProps({
        translations: Object,
        locale: String,
        name: String,
    });

</script>
