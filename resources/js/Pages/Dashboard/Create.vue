<template>
    <Breadcrumbs
        :breadcrumbs="[
            { label: translations.create, link: '#' }
        ]"
        :translations="translations"
        :locale="locale"
    />
    <form @submit.prevent="create">
        <div>
            <div>
                <label for="name">{{ translations.name }}:</label>
                <input type="text" id="name" name="name" v-model="form.name"/>
                <div v-if="form.errors.name">{{ form.errors.name }}</div>
            </div>

            <div>
                <label for="description">{{ translations.description }}:</label>
                <input type="text" id="description" name="description" v-model="form.description"/>
                <div v-if="form.errors.description">{{ form.errors.description }}</div>
            </div>

            <div>
                <label for="image">{{ translations.image }}:</label>
                <input type="text" id="image" name="image" v-model="form.image"/>
                <div v-if="form.errors.image">{{ form.errors.image }}</div>
            </div>

            <div>
                <label for="is_active">{{ translations.active }}:</label>
                <input type="checkbox" id="is_active" name="is_active" v-model="form.is_active"/>
            </div>

            <div>
                <button type="submit">{{ translations.save }}</button>
            </div>
        </div>
    </form>
</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';

    const props = defineProps({
        locale: String,
        translations: Object
    });

    const form = useForm({
        name: '',
        description: '',
        image: '',
        is_active: false,
    });

    const create = () => form.post(route('dashboard.store', { locale: props.locale }))

</script>


