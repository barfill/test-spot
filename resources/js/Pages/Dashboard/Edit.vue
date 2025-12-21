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
                    <label for="image" class="label label-enabled">{{ translations.image }}</label>
                    <input ref="fileInput" type="file" accept="image/*" @change="handleImageUpload" class="hidden" />

                    <div class="flex items-center gap-3 flex-col">
                        <button
                            type="button"
                            @click="$refs.fileInput.click()"
                            class="btn-form-tertiary w-full"
                        >
                        <div class="flex items-center justify-center gap-3 flex-row">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            <span>
                                {{ selectedFileName || translations.add_image || 'Dodaj zdjęcie' }}
                            </span>
                        </div>
                        </button>

                    </div>
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
    import { onMounted, nextTick, inject, ref } from 'vue';

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
        _method: 'put',
        name: props.dashboard.name,
        description: props.dashboard.description,
        image: null,
        is_active: props.dashboard.is_active,
    });

    const selectedFileName = ref(props.dashboard.image_path ? props.dashboard.image_path.split('/').pop() : '');

    const handleImageUpload = (event) => {
        const file = event.target.files[0];
        if (file) {
            form.image = file;
            selectedFileName.value = file.name;
        }
    };

    const update = () => form.post(route('dashboard.update', { locale: props.locale, dashboard: props.dashboard.id }));
    const activeView = inject('indexActiveView');
</script>


