<template>
    <div class="mx-auto w-3/4 border-1 border-zinc-800 dark:border-zinc-300 rounded-md p-8 py-10 mt-6">
        <div class="mx-auto w-3/4 flex flex-col lg:flex-row items-center justify-between mb-4 lg:mb-8">
            <h1 class="text-3xl font-bold text-center text-zinc-700 dark:text-zinc-200 mb-2 lg:mb-0">{{ translations.login }}</h1>
            <div class="flex gap-2">
                <span class="text-zinc-700 dark:text-zinc-200">{{ translations.no_account }}</span>
                <Link :href="route('register', { locale })" class="text-orange-500 dark:text-orange-400 hover:underline">{{ translations.register }}</Link>
            </div>
        </div>
        <form
            @submit.prevent="create"
            @click="labelTransition"
            @focus="labelTransition"
            @mousedown="labelTransition"
            @focusin="labelTransition"
        >
            <div class="mx-auto w-3/4 flex flex-col">
                <div>
                    <label for="email" class="label label-disabled">{{ translations.email }}</label>
                    <span class="absolute span-enabled"> {{ translations.email }}</span>
                    <input type="text" id="email" name="email" v-model="form.email" @input="labelTransition" @keydown="labelTransition" class="input"/>
                    <div class="mt-1.5 h-5">
                        <p v-if="form.errors.email" class="text-error">{{ form.errors.email }}</p>
                    </div>
                    <!-- <div v-if="form.errors.email" class="text-error">{{ form.errors.email }}</div> -->
                </div>
                <div>
                    <label for="password" class="label label-disabled">{{ translations.password }}</label>
                    <span class="absolute span-enabled"> {{ translations.password }}</span>
                    <input type="password" id="password" name="password" v-model="form.password" @input="labelTransition" @keydown="labelTransition" class="input"/>
                    <!-- <div v-if="form.errors.password" class="text-error">{{ form.errors.password }}</div> -->
                    <div class="mt-1.5 h-5">
                        <p v-if="form.errors.password" class="text-error">{{ form.errors.password }}</p>
                    </div>
                </div>
                <div class="mt-8 flex flex-col gap-4">
                    <div class="flex gap-4 p-2">
                        <input type="checkbox" id="remember_me" name="remember_me" v-model="form.remember_me" class="checkbox"/>
                        <label for="remember_me" class="text-zinc-700 dark:text-zinc-200 font-medium">{{ translations.remember_me }}</label>
                    </div>
                    <button type="submit" class="btn-form-primary w-full">{{ translations.login }}</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
    import { Link, useForm } from '@inertiajs/vue3';
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
        remember_me: false,
    });

    const create = () => form.post(route('login.store', { locale: props.locale }));
</script>
