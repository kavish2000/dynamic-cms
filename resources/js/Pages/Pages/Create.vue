<template>
  <div class="max-w-3xl mx-auto p-6">
    <!-- Back Link -->
    <div class="mb-6">
      <Link href="/pages" class="flex items-center text-blue-600 hover:text-blue-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 00-1.41 0L3.29 8.3a1 1 0 000 1.41l5.3 5.3a1 1 0 101.41-1.42L6.41 10H17a1 1 0 100-2H6.41l3.3-3.3A1 1 0 0010 3z" clip-rule="evenodd" />
        </svg>
        Back to Pages
      </Link>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
      <h1 class="text-2xl font-semibold text-gray-900 mb-6">Create New Page</h1>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Parent Page Dropdown -->
         <div>
          <label class="block text-sm font-semibold text-gray-700">Parent Page</label>
          <select v-model="form.parent_id" class="mt-2 w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            <option :value="null">Root Level</option>
            <option v-for="page in flattenedPages" :key="page.id" :value="page.id">
              {{ page.parent_title }}
            </option>
          </select>
        </div>


        <!-- Title -->
        <div>
          <label class="block text-sm font-semibold text-gray-700">Title</label>
          <input 
            type="text" 
            v-model="form.title"
            class="mt-2 w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
            required
          >
        </div>

        <!-- Slug -->
        <div>
          <label class="block text-sm font-semibold text-gray-700">Slug</label>
          <input 
            type="text" 
            v-model="form.slug"
            @input="validateSlug"
            class="mt-2 w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
            placeholder="URL-friendly name (e.g., page-1)"
            required
          >
          <p v-if="errors.slug" class="text-red-600 text-xs mt-1">{{ errors.slug }}</p>
        </div>

        

        <!-- Content -->
        <div>
          <label class="block text-sm font-semibold text-gray-700">Content</label>
          <textarea 
            v-model="form.content"
            rows="6"
            class="mt-2 w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Write your content here..."
            required
          ></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 pt-4">
          <Link href="/pages" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
            Cancel
          </Link>
          <button 
            type="submit"
            class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition flex items-center"
            :disabled="processing"
          >
            <svg v-if="processing" class="w-4 h-4 mr-2 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0116 0"></path>
            </svg>
            {{ processing ? 'Creating...' : 'Create Page' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  pages: {
    type: Array,
    default: () => []
  }
})

const processing = ref(false)

const errors = ref({
  slug: ''
})

const form = ref({
  parent_id: null,
  slug: '',
  title: '',
  content: ''
})

// Slugify function
const generateSlug = (text) => {
  return text
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9\s-]/g, '') // Remove invalid chars
    .replace(/\s+/g, '-') // Replace spaces with hyphens
    .replace(/-+/g, '-') // Remove multiple hyphens
}

// Watch for title changes and update slug
watch(() => form.value.title, (newTitle) => {
  if (newTitle) {
    form.value.slug = generateSlug(newTitle)
  }
})

// Slug validation in real-time
const validateSlug = () => {
  const slugRegex = /^[a-z0-9]+(?:-[a-z0-9]+)*$/
  if (!slugRegex.test(form.value.slug)) {
    errors.value.slug = 'Slug must be URL-friendly (lowercase, numbers, hyphens only).'
  } else {
    errors.value.slug = ''
  }
}

const flattenedPages = computed(() => {
  return props.pages.map(page => ({
    ...page,
    parent_title: page.parent_title !== page.title ? page.parent_title + ' -> ' + page.title : page.title
  }));
});


const submit = () => {
  validateSlug()
  
  processing.value = true
  router.post('/pages', form.value, {
    onSuccess: () => {
      processing.value = false
      router.visit('/pages')
    },
    onError: (validationErrors) => {
      processing.value = false
      errors.value = validationErrors
    }
  })
}
</script>

