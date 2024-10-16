<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
});

</script>

<template>

    <Head title="Register" />
    <form @submit.prevent="form.post(route('login'))">
        <InputText
            id="name"
            type="text"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
        />
        <div v-if="form.errors.name">{{ form.errors.name }}</div>

        <Button
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
        >
            Access to Chat
        </Button>
    </form>
</template>
