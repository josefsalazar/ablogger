<template>
    <Head title="Articles" />
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Articles
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div>
                            <h1 class="text-2xl font-semibold mb-4">Your Articles</h1>
                            
                            <table class="min-w-full table-auto">
                            <thead>
                                <tr>
                                <th class="px-4 py-2 text-left">Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="article in articles" :key="article.id">
                                <td class="px-4 py-2">
                                    <Link :href="`/articles/${article.id}`" class="text-blue-500 hover:underline">
                                        {{ article.title }}
                                    </Link>
                                </td>
                                </tr>
                            </tbody>
                            </table>

                            <div v-if="!articles.length" class="mt-4">
                            <p>No articles found.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </AuthenticatedLayout>

</template>

<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

// Props to receive the articles from the server
defineProps({
  articles: Array,
});
</script>