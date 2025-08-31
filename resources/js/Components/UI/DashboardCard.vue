<template>
    <div class="grid grid-cols-12 gap-6 pb-4">
        <div class="col-span-5 flex items-center justify-center w-full h-24">
            <img v-if="dashboard.image"
                 :src="dashboard.image"
                 alt="#"
                 class="w-full h-full object-cover rounded-md"/>
            <div v-else
                 class="w-full h-full flex items-center justify-center bg-zinc-100 dark:bg-zinc-600 rounded-md text-zinc-400">
                {{ translations.no_images }}
            </div>
        </div>
        <div class="col-span-7 flex flex-col justify-center h-24 gap-3 pr-3">
            <span :title="dashboard.name" class="font-bold text-zinc-700 dark:text-zinc-100 text-lg leading-tight truncate">{{ dashboard.name }}</span>
            <span :title="dashboard.description" class="text-sm text-zinc-500 dark:text-zinc-200 leading-tight line-clamp-2">{{ dashboard.description }}</span>
        </div>
    </div>
</template>

<script setup>
    defineProps({
        dashboard: Object,
        translations: Object
    });
</script>
