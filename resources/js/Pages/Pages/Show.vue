<template>
  <div class="container mx-auto p-4">
    <!-- Breadcrumbs with enhanced styling -->
    <!-- <nav aria-label="Breadcrumb" class="mb-6">
      <ol class="flex items-center space-x-2 text-sm">
        <li>
          <Link href="/" class="text-gray-500 hover:text-blue-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </Link>
        </li>
        <li v-for="(crumb, index) in breadcrumbs" :key="crumb.id" class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <Link 
            :href="'/' + crumb.path" 
            :class="[
              'ml-2',
              index === breadcrumbs.length - 1 
                ? 'font-semibold text-gray-800' 
                : 'text-blue-500 hover:text-blue-700 transition-colors'
            ]"
          >
            {{ crumb.title }}
          </Link>
        </li>
      </ol>
    </nav> -->
   <div class="mb-6">
      <Link href="/pages" class="flex items-center text-blue-600 hover:text-blue-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 00-1.41 0L3.29 8.3a1 1 0 000 1.41l5.3 5.3a1 1 0 101.41-1.42L6.41 10H17a1 1 0 100-2H6.41l3.3-3.3A1 1 0 0010 3z" clip-rule="evenodd" />
        </svg>
        Back to Pages
      </Link>
    </div>

    <!-- Page Content -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <div class="flex justify-between items-start mb-4">
        <h1 class="text-3xl font-bold">{{ page.title }}</h1>
        <div class="flex space-x-2">
          <Link 
            :href="`/pages/${page.id}/edit`" 
            class="flex items-center px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
            Edit
          </Link>
        </div>
      </div>
      <div class="prose max-w-none" v-html="page.content"></div>
    </div>
    
    <!-- Child Pages with Tree View -->
  <div v-if="page.children?.length" class="mt-8">
    <h2 class="text-xl font-bold mb-4 flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
      </svg>
      Child Pages
    </h2>
    
    <!-- Pass the current path to PageTree -->
    <div class="bg-white rounded-lg shadow p-4">
      <ChildPageTree :pages="page.children" :current-path="page.full_path" />
    </div>
  </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import ChildPageTree from '@/Components/ChildPageTree.vue'

defineProps({
  page: Object,
  breadcrumbs: Array
})
</script>