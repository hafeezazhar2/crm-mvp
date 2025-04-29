<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import LeadStatusBadge from '@/Components/LeadStatusBadge.vue';
import { ref } from 'vue';

const props = defineProps({
    lead: Object,
});

const noteForm = useForm({
    note: '',
});

const addNote = () => {
    noteForm.post(route('leads.notes.store', props.lead), {
        preserveScroll: true,
        onSuccess: () => noteForm.reset(),
    });
};
</script>

<template>
    <AppLayout>
        <Head :title="lead.name" />
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">{{ lead.name }}</h1>
                        <div class="mt-1 flex items-center">
                            <LeadStatusBadge :status="lead.status" class="mr-2" />
                            <span class="text-sm text-gray-500">
                                Created {{ lead.created_at }} by {{ lead.created_by.name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <Link
                            :href="route('leads.edit', lead)"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring focus:ring-primary-300 disabled:opacity-25 transition"
                        >
                            Edit
                        </Link>
                    </div>
                </div>
                
                <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Lead Information
                                </h3>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <div class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ lead.email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ lead.phone }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ lead.assigned_to?.name || 'Unassigned' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ lead.updated_at }}</dd>
                                    </div>
                                </div>
                                
                                <div class="mt-8">
                                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ lead.notes }}</dd>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Activity Notes
                                </h3>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <div class="space-y-4">
                                    <div v-for="note in lead.notes" :key="note.id" class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                        <div class="flex justify-between">
                                            <p class="text-sm font-medium text-gray-900">{{ note.user.name }}</p>
                                            <p class="text-sm text-gray-500">{{ note.created_at }}</p>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-700 whitespace-pre-line">{{ note.note }}</p>
                                    </div>
                                    
                                    <div v-if="lead.notes.length === 0" class="text-sm text-gray-500">
                                        No activity notes yet.
                                    </div>
                                </div>
                                
                                <form @submit.prevent="addNote" class="mt-6">
                                    <div>
                                        <label for="note" class="block text-sm font-medium text-gray-700">Add Note</label>
                                        <div class="mt-1">
                                            <textarea
                                                id="note"
                                                v-model="noteForm.note"
                                                rows="3"
                                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                :class="{ 'border-red-300': noteForm.errors.note }"
                                            ></textarea>
                                            <p v-if="noteForm.errors.note" class="mt-2 text-sm text-red-600">
                                                {{ noteForm.errors.note }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                            :disabled="noteForm.processing"
                                        >
                                            Add Note
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-1">
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Quick Actions
                                </h3>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <div class="space-y-4">
                                    <Link
                                        :href="route('leads.edit', lead)"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                    >
                                        Edit Lead
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>