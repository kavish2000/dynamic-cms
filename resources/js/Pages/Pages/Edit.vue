<template>
  <div class="max-w-3xl mx-auto p-6">

    <div class="mb-6">
      <Link href="/pages" class="flex items-center text-blue-600 hover:text-blue-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 00-1.41 0L3.29 8.3a1 1 0 000 1.41l5.3 5.3a1 1 0 101.41-1.42L6.41 10H17a1 1 0 100-2H6.41l3.3-3.3A1 1 0 0010 3z" clip-rule="evenodd" />
        </svg>
        Back to Pages
      </Link>
    </div>

    <!-- Title and form container -->
    <div class="bg-white rounded-lg shadow-md p-6">
      <h1 class="text-2xl font-semibold text-gray-900 mb-6">Edit Page: {{ page.title }}</h1>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-5">
        <div class="space-y-6">
          
          <!-- Parent Page Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Parent Page</label>
            <select 
              v-model="form.parent_id" 
              class="mt-2 w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
            >
              <option :value="null">Root Level</option>
              <option 
                v-for="availablePage in availableParents" 
                :key="availablePage.id" 
                :value="availablePage.id"
              >
                {{ availablePage.title }}
              </option>
            </select>
          </div>

           <!-- Title Field -->
          <div>
            <label class="block text-sm font-semibold text-gray-700">Title</label>
            <input 
              type="text" 
              v-model="form.title"
              class="mt-2 w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
              required
            >
          </div>

          <!-- Slug Field -->
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

          <!-- Content Field -->
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

          <!-- Actions (Cancel and Submit) -->
          <div class="flex justify-between items-center space-x-4 mt-6">
            <Link 
              :href="`/pages/`" 
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
            >
              Cancel
            </Link>
            <button 
              type="submit"
              class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
            >
              Update Page
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  page: Object,
  pages: Array
})

const form = ref({
  parent_id: props.page.parent_id ?? null, // Ensure null for root pages
  slug: props.page.slug,
  title: props.page.title,
  content: props.page.content
})

// Validation state for the slug
const errors = ref({
  slug: ''
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

// URL-friendly slug regex
const slugRegex = /^[a-z0-9]+(?:-[a-z0-9]+)*$/

// Slug validation in real-time
const validateSlug = () => {
  if (!slugRegex.test(form.value.slug)) {
    errors.value.slug = 'Slug must be URL-friendly (lowercase, numbers, hyphens only).'
  } else {
    errors.value.slug = ''
  }
}

// Ensure that we only allow non-descendant pages as parents
const availableParents = computed(() => {
  // Function to check if a page is a descendant of the current page
  const isDescendant = (page, parentId) => {
    if (!page.children) return false;
    return page.children.some(child => child.id === parentId || isDescendant(child, parentId));
  };

  // Find the parent of the current page
  const currentPageParent = props.pages.find(page => page.id === props.page.parent_id);

  // Filter pages to get only the parent's siblings and root-level pages
  return props.pages.filter(page => {
    return (
      // Allow root-level pages (parent_id is null)
      page.parent_id === null ||
      // Allow pages with the same parent (siblings) except for the current page itself
      (currentPageParent && page.parent_id === currentPageParent.parent_id && page.id !== props.page.id) ||
      // Allow pages that are not descendants of the current page
      (page.id !== props.page.id && !isDescendant(page, props.page.id))
    );
  });
});


const submit = () => {
  // Validate the slug field
  validateSlug();
  
  if (errors.value.slug) return;

  router.put(`/pages/${props.page.id}`, form.value, {
    onFinish: () => {
      router.visit('/pages'); // Redirect to /pages after success
    },
    onError: (errors) => {
      console.log(errors);
    }
  });
};

</script>


