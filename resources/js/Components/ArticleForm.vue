<template>
  <div class="my-4">
    <!-- <h1 class="text-2xl font-semibold text-center dark:text-white">Article</h1> -->
  
      <!-- Check if API key exists -->
      <p v-if="!hasApiKey" class="text-red-500 text-center">
        You need to set your API key in your profile to generate articles.
      </p>
  </div>
  <div style="display: grid; grid-template-columns: 30% 70%; gap: 1rem">
  <div class="p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
    <!-- Toggle between manual input and JSON input -->
    <div class="mb-4">
      <label class="block text-gray-700 dark:text-gray-300" for="input-mode">Input Mode</label>
      <select id="input-mode" v-model="inputMode" class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
        <option value="manual">Manual</option>
        <option value="json">JSON</option>
      </select>
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
    <div class="mb-4 flex flex-col space-y-4">
      <div class="w-100">
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

      <div class="w-100">
        <label class="block text-gray-700 dark:text-gray-300" for="language">Language</label>
        <select
          id="language"
          v-model="form.language"
          class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        >
          <option value="english">English</option>
          <option value="spanish">Spanish</option>
          <option value="spanish">French</option>
          <option value="spanish">German</option>
          <option value="spanish">Portuguese</option>
          <option value="spanish">Italian</option>
          <option value="spanish">Russian</option>
          <option value="spanish">Norwegian</option>
          <option value="spanish">Icelandic</option>
        </select>
      </div>

      <div class="w-100">
        <label class="block text-gray-700 dark:text-gray-300" for="model">Model</label>
        <select
          id="model"
          v-model="form.model"
          class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        >
          <option value="gpt-3.5-turbo">GPT 3.5</option>
          <option value="gpt-4">GPT 4</option>
          <option value="gpt-4-32k">GPT 4 32k</option>
        </select>
      </div>
      <div class="w-100">
        <label class="block text-gray-700 dark:text-gray-300" for="model">Format</label>
        <select
          id="format"
          v-model="form.format"
          class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        >
          <option value="Blog">Blog</option>
          <option value="Lista">Lista</option>
          <option value="Guía">Guía</option>
          <option value="Tutorial">Tutorial</option>
          <option value="Comparativo">Comparativo</option>
        </select>
      </div>
      <div class="w-100 flex flex-row space-x-4">
        <label class="block text-gray-700 dark:text-gray-300" for="takeaways">
          Key Takeaways
        </label>
        <input
          type="checkbox"
          id="takeaways"
          v-model="form.takeaways"
          class="mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-300 dark:border-blue-600 dark:text-gray-700"
        />
      </div>
      <div class="w-100">
        <label class="block text-gray-700 dark:text-gray-300" for="perspective">Perspective</label>
        <select
          id="perspective"
          v-model="form.perspective"
          class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        >
          <option value="first_person">Primera persona (Yo/Nosotros)</option>
          <option value="second_person">Segunda persona (Tú/Usted)</option>
          <option value="third_person">Tercera persona (Él/Ella/Ellos)</option>
        </select>
      </div>
      
      <div class="w-100">
        <label class="block text-gray-700 dark:text-gray-300" for="text-intention">Text Intention</label>
        <select
          id="text-intention"
          v-model="form.textIntention"
          class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        >
          <option value="Informativa">Informativa</option>
          <option value="Persuasiva">Persuasiva</option>
          <option value="Narrativa">Narrativa</option>
          <option value="Descriptiva">Descriptiva</option>
          <option value="Exhortativa">Exhortativa</option>
          <option value="Expositiva">Expositiva</option>
          <option value="Argumentativa">Argumentativa</option>
          <option value="Instructiva">Instructiva</option>
          <option value="Emotiva">Emotiva</option>
          <option value="Apreciativa">Apreciativa</option>
          <option value="Opinionado">Opinionado</option>
        </select>
      </div>


    </div>

    <!-- Article Length Slider -->
    <div class="mb-4">
      <label class="block text-gray-700 dark:text-gray-300" for="article-length">H2 Length</label>
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
    <div class="mb-4">
      <label class="block text-gray-700 dark:text-gray-300" for="h3-length">H3 Length</label>
      <input
        type="range"
        id="h3-length"
        v-model="form.h3Length"
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
  </div>
  <div class="p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
    

    

    <!-- Keyword Input -->
    <div class="mb-4">
      <label class="block text-gray-700 dark:text-gray-300" for="keyword">Title</label>
      <input
        type="text"
        id="keyword"
        v-model="form.keyword"
        class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        placeholder="Enter title"
      />
    </div>
    <!-- JSON Input Mode -->
    <div v-if="inputMode === 'json'" class="mb-4">
      <label class="block text-gray-700 dark:text-gray-300" for="json-input">Paste JSON Here</label>
      <textarea
        id="json-input"
        v-model="jsonInput"
        class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
        rows="8"
        placeholder="Paste your JSON structure"
      ></textarea>

      <button
        @click="processJsonInput"
        class="mt-4 px-4 py-2 bg-white text-black rounded-md hover:bg-gray-200 focus:outline-none"
      >
        Process JSON
      </button>
    </div>
    <!-- Dynamic Header Blocks for Manual Input Mode -->
    <div class="mb-4">
      <h2 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Headers</h2>
      <div v-for="(header, index) in form.headers" :key="index" class="mb-4 border-b border-gray-700 pb-4">
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
            class="px-5 py-2 bg-white h-full text-black rounded-md hover:bg-gray-200 focus:outline-none"
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

    

    

    

    <!-- Rich Text Editor -->
    <div class="mb-4">
      <label class="block text-gray-700 dark:text-gray-300" for="content">Content</label>
      <div ref="editor" class="quill-editor"></div>
    </div>
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

const hasApiKey = computed(() => !!props.apiKey);

const inputMode = ref('manual');
const jsonMode = ref(false); // Estado para activar o desactivar el modo JSON

const jsonInput = ref('');

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
  article_length: 2,
  h3Length: 2,
  content: '',
  model: 'gpt-3.5-turbo',
  format: 'Blog',
  takeaways: false,
  perspective: 'first_person',
  textIntention: 'Informativa'
});

const editor = ref(null);
const loading = ref(false);
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

const processJsonInput = () => {
    try {
        const parsedJson = JSON.parse(jsonInput.value);

        const validateJsonStructure = (jsonData) => {
            return jsonData.every(item => {
                return typeof item.h2 === 'string' && Array.isArray(item.h3);
            });
        };

        if (!validateJsonStructure(parsedJson)) {
            throw new Error('Invalid JSON structure');
        }

        parsedJson.forEach(header => {
            if (!header.hasOwnProperty('h3')) {
                header.h3 = [];
            }
        });

        form.headers = parsedJson;
    } catch (error) {
        console.error('Error processing JSON input:', error);
    }
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
    loading.value = true; 
    let headersContent = '';

    form.headers.forEach((header) => {
      headersContent += `## ${header.h2}\n\n`;
      header.h3.forEach((subheader) => {
        headersContent += `### ${subheader}\n\n`;
      });
    });

    const response = await axios.post('/generate-article', {
      ...form,
      
    });

    let generatedContent = response.data.content;
    const htmlContent = converter.makeHtml(generatedContent);
    quill.clipboard.dangerouslyPasteHTML(0, htmlContent);

    editor.value.scrollIntoView({ behavior: 'smooth', block: 'start' });

  } catch (error) {
    console.error('Error generating article:', error);
  } finally {
    loading.value = false;
  }
};
</script>


<style >
.text-editor .ql-container {
  min-height: 200px;
}

.ql-toolbar {
  background-color: #2d3748;
  color: #e2e8f0;
}

.ql-container {
  background-color: #2d3748;
  color: #e2e8f0;
}

.ql-editor {
    font-family: 'Arial', sans-serif;
    line-height: 1.6;
    color: #fff;
    font-size: 16px;
    padding: 20px;
}

.ql-editor h2 {
    font-size: 24px;
    font-weight: bold;
    color: #ff0000;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
}

.ql-editor h3 {
    font-size: 20px;
    font-weight: bold;
    color: #34495e;
    margin-top: 1em;
    margin-bottom: 0.3em;
}

.ql-editor p {
    margin-bottom: 1em;
    text-align: justify;
}

.ql-editor ul, .ql-editor ol {
    margin-left: 20px;
    padding-left: 20px;
    list-style-type: disc;
}

.ql-editor li {
    margin-bottom: 0.5em;
    font-size: 16px;
}

.ql-snow .ql-editor h2 {
    color: #fff !important;
}

.ql-editor h3 {
    color: #fff;
}

.ql-editor p {
    text-align: justify;
    font-size: 16px;
    margin: 0 0 15px 0;
}

.ql-editor ul {
    padding-left: 1.5em;
    list-style-type: disc;
}

.ql-editor ol {
    padding-left: 1.5em;
    list-style-type: decimal;
}

.ql-editor li {
    margin-bottom: 0.5em;
}

.ql-snow .ql-editor code {
  background-color: #34495e;
}
</style>
