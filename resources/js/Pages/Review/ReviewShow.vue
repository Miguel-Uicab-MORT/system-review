<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {onMounted, ref} from "vue";

defineProps({
    userAuth: {
        type: Object,
    },
    userLogged: {
        type: Boolean,
        required: true
    },
    id: {
        required: true
    }
});

const form = useForm({
    content: '',
});

const $page = usePage();

const review = ref([]);
const comments = ref([]);

function getReview() {
    axios.get(`http://reviews.test/api/review/${$page.props.id}`)
        .then(response => {
            review.value = response.data.review;
            comments.value = response.data.comments;
        })
        .catch(error => {
            console.error(error);
        });
}

function submit() {
    const formData = new FormData();
    formData.append('comment', form.content);
    formData.append('user_id', $page.props.userAuth.id);
    formData.append('review_id', $page.props.id);

    axios.post('http://reviews.test/api/review/add-comment', formData).then(response => {
        if (response.status === 200) {
            form.reset();
            getReview();
        } else if (response.status === 400) {
            console.error(response.data.message);
        } else {
            console.error(response.status);
        }
    }).catch(error => {
        console.error(error);
    });
}


onMounted(() => {
    getReview();
})

</script>

<template>
    <Head title="Detalles" />

    <AuthenticatedLayout :userLogged="userLogged" :userAuth="userAuth">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ review.title }}</h2>
        </template>

        <div class="container mx-auto max-w-7xl p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col space-y-4 bg-white rounded-lg shadow-md p-6">
                    <p class="text-gray-600">{{ review.synopsis }}</p>

                    <div class="flex items-center text-gray-600">
                        <span class="mr-2">Autor:</span>
                        <span class="font-semibold">{{ review.writer }}</span>
                    </div>

                    <div class="flex items-center text-gray-600">
                        <span class="mr-2">Fecha de estreno:</span>
                        <span class="font-semibold">{{ review.release_date }}</span>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Reseña</h3>
                    <p class="text-gray-600">{{ review.review }}</p>
                </div>

                <div class="flex flex-col items-center">
                    <img :src="review.url_poster" alt="Book Poster" class="w-full rounded-lg shadow-md mb-4" />

                    <div class="w-full text-left">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Comentarios</h3>
                        <ul class="list-none space-y-2">
                            <li v-for="comment in comments" :key="comment.id" class="shadow-lg rounded-lg p-4 bg-white">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-800 mr-2">{{ comment.author }}</span>
                                    <span class="text-gray-600">{{ comment.date }}</span>
                                </div>
                                <div class="text-left mt-2 w-full">
                                    <p class="text-gray-700">{{ comment.content }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div v-if="!userLogged" class="w-full bg-gray-200 p-4 rounded-lg mt-4 text-center text-gray-600 text-sm font-bold">
                        Para realizar un comentario, debes iniciar sesión.
                    </div>
                    <form @submit.prevent="submit" class="w-full">
                        <div class="flex justify-between items-center w-full mt-4">
                            <textarea v-model="form.content" class="w-full border border-gray-200 rounded-md p-2" placeholder="Escribe un comentario..."></textarea>
                            <div v-if="userLogged">
                                <PrimaryButton class="ml-2">Enviar</PrimaryButton>
                            </div>
                            <div v-else>
                                <PrimaryButton class="ml-2 disabled:opacity-25" disabled>Enviar</PrimaryButton>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
