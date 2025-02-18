<template>
  <div class="page-tree">
    <ul class="space-y-2">
      <li v-for="page in pages" :key="page.id" class="relative">
        <div class="flex items-center space-x-2 py-1">
          <button 
            v-if="page.children?.length" 
            @click="toggleExpand(page)" 
            class="w-6 h-6 flex items-center justify-center focus:outline-none"
          >
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              viewBox="0 0 20 20" 
              fill="currentColor" 
              class="w-4 h-4 transition-transform duration-200"
              :class="{'rotate-90': expanded.includes(page.id)}"
            >
              <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
            </svg>
          </button>
          <span v-else class="w-6"></span>
          
          <Link :href="'/' + getPagePath(page)" class="font-medium hover:text-blue-500 transition-colors">
            {{ page.title }}
          </Link>
          
          <div class="ml-auto flex items-center space-x-1">
            <button 
              @click="editPage(page)" 
              class="p-1 text-gray-500 hover:text-blue-500 transition-colors"
              title="Edit page"
            >
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
              </svg>
            </button>
            
            <button 
              @click="openDeleteModal(page)" 
              class="p-1 text-gray-500 hover:text-red-500 transition-colors"
              title="Delete page"
            >
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>

        <div 
          v-if="expanded.includes(page.id) && page.children?.length"
          class="pl-6 mt-1 border-l-2 border-gray-200 ml-3"
        >
          <PageTree
            :pages="page.children"
            :parent-path="getPagePath(page)"
          />
        </div>
      </li>
    </ul>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold">Confirm Deletion</h2>
        <p class="mt-2 text-gray-600">Are you sure you want to Delete "<strong>{{ pageToDelete?.title }}</strong>"? Deleting this page will also remove all its child pages.</p>
        <div class="mt-4 flex justify-end space-x-2">
          <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
          <button @click="deletePage" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  pages: Array,
  parentPath: {
    type: String,
    default: ''
  }
})

// Expand all pages with children by default
const expanded = ref(props.pages.flatMap(page => page.children?.length ? [page.id] : []))

const showDeleteModal = ref(false)
const pageToDelete = ref(null)

const toggleExpand = (page) => {
  const index = expanded.value.indexOf(page.id)
  if (index === -1) {
    expanded.value.push(page.id)
  } else {
    expanded.value.splice(index, 1)
  }
}

const getPagePath = (page) => {
  return props.parentPath ? `${props.parentPath}/${page.slug}` : page.slug
}

const openDeleteModal = (page) => {
  pageToDelete.value = page
  showDeleteModal.value = true
}

const deletePage = () => {
  if (pageToDelete.value) {
    router.post(`/pages/${pageToDelete.value.id}`, { _method: 'delete' })
    showDeleteModal.value = false
  }
}

const editPage = (page) => {
  router.get(`/pages/${page.id}/edit`)
}
</script>
