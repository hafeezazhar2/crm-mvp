<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppSidebar from '@/Components/AppSidebar.vue';
import AppHeader from '@/Components/AppHeader.vue';

const sidebarOpen = ref(false);
</script>

<template>
    <Head>
        <title><slot name="title" /> - CRM MVP</title>
    </Head>

    <div class="min-h-screen bg-gray-50">
        <AppSidebar :sidebarOpen="sidebarOpen" @close-sidebar="sidebarOpen = false" />
        
        <div class="lg:pl-72">
            <AppHeader @toggle-sidebar="sidebarOpen = !sidebarOpen" />
            
            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>