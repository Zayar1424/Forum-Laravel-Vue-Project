<template>
    <div>
        <header class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-sm text-gray-500">Overview of forum activity</p>
            </div>
            <div class="flex items-center space-x-3">
                <input
                    type="search"
                    placeholder="Search users or threads..."
                    class="px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">New report</button>
            </div>
        </header>

        <!-- Stat cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Threads</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ stats?.threads }}</p>
                    </div>
                    <div class="bg-indigo-50 text-indigo-600 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12v6a2 2 0 01-2 2H5l-4 0V6a2 2 0 012-2h14a2 2 0 012 2v6z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Replies</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ stats?.replies }}</p>
                    </div>
                    <div class="bg-green-50 text-green-600 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H6l-4 0V5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Users</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ stats?.users }}</p>
                    </div>
                    <div class="bg-yellow-50 text-yellow-600 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 100 8 4 4 0 000-8zM2 18a8 8 0 0116 0H2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Categories</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ stats?.categories }}</p>
                    </div>
                    <div class="bg-pink-50 text-pink-600 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 3a2 2 0 00-2 2v10l4-2 4 2 4-2 4 2V5a2 2 0 00-2-2H4z" />
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Latest users -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Latest Users</h2>
                    <a href="#" class="text-sm text-indigo-600 hover:underline">View all</a>
                </div>
                <ul class="space-y-3">

                    <li v-for="user in latestUsers" :key="user.id" class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div>
                                <p class="font-medium text-gray-900">{{ user.name }}</p>
                                <p class="text-sm text-gray-500">{{ user.email }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">{{ moment(user.created_at).fromNow() }}</div>
                    </li>

                </ul>
            </div>

            <!-- Latest threads -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Latest Threads</h2>
                    <a href="#" class="text-sm text-indigo-600 hover:underline">View all</a>
                </div>
                <ul class="space-y-4">
                    <li v-for="thread in latestThreads" :key="thread.id">
                        <!-- <a href="#" class="block hover:bg-gray-50 p-3 rounded"> -->
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ thread.title }}</p>
                                    <p class="text-sm text-gray-500 mt-1">by {{ thread.user.name }} • {{ thread.replies?.length }} {{ (thread.replies?.length || 0) > 1 ? 'replies' : 'reply' }}</p>
                                </div>
                                <div class="text-sm text-gray-500">{{ moment(thread.created_at).fromNow() }}</div>
                            </div>
                        <!-- </a> -->
                    </li>
                </ul>
            </div>
        </section>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    name: 'AdminDashboard',
    props: {
        stats: Object,
        latestUsers: Array,
        latestThreads: Array,

    },
    methods: {
        moment,
    },
};
</script>

