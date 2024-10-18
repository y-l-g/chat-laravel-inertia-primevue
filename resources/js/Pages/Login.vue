<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
});

</script>

<template>

    <Head title="Register" />
    <form
        @submit.prevent="form.post(route('login'))"
        class="
        sm:p-[8%]
        p-5
        max-w-xl
        h-screen
        m-auto
        flex-col
        flex
        gap-6"
    >
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
            @click.prevent="form.post(route('login'))"
        >
            Se connecter
        </Button>
    </form>
</template>
