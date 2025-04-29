<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';

const emit = defineEmits(['toggle-sidebar']);

const mobileMenuOpen = ref(false);
</script>

<template>
    <header class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
        <button
            type="button"
            class="-m-2.5 p-2.5 text-gray-700 lg:hidden"
            @click="emit('toggle-sidebar')"
        >
            <span class="sr-only">Open sidebar</span>
            <Bars3Icon class="h-6 w-6" aria-hidden="true" />
        </button>

        <!-- Separator -->
        <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true" />

        <div class="flex flex-1 justify-end gap-x-4 self-stretch lg:gap-x-6">
            <div class="flex items-center gap-x-4 lg:gap-x-6">
                <!-- Profile dropdown -->
                <div class="relative">
                    <Link
                        href="/logout"
                        method="post"
                        class="-m-1.5 flex items-center p-1.5"
                    >
                        <span class="sr-only">Sign out</span>
                        <span class="hidden lg:flex lg:items-center">
                            <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">
                                Sign out
                            </span>
                        </span>
                    </Link>
                </div>
            </div>
        </div>
    </header>
</template>