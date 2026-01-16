<template>
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-purple-100 py-16 px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-2xl p-10">
            <!-- Back Button and Thread Title -->
            <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                <button
                    @click="goBack"
                    class="flex items-center gap-2 px-3 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition font-semibold shadow"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </button>
                <h1 class="text-3xl font-extrabold text-indigo-700">{{ thread.title }}</h1>
            </div>
            <!-- Meta Info -->
            <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6 text-gray-500 text-sm">
                <span>
                    <svg class="inline w-5 h-5 mr-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <Link :href="route('user.show', thread.user_id)">{{ thread.user?.name || 'Anonymous' }}</Link>
                </span>
                <span>
                    <svg class="inline w-5 h-5 mr-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ moment(thread.created_at).format('MMM D, YYYY, h:mm A') }}
                </span>
                <span v-if="thread.category">
                    <svg class="inline w-5 h-5 mr-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    {{ thread.category.name }}
                </span>
            </div>
            <!-- Tags -->
            <div v-if="thread.tags && thread.tags.length" class="mb-6 flex flex-wrap gap-2">
                <span
                    v-for="tag in thread.tags"
                    :key="tag.id"
                    class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-semibold"
                >
                    #{{ tag.name }}
                </span>
            </div>
            <!-- Thread Body -->
            <div class="prose prose-indigo max-w-none text-lg text-gray-800 mb-10">
                {{ thread.body }}
            </div>
            <!-- Replies Section -->
            <div>
                <h2 class="text-xl font-bold text-indigo-700 mb-4">Replies</h2>
                <!-- Reply Form -->
                <div v-if="$page.props.auth.user" class="mb-8">
                    <form @submit.prevent="submitReply" class="flex flex-col gap-4">
                        <textarea
                            v-model="replyBody"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            placeholder="Write your reply..."
                            required
                        ></textarea>
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="bg-indigo-600 text-white font-bold px-6 py-2 rounded-lg shadow hover:bg-indigo-700 transition"
                                :disabled="replyProcessing"
                            >
                                Reply
                            </button>
                        </div>
                    </form>
                    <div v-if="replyError" class="text-red-500 mt-2">{{ replyError }}</div>
                </div>
                <div v-else class="mb-8 text-gray-500 italic">You must be logged in to reply.</div>
                <!-- Replies List -->
                <div v-if="thread.replies && thread.replies.length" class="space-y-6">
                <div
                    v-for="reply in thread.replies"
                    :key="reply.id"
                    class="bg-indigo-50 rounded-xl p-6 shadow flex flex-col gap-2"
                >
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2">
                        <div class="flex flex-row gap-2 items-center">
                        <Link :href="route('user.show', reply.user.id)" class=" w-8 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center text-white font-bold text-xl">
                            {{ (reply.user?.name || 'A').charAt(0).toUpperCase() }}

                        </Link>
                        <span class="font-semibold text-indigo-700">{{ reply.user?.name || 'Anonymous' }}</span>
                        </div>
                        <div>
                        <span class="text-gray-400 text-xs">{{ moment(reply.created_at).format('MMM D, YYYY, h:mm A') }}</span>
                        </div>
                    </div>
                    <div v-if="reply.editing">
                        <textarea
                            v-model="reply.editBody"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            required
                        ></textarea>
                        <div class="flex justify-end">
                            <button
                                @click="updateReply(reply)"
                                class="bg-indigo-600 text-white font-bold px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition"
                            >
                                Save
                            </button>
                            <button
                                @click="cancelEdit(reply)"
                                class="ml-2 bg-gray-300 text-gray-700 font-bold px-4 py-2 rounded-lg shadow hover:bg-gray-400 transition"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                    <div v-else class="text-gray-700 text-base">{{ reply.body }}</div>
                    <div class="flex justify-end gap-1">
                        <button
                            v-if="$page.props.auth.user && $page.props.auth.user.id === reply.user_id && !reply.editing"
                            @click="startEdit(reply)"
                            class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-yellow-200 transition flex items-center gap-2"
                        is="a"
                        >
                            <!-- Edit Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/>
                            </svg>

                        </button>
                        <button
                            v-if="$page.props.auth.user && $page.props.auth.user.id === reply.user_id && !reply.editing"
                            @click="openDeleteModal(reply)"
                            class="bg-red-100 text-red-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-red-200 transition flex items-center gap-2"
                        >
                            <!-- Delete Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h10"/>
                            </svg>

                        </button>

                    </div>
                </div>
            </div>
                <div v-else class="text-gray-400 italic">No replies yet.</div>
            </div>
        </div>
    </div>
    <!-- Modal Popup -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
            <h2 class="text-xl font-bold text-red-700 mb-4">Confirm Deletion</h2>
            <p class="mb-6 text-gray-700">
                Are you sure you want to delete? This action cannot be undone.
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
</template>

<script>
import { usePage, router, Link } from '@inertiajs/vue3'
import moment from 'moment'

export default {
    components: {
        Link,
    },
    data() {
        return {
            replyBody: '',
            replyProcessing: false,
            replyError: '',
            replyToDelete: null,
            showModal: false,
        }
    },
    computed: {
        thread() {
            return usePage().props.thread
        }
    },
    methods: {
        moment,
        goBack() {
            // Try to go back in browser history first
            if (window.history.length > 1) {
                window.history.back();
            } else {
                // Fallback to a default route using Inertia router
                router.visit(route("threads.index"));
            }
        },

        // ---- Reply Submit ----
        submitReply() {
            this.replyProcessing = true
            this.replyError = ''
            router.post(`/threads/${this.thread.id}/reply`, { body: this.replyBody }, {
                preserveScroll: true,
                onSuccess: () => {
                    this.replyBody = ''
                    this.replyProcessing = false
                },
                onError: (errors) => {
                    this.replyError = errors.body || 'Failed to post reply.'
                    this.replyProcessing = false
                }
            })
        },

        // ---- Delete ----
        openDeleteModal(reply) {
            this.replyToDelete = reply
            this.showModal = true
        },
        closeModal() {
            this.showModal = false
            this.replyToDelete = null
        },
        confirmDelete() {
            router.delete(route('replies.destroy', this.replyToDelete.id), {
                preserveScroll: true,
                onSuccess: () => {
                    router.reload({ only: ['thread'] })
                },
            })
            this.closeModal()
        },

        // ---- Edit ----
        startEdit(reply) {
            reply.editing = true
            reply.editBody = reply.body
        },
        cancelEdit(reply) {
            reply.editing = false
            reply.editBody = ''
        },
        updateReply(reply) {
            this.replyProcessing = true
            this.replyError = ''

            router.put(route('replies.update', reply.id), { body: reply.editBody }, {
                preserveScroll: true,
                onSuccess: () => {
                    reply.body = reply.editBody
                    reply.editing = false
                    reply.editBody = ''
                    this.replyProcessing = false
                },
                onError: (errors) => {
                    this.replyError = errors.body || 'Failed to update reply.'
                    this.replyProcessing = false
                }
            })
        },
    },
}
</script>




<style>

</style>
