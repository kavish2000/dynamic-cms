<template>
  <div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Pages</h1>

      <!-- Show "Add Page" button only when there are pages -->
      <Link 
        v-if="pages.length > 0"
        href="/pages/create" 
        class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H3a1 1 0 110-2h6V3a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Add Page
      </Link>
    </div>

    <!-- Page Tree Component -->
    <div class="bg-white rounded-lg shadow p-6">
      <PageTree v-if="pages.length" :pages="rootPages" />

      <div v-else class="text-gray-500 text-center py-6 flex flex-col items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <p>No pages found. Create your first page to get started.</p>
        <Link 
          href="/pages/create"
          class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors"
        >
          Create First Page
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import PageTree from '@/Components/PageTree.vue'

const props = defineProps({
  pages: {
    type: Array,
    default: () => []
  }
})

// Filter out only root-level pages
const rootPages = computed(() => {
  return props.pages.filter(page => !page.parent_id)
})
</script>
