<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ArticleForm from '@/Components/ArticleForm.vue';

import { Head, usePage } from '@inertiajs/vue3';

const { props } = usePage();

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                
                    <div class="p-2 text-gray-900 dark:text-gray-100">
                        <ArticleForm :api-key="props.auth.user.api_key"></ArticleForm>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
