<template>
  <ul class="space-y-2">
    <li v-for="page in pages" :key="page.id" class="relative">
      <div class="flex items-center">
        <span 
          v-if="page.children?.length" 
          @click="toggleExpand(page)"
          class="mr-2 w-5 h-5 flex items-center justify-center cursor-pointer hover:text-blue-600 transition-colors"
        >
          <svg v-if="!expanded[page.id]" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </span>
        <span v-else class="w-5 mr-2"></span>
        
        <Link :href="page.full_path" class="hover:text-blue-600 transition-colors">
          {{ page.title }}
        </Link>
      </div>
      
      <div v-if="page.children?.length && expanded[page.id]" class="pl-6 mt-2">
        <PageTree :pages="page.children" />
      </div>
    </li>
  </ul>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'

const props = defineProps({
  pages: Array
})

const expanded = reactive({})

const toggleExpand = (page) => {
  expanded[page.id] = !expanded[page.id]
}
</script>