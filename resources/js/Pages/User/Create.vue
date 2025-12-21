<template>
    <Breadcrumbs :breadcrumbs="[
        { label: translations.users, link: route('user.index', { locale }) },
        { label: translations.create_user, link: '#' }
    ]"
        :translations="translations"
        :locale="locale"
    />
    <form
        @submit.prevent="store"
        @click="labelTransition"
        @focus="labelTransition"
        @mousedown="labelTransition"
        @focusin="labelTransition"
    >
        <div class="flex flex-col">
            <div>
                <label for="email" class="label label-disabled">{{ translations.email }}</label>
                <span class="absolute span-enabled"> {{ translations.email }}</span>
                <input type="email" id="email" name="email" v-model="form.email" class="input"/>
                <div v-if="form.errors.email" class="text-error">{{ form.errors.email }}</div>
            </div>

            <div>
                <label for="password" class="label label-disabled">{{ translations.password }}</label>
                <span class="absolute span-enabled"> {{ translations.password }}</span>
                <input type="password" id="password" name="password" v-model="form.password" @input="labelTransition" @keydown="labelTransition" class="input" :placeholder="translations.leave_empty_to_keep_current_password"/>
                <div v-if="form.errors.password" class="text-error">{{ form.errors.password }}</div>
            </div>
            <div>
                <label for="type" class="label label-enabled">{{ translations.type }}</label>
                <select id="type" name="type" v-model="form.type" class="input select-user-dropdown">
                    <option value="student">{{ translations.student }}</option>
                    <option value="teacher">{{ translations.teacher }}</option>
                    <option value="admin">{{ translations.admin }}</option>
                </select>
                <div v-if="form.errors.type" class="text-error">{{ form.errors.type }}</div>
            </div>

            <div>
                <label for="title" class="label label-disabled">{{ translations.title }}</label>
                <span class="absolute span-enabled"> {{ translations.title }}</span>
                <input type="text" id="title" name="title" v-model="form.title" @input="labelTransition" @keydown="labelTransition" class="input"/>
                <div v-if="form.errors.title" class="text-error">{{ form.errors.title }}</div>
            </div>

            <div>
                <label for="first_name" class="label label-disabled">{{ translations.first_name }}</label>
                <span class="absolute span-enabled"> {{ translations.first_name }}</span>
                <input type="text" id="first_name" name="first_name" v-model="form.first_name" @input="labelTransition" @keydown="labelTransition" class="input"/>
                <div v-if="form.errors.first_name" class="text-error">{{ form.errors.first_name }}</div>
            </div>

            <div class="pb-8">
                <label for="last_name" class="label label-disabled">{{ translations.last_name }}</label>
                <span class="absolute span-enabled"> {{ translations.last_name }}</span>
                <input type="text" id="last_name" name="last_name" v-model="form.last_name" @input="labelTransition" @keydown="labelTransition" class="input"/>
                <div v-if="form.errors.last_name" class="text-error">{{ form.errors.last_name }}</div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-form-primary flex-1">{{ translations.save }}</button>
                <button type="button" @click="$inertia.visit(route('user.index', { locale }))" class="btn-form-secondary flex-1">{{ translations.cancel }}</button>
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
        editedUser: Object,
        locale: String,
        translations: Object,
        errors: Object,
        flash: Object
    });

    const form = useForm({
        email: '',
        password: '',
        type: 'student',
        title: '',
        first_name: '',
        last_name: '',
    });

    const store = () => form.post(route('user.store', { locale: props.locale }));
</script>


