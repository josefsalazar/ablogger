<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    apiKey: {
        type: String,
        default: '',
    },
});

const form = useForm({
    apiKey: props.apiKey,
});

const apiKeyVisible = ref(false);

const saveApiKey = () => {
    form.post(route('profile.update-api-key'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                API Key Settings
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Manage your OpenAI API key here.
            </p>
        </header>

        <!-- Single input to handle both viewing and updating the API Key -->
        <div>
            <InputLabel for="api-key" value="API Key" />

            <div class="mt-1 relative">
                <TextInput
                    id="api-key"
                    type="text"
                    v-model="form.apiKey"
                    :value="apiKeyVisible ? form.apiKey : form.apiKey.replace(/.(?=.{4})/g, '*')"
                    class="w-full pr-10"
                />
                <button
                    type="button"
                    @click="apiKeyVisible = !apiKeyVisible"
                    class="absolute inset-y-0 right-0 px-4 py-2 text-gray-500 dark:text-gray-400"
                >
                    {{ apiKeyVisible ? 'Hide' : 'Show' }}
                </button>
            </div>

            <InputError :message="form.errors.apiKey" class="mt-2" />
        </div>

        <div class="flex items-center mt-4">
            <PrimaryButton
                :disabled="form.processing"
                @click="saveApiKey"
            >
                Save
            </PrimaryButton>
        </div>
    </section>
</template>
