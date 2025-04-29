<template>
    <span 
      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
      :class="statusClasses"
    >
      {{ status }}
    </span>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  
  const props = defineProps({
    status: {
      type: String,
      required: true
    }
  });
  
  const statusClasses = computed(() => {
    switch (props.status) {
      case 'new':
        return 'bg-blue-100 text-blue-800';
      case 'contacted':
        return 'bg-purple-100 text-purple-800';
      case 'qualified':
        return 'bg-green-100 text-green-800';
      case 'lost':
        return 'bg-red-100 text-red-800';
      case 'won':
        return 'bg-emerald-100 text-emerald-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  });
  </script>