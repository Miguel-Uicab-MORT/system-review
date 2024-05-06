<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextAreaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    userAuth: {
        type: Object,
    },
    userLogged: {
        type: Boolean,
        required: true
    },
});

const form = useForm({
    title: '',
    synopsis: '',
    release_date: '',
    review: '',
    poster: '',
});

const submit = () => {
    const $page = usePage();

    const formData = new FormData();

    formData.append('title', form.title);
    formData.append('synopsis', form.synopsis);
    formData.append('release_date', form.release_date);
    formData.append('review', form.review);
    formData.append('poster', form.poster);
    formData.append('user_id', $page.props.userAuth.id);

    axios.post('http://reviews.test/api/review/create-review', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        responseType: 'json'
    }).then(response => {
        if (response.status === 200) {
            form.reset();
            window.location.href = '/';
        } else if (response.status === 400) {

        } else {
        }
    }).catch(error => {
    });
};

</script>

<template>
    <Head title="Create Review" />

    <AuthenticatedLayout :userLogged="userLogged" :userAuth="userAuth">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Review</h2>
        </template>

        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 py-10">
            <div class="bg-white shadow-xl sm:rounded-lg">
                <div class="bg-gray-200 px-4 py-3 sm:rounded-t-lg">
                    <h3 class="text-lg font-semibold text-gray-800">Movie Information</h3>
                </div>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
                        <div class="md:col-span-2">
                            <InputLabel for="title" value="Title" />
                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.title"
                                required
                                autofocus
                                placeholder="Enter movie title"
                                autocomplete="title"
                            />
                        </div>

                        <div>
                            <InputLabel for="release_date" value="Release Date" />
                            <TextInput
                                id="release_date"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="form.release_date"
                                required
                                autofocus
                                autocomplete="release_date"
                            />
                        </div>

                        <div>
                            <InputLabel for="poster" value="Poster" />
                            <input
                                id="poster"
                                type="file"
                                class="mt-1 block w-full"
                                accept="image/*"
                                required
                                autofocus
                                @change="form.poster = $event.target.files[0]"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <InputLabel for="synopsis" value="Synopsis" />
                            <TextArea
                                id="synopsis"
                                type="text"
                                rows="5"
                                class="mt-1 block w-full"
                                v-model="form.synopsis"
                                required
                                autofocus
                                placeholder="Enter movie synopsis"
                                autocomplete="synopsis"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <InputLabel for="review" value="Review" />
                            <TextArea
                                id="review"
                                type="text"
                                rows="5"
                                class="mt-1 block w-full"
                                v-model="form.review"
                                required
                                autofocus
                                autocomplete="review"
                            />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 bg-gray-200 px-4 py-3">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Add Review
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
