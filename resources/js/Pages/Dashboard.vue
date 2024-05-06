<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ReviewList from "@/Components/ReviewList.vue";

defineProps({
    userAuth: {
        type: Object,
    },
    userLogged: {
        type: Boolean,
        required: true
    },
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :userLogged="userLogged" :userAuth="userAuth">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>
        <ReviewList />
    </AuthenticatedLayout>
</template>
