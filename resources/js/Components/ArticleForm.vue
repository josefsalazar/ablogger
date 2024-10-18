<template>
  <div class="p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
    <h1 class="text-2xl font-semibold text-center mb-8 dark:text-white">Custom Article Workflow</h1>
    <!-- Check if API key exists -->
    <p v-if="!hasApiKey" class="text-red-500 text-center">
      You need to set your API key in your profile to generate articles.
    </p>
    <!-- Keyword Input -->
    <div class="mb-4">
      <label class="block text-gray-700 dark:text-gray-300" for="keyword">Keyword</label>
      <input
        type="text"
        id="keyword"
        v-model="form.keyword"
        class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        placeholder="Enter a keyword"
      />
    </div>

    <!-- Dynamic Header Blocks -->
    <div class="mb-4">
      <h2 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Headers</h2>
      <div v-for="(header, index) in form.headers" :key="index" class="mb-4 border-b pb-4">
        <label class="block text-gray-700 dark:text-gray-300">H2 Title</label>
        <div class="flex items-center space-x-2">
          <input
            :value="header.h2"
            @input="updateHeader(index, $event.target.value)"
            type="text"
            class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
            placeholder="Enter H2 title"
          />
          <!-- Button to remove H2 and all its H3 -->
          <button
            @click="removeHeaderBlock(index)"
            type="button"
            class=" px-5 py-2 bg-white h-full text-black rounded-md hover:bg-gray-200 focus:outline-none"
          >
            Remove
          </button>
        </div>

        <label class="block text-gray-700 dark:text-gray-300 mt-4">Subheaders (H3)</label>
        <div v-for="(subheader, subIndex) in header.h3" :key="subIndex" class="ml-4 mt-2 flex items-center">
          <input
            :value="subheader"
            @input="updateSubheader(index, subIndex, $event.target.value)"
            type="text"
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
            placeholder="Enter H3 subheader"
          />

          <!-- Button to remove H3 -->
          <button
            @click="removeSubheader(index, subIndex)"
            type="button"
            class="ml-2 px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none"
          >
            Remove
          </button>
        </div>

        <!-- Button to add a new H3 under this H2 -->
        <button
          @click="addSubheader(index)"
          type="button"
          class="mt-2 px-4 py-2 bg-white text-black rounded-md hover:bg-gray-200 focus:outline-none"
        >
          Add H3
        </button>
      </div>

      <!-- Button to add a new H2 block -->
      <button
        @click="addHeaderBlock"
        type="button"
        class="mt-4 px-4 py-2 bg-white text-black rounded-md hover:bg-gray-200 focus:outline-none"
      >
        Add H2
      </button>
    </div>

    <!-- Additional Keywords -->
    <div class="mb-4">
      <label class="block text-gray-700 dark:text-gray-300" for="additional-keywords">Keywords (Additional)</label>
      <textarea
        id="additional-keywords"
        v-model="form.additional_keywords"
        class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        rows="4"
        placeholder="Enter additional keywords"
      ></textarea>
    </div>

    <!-- Style and Language Dropdowns -->
    <div class="mb-4 flex space-x-4">
      <div class="w-1/2">
        <label class="block text-gray-700 dark:text-gray-300" for="style">Style</label>
        <select
          id="style"
          v-model="form.style"
          class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        >
          <option value="professional">Professional</option>
          <option value="casual">Casual</option>
        </select>
      </div>

      <div class="w-1/2">
        <label class="block text-gray-700 dark:text-gray-300" for="language">Language</label>
        <select
          id="language"
          v-model="form.language"
          class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        >
          <option value="english">English</option>
          <option value="spanish">Spanish</option>
        </select>
      </div>
    </div>

    <!-- Article Length Slider -->
    <div class="mb-4">
      <label class="block text-gray-700 dark:text-gray-300" for="article-length">Article Length</label>
      <input
        type="range"
        id="article-length"
        v-model="form.article_length"
        min="1"
        max="5"
        step="1"
        class="w-full"
      />
    </div>

    <!-- Submit Button -->
    <div class="text-center">
    <button
      @click="createArticle"
      class="px-6 py-2 my-8 bg-blue-500 w-full text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800"
      :disabled="!hasApiKey || loading" 
    >
      <span v-if="loading">Processing...</span>
      <span v-else>Create Article</span>
    </button>
  </div>

    <!-- Rich Text Editor -->
    <div class="mb-4">
      <label class="block text-gray-700 dark:text-gray-300" for="content">Content</label>
      <div ref="editor"></div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue';
import axios from 'axios';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import showdown from 'showdown';

// Assuming the API key is passed as a prop to this component
const props = defineProps({
  apiKey: {
    type: String,
    default: '',
  },
});

// Check if the API key exists
const hasApiKey = computed(() => !!props.apiKey);

const converter = new showdown.Converter({
  simpleLineBreaks: true,
  noHeaderId: true,
  ghCompatibleHeaderId: true,
  parseImgDimensions: true,
});

const form = reactive({
  keyword: '',
  headers: [{ h2: '', h3: [] }],
  additional_keywords: '',
  style: 'professional',
  language: 'english',
  article_length: 50,
  content: '',
});

const editor = ref(null);
const loading = ref(false); // Loading state for disabling the button
let quill;

const addHeaderBlock = () => {
  form.headers.push({ h2: '', h3: [] });
};

const addSubheader = (index) => {
  form.headers[index].h3.push('');
};

const updateHeader = (index, value) => {
  form.headers[index].h2 = value;
};

const updateSubheader = (headerIndex, subheaderIndex, value) => {
  form.headers[headerIndex].h3[subheaderIndex] = value;
};

const removeHeaderBlock = (index) => {
  form.headers.splice(index, 1);
};

const removeSubheader = (headerIndex, subheaderIndex) => {
  form.headers[headerIndex].h3.splice(subheaderIndex, 1);
};

onMounted(() => {
  quill = new Quill(editor.value, {
    theme: 'snow',
    modules: {
      toolbar: [
        ['bold', 'italic', 'underline'],
        [{ header: 1 }, { header: 2 }],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['clean'],
      ],
    },
  });

  quill.on('text-change', () => {
    form.content = quill.root.innerHTML;
  });
});

const createArticle = async () => {
  try {
    loading.value = true; // Set loading state to true
    let headersContent = '';

    // Recorrer los headers (H2 y H3)
    form.headers.forEach((header) => {
      headersContent += `## ${header.h2}\n\n`;
      header.h3.forEach((subheader) => {
        headersContent += `### ${subheader}\n\n`;
      });
    });

    // Send request to generate article
    const response = await axios.post('/generate-article', {
      ...form,
    });

    let generatedContent = response.data.content;
    const htmlContent = converter.makeHtml(generatedContent);
    quill.clipboard.dangerouslyPasteHTML(0, htmlContent);
  } catch (error) {
    console.error('Error generating article:', error);
  } finally {
    loading.value = false; // Reset loading state after request completes
  }
};
</script>


<style scoped>
.text-editor .ql-container {
  min-height: 200px;
}

.dark .ql-toolbar {
  background-color: #2d3748;
  color: #e2e8f0;
}

.dark .ql-container {
  background-color: #2d3748;
  color: #e2e8f0;
}
</style>
