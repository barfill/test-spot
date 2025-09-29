<template>
    <input
        type="text"
        :name="name"
        :placeholder="placeholder"
        class="w-full border rounded-md bg-zinc-100 dark:bg-zinc-400 p-4 placeholder:text-zinc-500 dark:placeholder:text-zinc-200 ring-1 ring-slate-200 focus:ring-2"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />

</template>

<script setup>
    import { Link } from '@inertiajs/vue3';

    defineProps({
        translations: Object,
        locale: String,
        name: String,
        placeholder: String,
        modelValue: String
    });

</script>
