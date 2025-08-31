<template>
    <Breadcrumbs
        :breadcrumbs="[
            { label: translations.create, link: '#' }
        ]"
        :translations="translations"
        :locale="locale"
    />
    <form
        @submit.prevent="create"
        @click="labelTransition"
    >
        <div class="flex flex-col">
            <div>
                <label for="name" class="label label-disabled">{{ translations.name }}</label>
                <span class="absolute span-enabled"> {{ translations.name }}</span>
                <input type="text" id="name" name="name" v-model="form.name" class="input"/>
                <div v-if="form.errors.name" class="text-error">{{ form.errors.name }}</div>
            </div>

            <div>
                <label for="description" class="label label-disabled">{{ translations.description }}</label>
                <span class="absolute span-enabled"> {{ translations.description }}</span>
                <input type="text" id="description" name="description" v-model="form.description" class="input"/>
                <div v-if="form.errors.description" class="text-error">{{ form.errors.description }}</div>
            </div>

            <div class="grid grid-cols-10 gap-4 justify-between pb-6">
                <div class="col-span-7">
                    <label for="image" class="label label-disabled">{{ translations.image }}</label>
                    <span class="absolute span-enabled"> {{ translations.image }}</span>
                    <input type="text" id="image" name="image" v-model="form.image" class="input"/>
                    <div v-if="form.errors.image" class="text-error">{{ form.errors.image }}</div>
                </div>

                <div
                    class="col-span-3"
                    :class="form.errors.image ? 'checkbox-container-scaled' : 'checkbox-container'"
                >
                    <label for="is_active" class="checkbox-label">{{ translations.active }}</label>
                    <input type="checkbox" id="is_active" name="is_active" v-model="form.is_active" class="checkbox"/>
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
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';

    const props = defineProps({
        locale: String,
        translations: Object,
        errors: Object,
        flash: Object
    });

    const form = useForm({
        name: '',
        description: '',
        image: '',
        is_active: false,
    });

    const create = () => form.post(route('dashboard.store', { locale: props.locale }))

    function getInputLabel(inputId) {
        return document.querySelector(`label[for="${inputId}"]`)
    }

    function getSpanAbove(inputId) {
        const input = document.getElementById(inputId);
        const previusElement = input.previousElementSibling;

        if(previusElement && previusElement.tagName === 'SPAN') {
            return previusElement;
        }

        return null;
    }

    let isAnimating = {};

    function labelTransition(event) {
        const inputElement = event.target;

        if(inputElement.tagName === 'INPUT' && inputElement.type === 'text') {
            if(isAnimating[inputElement.id]) {
                return;
            }
            if(inputElement.value !== '' && inputElement.value.trim() !== '') {
                return;
            }
            const label = getInputLabel(inputElement.id);
            const span = getSpanAbove(inputElement.id);

            const labelRect = label.getBoundingClientRect();
            const spanRect = span.getBoundingClientRect();

            if(spanRect.left === labelRect.left && spanRect.top === labelRect.top) {
                return;
            }

            isAnimating[inputElement.id] = true;

            const deltaX = labelRect.left - spanRect.left;
            const deltaY = labelRect.top - spanRect.top;

            span.style.transition = 'transform .5s ease';
            span.style.transform = `translate(${deltaX}px, ${deltaY}px)`;

            setTimeout(() => {
                isAnimating[inputElement.id] = false;
                label.classList.add('label-enabled');
                label.classList.remove('label-disabled');
                span.classList.add('span-disabled');
            }, 500);

            inputElement.addEventListener('blur', function() {
                if (inputElement.value === '' || inputElement.value.trim() === '') {
                    span.style.transition = 'transform .5s ease';
                    span.style.transform = 'translate(0, 0)';

                    label.classList.remove('label-enabled');
                    label.classList.add('label-disabled');
                    span.classList.remove('span-disabled');
                }
            })
        }
    }

</script>


