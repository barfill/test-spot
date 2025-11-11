<template>
    <div class="w-full h-3 border border-gray-400 rounded-md flex overflow-hidden">
        <div
            class="h-full bg-orange-500 dark:bg-orange-400"
            :style="{ width: completedDaysPercentage + '%' }"
        ></div>
        <div
            class="h-full bg-gray-200 dark:bg-gray-400"
            :style="{ width: remainingDaysPercentage + '%' }"
        ></div>
    </div>
    <div
        class="w-full text-xs font-light text-center text-gray-600 dark:text-gray-300 mt-1"
    >{{ completedDaysPercentage.toFixed(2) }}%</div>
</template>

<script setup>
    import { defineProps, computed } from 'vue';

    const props = defineProps({
        startTime: String,
        endTime: String,
        duration: Number,
        remainingDays: Number
    });

    const completedDaysPercentage = computed(() => {
        const startDate = new Date(props.startTime);
        const endDate = new Date(props.endTime);
        const now = new Date();

        const totalDuration = (endDate - startDate) / (1000 * 60 * 60 * 24);
        const elapsedDuration = (now - startDate) / (1000 * 60 * 60 * 24);

        if (elapsedDuration <= 0) return 0;
        if (elapsedDuration >= totalDuration) return 100;

        return (elapsedDuration / totalDuration) * 100;
    })

    const remainingDaysPercentage = computed(() => {
        const days = parseInt(props.remainingDays.match(/\d+/));
        return days * 100 / props.duration;
    })

</script>
