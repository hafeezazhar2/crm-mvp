<template>
    <div class="flex items-center justify-between px-4 py-3 sm:px-6">
      <div class="flex-1 flex justify-between sm:hidden">
        <Link
          :href="links.prev"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': !links.prev }"
          :disabled="!links.prev"
        >
          Previous
        </Link>
        <Link
          :href="links.next"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': !links.next }"
          :disabled="!links.next"
        >
          Next
        </Link>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ meta.from }}</span> to <span class="font-medium">{{ meta.to }}</span> of <span class="font-medium">{{ meta.total }}</span> results
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <template v-for="(link, index) in links.links">
              <Link
                v-if="link.url"
                :key="index"
                :href="link.url"
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                :class="{
                  'z-10 bg-primary-50 border-primary-500 text-primary-600': link.active,
                  'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active && index !== 0 && index !== links.links.length - 1,
                  'rounded-l-md': index === 0,
                  'rounded-r-md': index === links.links.length - 1
                }"
                v-html="link.label"
              />
            </template>
          </nav>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  
  defineProps({
    links: {
      type: Object,
      required: true
    },
    meta: {
      type: Object,
      required: true
    }
  });
  </script>