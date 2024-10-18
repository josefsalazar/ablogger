<template>
    <Head :title="article.title" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class=" text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ article.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div>
                            <div v-html="convertedContent" class="prose prose-lg dark:prose-invert max-w-none"></div>
                            <Link href="/articles" class="text-blue-500 hover:underline mt-4 inline-block">
                                Back to Articles
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

    import { defineProps, computed } from 'vue';
    import { Head, Link } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import showdown from 'showdown';

    const props = defineProps({
    article: Object,
    });

    const converter = new showdown.Converter();
    const convertedContent = computed(() => {
    return converter.makeHtml(props.article.content);
    });

</script>

<style>
ul, ol {
  padding-left: 1.5rem;
  margin-bottom: 1rem;
}

ul {
  list-style-type: disc; 
}

ol {
  list-style-type: decimal; 
}


h1 {
  font-size: 2.25rem; 
  font-weight: 700;
  margin-bottom: 1rem;
  margin-top: 1rem;
}

h2 {
  font-size: 1.875rem; 
  font-weight: 600;
  margin-bottom: 0.75rem;
    margin-top: 0.75rem;

}

h3 {
  font-size: 1.5rem; 
  font-weight: 600;
  margin-bottom: 0.75rem;
    margin-top: 0.75rem;

}

h4 {
  font-size: 1.25rem; 
  font-weight: 600;
  margin-bottom: 0.75rem;
}

h5 {
  font-size: 1.125rem; 
  font-weight: 500;
  margin-bottom: 0.5rem;
  margin-top: 0.75rem;
}

h6 {
  font-size: 1rem; 
  font-weight: 500;
  margin-bottom: 0.5rem;
  margin-top: 0.5rem;
}


blockquote {
  font-style: italic;
  border-left: 4px solid #ddd; 
  padding-left: 1rem;
  color: #fff;
  margin: 1.5rem 0;
  padding: 0.75rem 1.5rem;
}


blockquote p {
  margin: 0; 
}
</style>