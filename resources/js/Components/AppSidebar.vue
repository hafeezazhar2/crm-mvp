<script setup>
import { Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
    sidebarOpen: Boolean,
});

const emit = defineEmits(['close-sidebar']);

const navigation = computed(() => [
    { name: 'Dashboard', href: '/dashboard', current: router.page.url === '/dashboard' },
    { 
        name: 'Leads', 
        href: '/leads', 
        icon: 'UsersIcon', 
        current: router.page.url.startsWith('/leads') 
    },
    // ... other routes
]);
/*
const navigation = computed(() => [
    { name: 'Dashboard', href: route('dashboard'), icon: 'HomeIcon', current: route().current('dashboard') },
    { name: 'Leads', href: route('leads.index'), icon: 'UsersIcon', current: route().current('leads.*') },
    ...(user.value.isAdmin ? [
        { name: 'Advisors', href: route('advisors.index'), icon: 'UserGroupIcon', current: route().current('advisors.*') },
    ] : []),
    { name: 'Policy Assistant', href: route('ai.policy-assistant'), icon: 'LightBulbIcon', current: route().current('ai.*') },
]);*/
</script>

<template>
    <div>
        <!-- Mobile sidebar -->
        <div class="lg:hidden">
            <div v-show="sidebarOpen" class="fixed inset-0 z-50 flex">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="emit('close-sidebar')" />
                
                <div class="relative flex w-full max-w-xs flex-1 flex-col bg-white focus:outline-none">
                    <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
                        <div class="flex flex-shrink-0 items-center px-4">
                            <h1 class="text-xl font-bold text-primary-600">CRM MVP</h1>
                        </div>
                        <nav aria-label="Sidebar" class="mt-5">
                            <div class="space-y-1 px-2">
                                <Link
                                    v-for="item in navigation"
                                    :key="item.name"
                                    :href="item.href"
                                    :class="[
                                        item.current
                                            ? 'bg-gray-100 text-primary-600'
                                            : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600',
                                        'group flex items-center rounded-md px-2 py-2 text-base font-medium'
                                    ]"
                                >
                                    <component
                                        :is="item.icon"
                                        :class="[
                                            item.current ? 'text-primary-500' : 'text-gray-400 group-hover:text-primary-500',
                                            'mr-4 h-6 w-6'
                                        ]"
                                        aria-hidden="true"
                                    />
                                    {{ item.name }}
                                </Link>
                            </div>
                        </nav>
                    </div>
                    <div class="flex flex-shrink-0 border-t border-gray-200 p-4">
                        <div class="group block flex-shrink-0">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-base font-medium text-gray-700 group-hover:text-gray-900">
                                        {{ user.name }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-500 group-hover:text-gray-700">
                                        {{ user.isAdmin ? 'Admin' : 'Advisor' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col lg:border-r lg:border-gray-200 lg:bg-white lg:pb-4 lg:pt-5">
            <div class="flex flex-shrink-0 items-center px-6">
                <h1 class="text-xl font-bold text-primary-600">CRM MVP</h1>
            </div>
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="mt-5 flex h-0 flex-1 flex-col overflow-y-auto">
                <!-- Navigation -->
                <nav class="flex-1 space-y-1 px-2">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        :class="[
                            item.current
                                ? 'bg-gray-100 text-primary-600'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600',
                            'group flex items-center rounded-md px-2 py-2 text-sm font-medium'
                        ]"
                    >
                        <component
                            :is="item.icon"
                            :class="[
                                item.current ? 'text-primary-500' : 'text-gray-400 group-hover:text-primary-500',
                                'mr-3 h-6 w-6'
                            ]"
                            aria-hidden="true"
                        />
                        {{ item.name }}
                    </Link>
                </nav>
            </div>
            <div class="flex flex-shrink-0 border-t border-gray-200 p-4">
                <div class="group block w-full flex-shrink-0">
                    <div class="flex items-center">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                                {{ user.name }}
                            </p>
                            <Link
                                href="/logout"
                                method="post"
                                class="text-xs font-medium text-gray-500 group-hover:text-gray-700"
                            >
                                Sign out
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>