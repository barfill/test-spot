<template>
    <Breadcrumbs :breadcrumbs="[
        { label: dashboard.name, link: route('dashboard.show', { locale, dashboard: dashboard.id }) },
        { label: translations.edit, link: '#' }
    ]"
        :translations="translations"
        :locale="locale"
    />
    <form @submit.prevent="update">
        <div>
            <div>
                <label for="name">{{ translations.name }}</label>
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
                <button type="submit">{{ translations.update }}</button>
            </div>
        </div>
    </form>
</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';
    import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';

    const props = defineProps({
        dashboard: Object,
        locale: String,
        translations: Object
    });

    const form = useForm({
        name: props.dashboard.name,
        description: props.dashboard.description,
        image: props.dashboard.image_path,
        is_active: props.dashboard.is_active,
    });

    const update = () => form.put(route('dashboard.update', { locale: props.locale, dashboard: props.dashboard.id }))

</script>


