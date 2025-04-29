<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import LeadStats from '@/Components/LeadStats.vue';

defineProps({
    leadStatusCounts: Object,
});
</script>

<template>
    <AppLayout>
        <Head title="Dashboard" />
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                
                <div class="mt-6">
                    <LeadStats :status-counts="leadStatusCounts" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>