<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold">Categories</h1>
            <div>
                <Link :href="route('admin.categories.createForm')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow hover:opacity-95 disabled:opacity-60"
                >
                    Create
                </Link>
            </div>
        </div>

        <!-- Category List Start -->
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="font-medium">All Categories</div>
                    <div class="text-sm text-gray-500">
                        Total: {{ categories?.length || 0 }}
                    </div>
                </div>
            </div>

            <div v-if="categories.length === 0" class="px-6 py-12 text-center">
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
                <p class="text-gray-600">No categories found.</p>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="min-w-full text-left align-middle">
                    <thead class="bg-gray-50 text-gray-600 text-sm">
                        <tr>
                            <th class="px-6 py-3 font-medium">Name</th>
                            <th class="px-6 py-3 font-medium">Description</th>
                            <th class="px-6 py-3 font-medium">Slug</th>
                            <th class="px-6 py-3 font-medium">Created</th>
                            <th class="px-6 py-3 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="category in categories"
                            :key="category.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ category.name }}
                                </div>
                            </td>
                            <td>
                                <div class="text-gray-600 text-sm">
                                    {{ category.description }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-full bg-indigo-50 text-indigo-700 px-2 py-0.5 text-xs font-medium"
                                    >{{ category.slug }}</span
                                >
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ formatDate(category.created_at) }}
                            </td>
                            <td
                                class="px-6 py-4 text-gray-600 text-sm space-x-4 gap-0 flex"
                            >
                                <Link
                                    :href="route('admin.categories.edit', category.id)"
                                    class="bg-yellow-100 text-yellow-700 px-2 py-2 rounded-lg font-semibold shadow hover:bg-yellow-200 transition flex items-center gap-2"
                                >
                                    <!-- Edit Icon -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/>
                                    </svg>

                                </Link>
                                <button
                                    @click="openDeleteModal(category)"
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
        <!-- Category List End -->
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

export default {
    components: { Link },
    data() {
        return {
            showModal: false,
            categoryToDelete: null,
        };
    },
    props: {
        categories: { type: Array, required: true },
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
        openDeleteModal(category) {
            this.categoryToDelete = category
            this.showModal = true
        },
        closeModal() {
            this.showModal = false
            this.categoryToDelete = null
        },
        confirmDelete() {
            router.delete(route('admin.categories.destroy', this.categoryToDelete.id), {
                preserveScroll: true,
                onSuccess: () => {
                    router.reload({ only: ['categories'] })
                },
            })
            this.closeModal()
        },
    },
};
</script>
