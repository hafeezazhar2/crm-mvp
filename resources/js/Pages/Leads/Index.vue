<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import LeadStatusBadge from '@/Components/LeadStatusBadge.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { FunnelIcon, MagnifyingGlassIcon } from '@heroicons/vue/20/solid';
import { route } from 'ziggy-js';

//import { Ziggy } from 'ziggy-js';
//console.log('Available routes:', Ziggy.routes);


const props = defineProps({
    leads: Object,
    statusCounts: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

watch([search, statusFilter], debounce(([searchValue, statusValue]) => {
    router.get(route('leads.index'), {
        search: searchValue,
        status: statusValue,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));
</script>

<template>
    <AppLayout>
        <Head title="Leads" />
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-900">Leads</h1>
                    <Link
                        :href="route('leads.create')"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring focus:ring-primary-300 disabled:opacity-25 transition"
                    >
                        Add Lead
                    </Link>
                </div>
                
                <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="relative rounded-md shadow-sm flex-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    v-model="search"
                                    type="text"
                                    class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                    placeholder="Search leads..."
                                />
                            </div>
                            
                            <div class="flex items-center">
                                <FunnelIcon class="h-5 w-5 text-gray-400 mr-2" />
                                <select
                                    v-model="statusFilter"
                                    class="rounded-md border-gray-300 py-1 pl-2 pr-8 text-base focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm"
                                >
                                    <option value="">All Statuses</option>
                                    <option v-for="(count, status) in statusCounts" :key="status" :value="status">
                                        {{ status }} ({{ count }})
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Phone
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Assigned To
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="lead in leads.data" :key="lead.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                <Link :href="route('leads.show', lead)">
                                                    {{ lead.name }}
                                                </Link>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ lead.email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ lead.phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <LeadStatusBadge :status="lead.status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ lead.assigned_to?.name || 'Unassigned' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link
                                            :href="route('leads.edit', lead)"
                                            class="text-primary-600 hover:text-primary-900 mr-4"
                                        >
                                            Edit
                                        </Link>
                                    </td>
                                </tr>
                                
                                <tr v-if="leads.data.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No leads found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                        <Pagination :links="leads.links" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>