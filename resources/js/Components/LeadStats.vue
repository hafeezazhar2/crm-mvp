<template>
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-lg font-medium text-gray-900 mb-4">Lead Statistics</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div 
          v-for="(count, status) in statusCounts" 
          :key="status"
          class="border rounded-lg p-4 text-center"
          :class="statusColorClasses(status)"
        >
          <div class="text-sm font-medium text-gray-500 capitalize">
            {{ status }}
          </div>
          <div class="mt-1 text-2xl font-semibold">
            {{ count || 0 }}
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  
  const props = defineProps({
    statusCounts: {
      type: Object,
      required: true
    }
  });
  
  const statusColorClasses = (status) => {
    const baseClasses = 'border-';
    switch (status) {
      case 'new':
        return `${baseClasses}blue-200 bg-blue-50 text-blue-800`;
      case 'contacted':
        return `${baseClasses}purple-200 bg-purple-50 text-purple-800`;
      case 'qualified':
        return `${baseClasses}green-200 bg-green-50 text-green-800`;
      case 'lost':
        return `${baseClasses}red-200 bg-red-50 text-red-800`;
      case 'won':
        return `${baseClasses}emerald-200 bg-emerald-50 text-emerald-800`;
      default:
        return `${baseClasses}gray-200 bg-gray-50 text-gray-800`;
    }
  };
  </script>