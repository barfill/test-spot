<template>
    <label class="flex items-center cursor-pointer">
        <input
            type="checkbox"
            class="sr-only"
            :checked="localChecked"
            @change="handleToggle"
        />

        <div class="relative w-11">
            <div
                :class="{
                    'bg-orange-500 dark:bg-orange-400': localChecked,
                    'bg-gray-300': !localChecked }"
                class="h-6 rounded-full transition-colors">
            </div>
            <div
                :class="{
                    'translate-x-5': localChecked }"
                class="absolute top-0.5 left-0.5 h-5 w-5 bg-white rounded-full transition-transform">
            </div>
        </div>
    </label>
</template>


<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    isChecked: Number,
});
const localChecked = ref(props.isChecked);

const emit = defineEmits(['toggle']);

watch(() => props.isChecked, (newValue) => {
    localChecked.value = newValue;
});

const handleToggle = () => {
    localChecked.value = !localChecked.value;
    emit('toggle', localChecked.value);
};

</script>

