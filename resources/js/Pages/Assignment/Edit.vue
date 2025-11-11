<template>
    <Breadcrumbs
        :breadcrumbs="[
            { label: dashboard.name, link: route('dashboard.show', { locale, dashboard: dashboard.id }) },
            { label: assignment.name, link: route('dashboard.assignments.show', { locale, dashboard: dashboard.id, assignment: assignment.id }) },
            { label: translations.edit, link: '#' },
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
                <div class="col-span-1 md:col-span-4"
                    @click="changeInputType('start_time', 'datetime-local', formatDateTimeForInputToISO(form.start_time), form.start_time)"
                    @focus="changeInputType('start_time', 'datetime-local', formatDateTimeForInputToISO(form.start_time), form.start_time)"
                >
                    <label for="start_time" class="label label-disabled">{{ translations.start_time }}</label>
                    <span class="absolute span-enabled"> {{ translations.start_time }}</span>
                    <input type="text" id="start_time" name="start_time" v-model="form.start_time" @input="labelTransition" @keydown="labelTransition" class="input "/>
                    <div v-if="form.errors.start_time" class="text-error">{{ form.errors.start_time }}</div>
                </div>

                <div class="col-span-1 md:col-span-4"
                    @click="changeInputType('end_time', 'datetime-local', formatDateTimeForInputToISO(form.end_time), form.end_time)"
                    @focus="changeInputType('end_time', 'datetime-local', formatDateTimeForInputToISO(form.end_time), form.end_time)"
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
    import { labelTransition, changeInputType, initializeFormLabels, formatDateTimeForInputToISO, formatDateTimeForInputForTextInputs } from '@/Composables/useFormAnimations';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';
    import { inject, onMounted, nextTick } from 'vue';

    onMounted(async () => {
        await nextTick();
        initializeFormLabels();
    });

    const props = defineProps({
        locale: String,
        dashboard: Object,
        assignment: Object,
        translations: Object,
        errors: Object,
        flash: Object
    });

    const form = useForm({
        name: props.assignment.name,
        description: props.assignment.description,
        start_time: formatDateTimeForInputForTextInputs(props.assignment.start_time),
        end_time: formatDateTimeForInputForTextInputs(props.assignment.end_time),
        status: props.assignment.status,
    });

    const update = () => form.put(route('dashboard.assignments.update', { locale: props.locale, dashboard: props.dashboard.id, assignment: props.assignment.id }));
    const activeView = inject('indexActiveView');
    const createAction = inject('createAction');
    createAction.value = 'createAssignment';
</script>


