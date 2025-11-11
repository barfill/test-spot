<template>
    <Breadcrumbs
        :breadcrumbs="[
            { label: dashboard.name, link: route('dashboard.show', { locale, dashboard: dashboard.id }) },
            { label: translations.create, link: '#' },
        ]"
        :translations="translations"
        :locale="locale"
    />
    <form
        @submit.prevent="create"
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
                <div class="col-span-1 md:col-span-4"
                    @click="changeInputType('start_time', 'datetime-local')"
                    @focus="changeInputType('start_time', 'datetime-local')"
                >
                    <label for="start_time" class="label label-disabled">{{ translations.start_time }}</label>
                    <span class="absolute span-enabled"> {{ translations.start_time }}</span>
                    <input type="text" id="start_time" name="start_time" v-model="form.start_time" @input="labelTransition" @keydown="labelTransition" class="input"/>
                    <div v-if="form.errors.start_time" class="text-error">{{ form.errors.start_time }}</div>
                </div>

                <div class="col-span-1 md:col-span-4"
                    @click="changeInputType('end_time', 'datetime-local')"
                    @focus="changeInputType('end_time', 'datetime-local')"
                >
                    <label for="end_time" class="label label-disabled">{{ translations.end_time }}</label>
                    <span class="absolute span-enabled"> {{ translations.end_time }}</span>
                    <input type="text" id="end_time" name="end_time" v-model="form.end_time" @input="labelTransition" @keydown="labelTransition" class="input"/>
                    <div v-if="form.errors.end_time" class="text-error">{{ form.errors.end_time }}</div>
                </div>

                <div class="col-span-1  md:col-span-2 flex items-end gap-3">
                    <input type="checkbox" id="status" name="status" v-model="form.status" class="hidden"/>
                    <div class="w-full">
                        <button
                            type="button"
                            class="cursor-pointer"
                            @click="form.status = form.status === 'open' ? 'closed' : 'open'"
                            :class="form.status === 'open' ? 'btn-toggle-active' : 'btn-toggle-inactive'"
                        >
                            <template v-if="form.status === 'open'" class="transition: all 0.3s ease-in-out">
                                {{ translations.open }}
                            </template>
                            <template v-else class="transition: all 0.3s ease-in-out">
                                {{ translations.closed }}
                            </template>
                        </button>
                        <div v-if="form.errors.end_time || form.errors.start_time" class="hidden md:block text-hidden">hidden</div>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="btn-form-primary w-full">{{ translations.save }}</button>
            </div>
        </div>
    </form>
</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { labelTransition, changeInputType } from '@/Composables/useFormAnimations';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import { defineProps, inject } from 'vue';

    const props = defineProps({
        locale: String,
        dashboard: Object,
        translations: Object,
        errors: Object,
        flash: Object
    });

    const form = useForm({
        name: '',
        description: '',
        start_time: '',
        end_time: '',
        status: 'open',
    });

    const create = () => form.post(route('dashboard.assignments.store', { locale: props.locale, dashboard: props.dashboard.id }));
    const activeView = inject('indexActiveView');
    const createAction = inject('createAction');
    createAction.value = 'null';
</script>


