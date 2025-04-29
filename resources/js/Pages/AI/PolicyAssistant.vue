<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const question = ref('');
const answer = ref('');
const isLoading = ref(false);
const aiService = ref('openai');
const source = ref('');

const askQuestion = async () => {
    if (!question.value.trim()) return;
    
    isLoading.value = true;
    answer.value = '';
    source.value = '';
    
    try {
        const response = await axios.post(route('ai.query-policy'), {
            question: question.value,
            ai_service: aiService.value,
        });
        
        answer.value = response.data.answer;
        source.value = response.data.source;
    } catch (error) {
        answer.value = 'An error occurred while processing your question. Please try again.';
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Policy Assistant" />
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <h1 class="text-2xl font-semibold text-gray-900">Policy Assistant</h1>
                
                <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Ask a question about company policies
                        </h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            <div>
                                <label for="ai-service" class="block text-sm font-medium text-gray-700">
                                    AI Service
                                </label>
                                <select
                                    id="ai-service"
                                    v-model="aiService"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                >
                                    <option value="openai">OpenAI</option>
                                    <option value="deepseek">DeepSeek</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="question" class="block text-sm font-medium text-gray-700">
                                    Your Question
                                </label>
                                <div class="mt-1">
                                    <textarea
                                        id="question"
                                        v-model="question"
                                        rows="3"
                                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="e.g. What is our refund policy?"
                                    ></textarea>
                                </div>
                            </div>
                            
                            <div>
                                <button
                                    type="button"
                                    @click="askQuestion"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                    :disabled="isLoading"
                                >
                                    <span v-if="isLoading" class="mr-2">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    Ask Question
                                </button>
                            </div>
                            
                            <div v-if="answer" class="mt-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">
                                    Answer <span v-if="source" class="text-xs bg-gray-100 px-2 py-1 rounded">from {{ source }}</span>
                                </h4>
                                <div class="prose prose-sm max-w-none">
                                    <p class="whitespace-pre-line">{{ answer }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>