<script setup>

import {onMounted, ref} from "vue";

const listReviews = ref([]);

function getReviews() {
    axios.get('http://reviews.test/api/reviews')
        .then(response => {
            listReviews.value = response.data;
        })
        .catch(error => {
            console.error(error);
        });
}

onMounted(() => {
    getReviews();
})

</script>

<template>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
            <div v-for="(review, index) in listReviews" :key="review.id">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <a class="w-full" :href="route('review.show', {id: review.id})">
                        <div v-if="review.url_poster" class="pb-5/6">
                            <img class="h-full w-full object-cover" :src="review.url_poster" :alt="review.title_movie">
                        </div>
                        <div class="flex justify-between items-center mb-1 px-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ review.title_movie }}</h3>
                            <p class="text-sm text-gray-500">{{ review.release_date }}</p>
                        </div>
                        <div class="mb-1 px-4 text-sm text-gray-500 text-justify">
                            <p>{{ review.synopsis }}</p>
                        </div>
                        <div class="mb-1 px-4 text-right">
                            <span class="text-sm font-bold text-gray-500">{{ review.author }}</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
