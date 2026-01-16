<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold">Users</h1>
            <div class="flex flex-row items-center gap-4">
                <!-- Search bar -->
                        <div class="relative w-full">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search for users"
                                class="w-full rounded-md bg-white-800 py-2.5 pl-10 text-sm text-gray-800 placeholder-gray-400 focus:ring-1 focus:outline-none"
                            />
                        </div>
                <Link :href="route('admin.users.userForm')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow hover:opacity-95 disabled:opacity-60"
                >
                    Create
                </Link>
            </div>
        </div>

        <!-- User List Start -->
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="font-medium">All Users</div>
                    <div class="text-sm text-gray-500">
                        Total: {{ users?.length || 0 }}
                    </div>
                </div>
            </div>

            <div v-if="users.length === 0" class="px-6 py-12 text-center">
                <div
                    class="mx-auto w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6 text-gray-400"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 7.5A2.25 2.25 0 015.25 5.25h13.5A2.25 2.25 0 0121 7.5v9A2.25 2.25 0 0118.75 18.75H5.25A2.25 2.25 0 013 16.5v-9z"
                        />
                    </svg>
                </div>
                <p class="text-gray-600">No users found.</p>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="min-w-full text-left align-middle">
                    <thead class="bg-gray-50 text-gray-600 text-sm">
                        <tr>
                            <th class="px-6 py-3 font-medium">Name</th>
                            <th class="px-6 py-3 font-medium">Email</th>
                            <th class="px-6 py-3 font-medium">Threads</th>
                            <th class="px-6 py-3 font-medium">Role</th>
                            <th class="px-6 py-3 font-medium">Created</th>
                            <th class="px-6 py-3 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="user in filteredUsers"
                            :key="user.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ user.name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-full bg-indigo-50 text-indigo-700 px-2 py-0.5 text-xs font-medium"
                                    >{{ user.email }}</span
                                >
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-md bg-emerald-50 text-emerald-700 px-2 py-1 text-xs font-semibold"
                                    >{{ user.threads.length ?? 0 }}</span
                                >
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-small text-gray-900">
                                    {{ user.is_admin ? "admin" : "user" }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ formatDate(user.created_at) }}
                            </td>
                            <td
                                class="px-6 py-4 text-gray-600 text-sm space-x-4 gap-0 flex"
                            >
                                <Link
                                    :href="route('admin.users.edit', user.id)"
                                    class="bg-yellow-100 text-yellow-700 px-2 py-2 rounded-lg font-semibold shadow hover:bg-yellow-200 transition flex items-center gap-2"
                                is="a"
                                >
                                    <!-- Edit Icon -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/>
                                    </svg>

                                </Link>
                                <button
                                    v-if="!user.is_admin"
                                    @click="openDeleteModal(user)"
                                    class="bg-red-100 text-red-700 px-2 py-2 rounded-lg font-semibold shadow hover:bg-red-200 transition flex items-center gap-2"
                                >
                                    <!-- Delete Icon -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>

                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- User List End -->
        <!-- Modal Popup Start -->
        <div
            v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black backdrop-blur-sm"
        >
            <div class="bg-white rounded-2xl shadow-2xl p-6 w-11/12 max-w-sm">
                <h2 class="text-xl font-bold text-red-700 mb-4">
                    Confirm Deletion
                </h2>
                <p class="mb-6 text-gray-700">
                    Are you sure you want to delete? This action cannot be
                    undone.
                </p>
                <div class="flex justify-end gap-4">
                    <button
                        @click="closeModal"
                        class="px-5 py-2 rounded-lg bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition"
                    >
                        Cancel
                    </button>
                    <button
                        @click="confirmDelete"
                        class="px-5 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal Popup End -->
    </div>
</template>

<script>
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

export default {
    components: { Link },
    data() {
        return {
            showModal: false,
            userToDelete: null,
            search: '',
        };
    },
    props: {
        users: { type: Array, required: true },
    },
    computed: {
        filteredUsers() {
            if (this.search.trim() === '') {
                return this.users;
            }
            return this.users.filter(user =>
                user.name.toLowerCase().includes(this.search.toLowerCase()) ||
                user.email.toLowerCase().includes(this.search.toLowerCase())
            );
        },
    },
    methods: {
        formatDate(value) {
            if (!value) return "-";
            try {
                return new Date(value).toLocaleString();
            } catch {
                return String(value);
            }
        },
        // ---- Delete ----
        openDeleteModal(user) {
            this.userToDelete = user
            this.showModal = true
        },
        closeModal() {
            this.showModal = false
            this.userToDelete = null
        },
        confirmDelete() {
            router.delete(route('admin.users.destroy', this.userToDelete.id), {
                preserveScroll: true,
                onSuccess: () => {
                    router.reload({ only: ['users'] })
                },
            })
            this.closeModal()
        },
    },
};
</script>
