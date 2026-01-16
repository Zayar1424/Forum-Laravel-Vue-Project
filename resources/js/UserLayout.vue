<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import moment from 'moment';
import { usePage } from '@inertiajs/vue3';

// const search = ref('');

// Watch for changes and trigger search
// watch(search, (value) => {
//     router.get('/', { search: value }, { preserveState: true, replace: true });
// });
</script>

<template>
    <Head title="Forum Home" />
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 left-0 w-full z-50 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- Hamburger Menu Button for Mobile -->
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden text-white hover:text-indigo-200 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <span class="text-white text-2xl font-extrabold tracking-wide drop-shadow">
                    <Link href="/" class="hover:text-indigo-200 transition">Code Forum</Link>
                </span>
            </div>
            <!-- Search Bar -->
            <form @submit.prevent class="hidden md:flex items-center gap-3 w-full max-w-xl mx-8">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search threads..."
                    class="w-full px-4 py-2 rounded-lg border border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                />
            </form>
            <div class="flex items-center gap-8">
                <template v-if="!$page.props.auth.user">
                    <div class="flex flex-col md:flex-row gap-4">
                    <div>
                        <Link href="/login" class="bg-white text-indigo-700 font-bold px-4 py-2 rounded-lg shadow hover:bg-indigo-100 transition">Login</Link>
                    </div>
                    <div>
                        <Link href="/register" class="bg-indigo-500 text-white font-bold px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition">Register</Link>
                    </div>
                    </div>
                </template>
                <template v-else>
                    <Link href="/logout" method="post" class="bg-indigo-500 text-white font-bold px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition">Logout</Link>
                </template>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div
        v-if="mobileMenuOpen"
        @click="mobileMenuOpen = false"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm md:hidden"
    ></div>

    <!-- Mobile Sidebar Menu -->
    <aside
        v-if="mobileMenuOpen"
        class="fixed left-0 top-20 h-[calc(100vh-80px)] w-72 bg-gradient-to-b from-white via-indigo-50 to-purple-50 shadow-2xl z-40 md:hidden overflow-y-auto"
    >
        <div class="p-6 space-y-8">
            <!-- Categories Section -->
            <div>
                <h2 class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-4 tracking-wide">Categories</h2>
                <ul class="space-y-4">
                    <li v-for="category in $page.props.categories" :key="category.id">
                        <Link
                            :href="'/?category='+category.slug"
                            @click="mobileMenuOpen = false"
                            class="block text-indigo-700 font-semibold hover:text-purple-600 transition text-base"
                        >
                            {{ category.name }}
                        </Link>
                        <p class="text-gray-500 text-sm mt-1">{{ category.description }}</p>
                    </li>
                </ul>
            </div>

            <!-- Popular Tags Section -->
            <div class="border-t border-indigo-200 pt-6">
                <h2 class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-4 tracking-wide">Popular Tags</h2>
                <div class="flex flex-wrap gap-2">
                    <span v-for="tag in $page.props.tags" :key="tag.id" class="bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-semibold cursor-pointer hover:from-indigo-200 hover:to-purple-200 transition shadow">
                        <Link :href="'/?tag='+tag.id" @click="mobileMenuOpen = false">#{{ tag.name }}</Link>
                    </span>
                </div>
            </div>
        </div>
    </aside>

    <div class="bg-gradient-to-br from-indigo-50 via-white to-purple-100 min-h-screen flex pt-24">
    <!-- Desktop Sidebar -->
    <aside class="hidden md:flex w-80 bg-white/90 border-r border-gray-200 p-8 flex-col gap-10 shadow-xl rounded-r-3xl mt-8 mb-8 ml-8">
            <!-- Categories Section (on top) -->
            <div>
                <h2 class="text-xl font-bold text-indigo-700 mb-5 tracking-wide">Categories</h2>
                <ul class="space-y-6">
                    <li v-for="category in $page.props.categories" :key="category.id" class="border-b border-gray-200 pb-4">
                        <Link :href="'/?category='+category.slug" class="block text-indigo-700 font-semibold hover:underline text-lg">{{ category.name }}</Link>
                        <p class="text-gray-500 text-sm mt-1">{{ category.description }}</p>
                    </li>
                </ul>
            </div>
            <!-- Popular Tags Section -->
            <div>
                <h2 class="text-xl font-bold text-indigo-700 mb-5 tracking-wide">Popular Tags</h2>
                <div class="flex flex-wrap gap-3">
                    <span v-for="tag in $page.props.tags" class="bg-indigo-100 text-indigo-700 px-4 py-1 rounded-full text-sm font-semibold cursor-pointer hover:bg-indigo-200 transition shadow">
                        <Link :href="'/?tag='+tag.id">#{{ tag.name }}</Link>
                    </span>
                </div>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 px-12 py-10">
            <slot />
        </main>
    </div>
</template>

<script>
export default {
    name: 'Home',
    props: {
        threads: Array,
    },
    data() {
        return {
            search: '',
            mobileMenuOpen: false,
        };
    },
    watch: {
        search(){
            this.$inertia.get('/', {
                search: this.search,
            })
        }
    },
    methods: {

    },
    mounted() {

    },
};
</script>
