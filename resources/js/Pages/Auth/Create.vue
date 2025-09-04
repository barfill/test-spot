<template>
    <form
        @submit.prevent="create"
        @click="labelTransition"
        @focus="labelTransition"
        @mousedown="labelTransition"
        @focusin="labelTransition"
    >
        <div class="mx-auto w-1/2 flex flex-col">
            <div>
                <label for="email" class="label label-disabled">{{ translations.email }}</label>
                <span class="absolute span-enabled"> {{ translations.email }}</span>
                <input type="text" id="email" name="email" v-model="form.email" @input="labelTransition" @keydown="labelTransition" class="input"/>
                <div v-if="form.errors.email" class="text-error">{{ form.errors.email }}</div>
            </div>
            <div>
                <label for="password" class="label label-disabled">{{ translations.password }}</label>
                <span class="absolute span-enabled"> {{ translations.password }}</span>
                <input type="password" id="password" name="password" v-model="form.password" @input="labelTransition" @keydown="labelTransition" class="input"/>
                <div v-if="form.errors.password" class="text-error">{{ form.errors.password }}</div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn-form-primary w-full">{{ translations.login }}</button>
            </div>
        </div>
    </form>
</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { labelTransition } from '@/Composables/useFormAnimations';
    import AuthLayout from '@/Layouts/AuthLayout.vue';

    const props = defineProps({
        locale: String,
        translations: Object,
        errors: Object,
        flash: Object
    });

    defineOptions({
        layout: AuthLayout
    });

    const form = useForm({
        email: null,
        password: null,
    });

    const create = () => form.post(route('login.store', { locale: props.locale }));
</script>
