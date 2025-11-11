<template>
    <Breadcrumbs :breadcrumbs="[
        { label: dashboard.name, link: route('dashboard.show', { locale, dashboard: dashboard.id }) },
        { label: translations.edit, link: '#' }
    ]"
        :translations="translations"
        :locale="locale"
    />
    <form
        @submit.prevent="update"
        @click="labelTransition"
        @focus="labelTransition"
        @mousedown="labelTransition"
        @focusin="labelTransition"
    >
        <div class="flex flex-col">
            <div>
                <label for="name" class="label label-disabled">{{ translations.name }}</label>
                <span class="absolute span-enabled"> {{ translations.name }}</span>
                <input type="text" id="name" name="name" v-model="form.name" @input="labelTransition" @keydown="labelTransition" class="input"/>
                <div v-if="form.errors.name" class="text-error">{{ form.errors.name }}</div>
            </div>

            <div>
                <label for="description" class="label label-disabled">{{ translations.description }}</label>
                <span class="absolute span-enabled"> {{ translations.description }}</span>
                <textarea id="description" name="description" v-model="form.description" @input="labelTransition" @keydown="labelTransition" class="input"></textarea>
                <div v-if="form.errors.description" class="text-error">{{ form.errors.description }}</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-10 gap-4 justify-between pb-6">
                <div class="col-span-1 md:col-span-7">
                    <label for="image" class="label label-disabled">{{ translations.image }}</label>
                    <span class="absolute span-enabled"> {{ translations.image }}</span>
                    <input type="text" id="image" name="image" v-model="form.image" @input="labelTransition" @keydown="labelTransition" class="input"/>
                    <div v-if="form.errors.image" class="text-error">{{ form.errors.image }}</div>
                </div>

                <div class="col-span-1  md:col-span-3 flex items-end gap-3">
                    <input type="checkbox" id="is_active" name="is_active" v-model="form.is_active" class="hidden"/>
                    <div class="w-full">
                        <button
                            type="button"
                            class="cursor-pointer"
                            @click="form.is_active = !form.is_active"
                            :class="form.is_active ? 'btn-toggle-active' : 'btn-toggle-inactive'"
                        >
                            <template v-if="form.is_active" class="transition: all 0.3s ease-in-out">
                                {{ translations.active }}
                            </template>
                            <template v-else class="transition: all 0.3s ease-in-out">
                                {{ translations.inactive }}
                            </template>
                        </button>
                        <div v-if="form.errors.image" class="hidden md:block text-hidden">hidden</div>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="btn-form-primary w-full">{{ translations.edit }}</button>
            </div>
        </div>
    </form>
</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { onMounted, nextTick, inject } from 'vue';

    import { labelTransition } from '@/Composables/useFormAnimations';
    import { initializeFormLabels } from '../../Composables/useFormAnimations';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';

    onMounted(async () => {
        await nextTick();
        initializeFormLabels();
    });

    const props = defineProps({
        dashboard: Object,
        locale: String,
        translations: Object,
        errors: Object,
        flash: Object
    });

    const form = useForm({
        name: props.dashboard.name,
        description: props.dashboard.description,
        image: props.dashboard.image_path,
        is_active: props.dashboard.is_active,
    });

    const update = () => form.put(route('dashboard.update', { locale: props.locale, dashboard: props.dashboard.id }));
    const activeView = inject('indexActiveView');
</script>


