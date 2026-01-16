<template>
    <div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-xl rounded-2xl">
        <div class="mb-6 w-24">
            <button
                @click="goBack"
                class="flex items-center gap-2 px-3 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition font-semibold shadow"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Back
            </button>
        </div>
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:tems-center gap-4 border-b pb-6 justify-between">
            <div class="flex items-center gap-4">
                <!-- Profile Avatar -->
                <div
                    class="hidden md:flex w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center text-white font-bold text-2xl shadow-lg"
                >
                    {{ (user.name || "U").charAt(0).toUpperCase() }}
                </div>

                <!-- Profile Info -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ user.name }}
                    </h1>
                    <p class="text-gray-500">{{ user.email }}</p>
                </div>
            </div>

            <!-- Simple Follow / Unfollow buttons (UI only) -->
            <div v-if="$page.props.auth.user && user.id != $page.props.auth.user.id" class="flex items-center gap-2">
                <button
                    v-if="!isFollowing"
                    @click="follow"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-semibold bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-sm hover:scale-105 transition"
                >
                    <!-- Follow -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Follow
                </button>

                <button
                    v-else
                    @click="unfollow"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-semibold bg-white border border-gray-200 text-gray-700 shadow-sm hover:scale-105 transition"
                >
                    <!-- Unfollow -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Unfollow
                </button>
            </div>
        </div>

        <!-- Details Section -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
                class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-xl"
            >
                <h2 class="text-sm font-semibold text-gray-600">Joined</h2>
                <p class="text-lg font-medium text-gray-800">
                    {{ moment(user.created_at).format("MMMM YYYY") }}
                </p>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-xl"
            >
                <h2 class="text-sm font-semibold text-gray-600">Threads</h2>
                <p class="text-lg font-medium text-gray-800">
                    {{ user.threads ? user.threads.length : 0 }}
                </p>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-xl"
            >
                <h2 class="text-sm font-semibold text-gray-600">Replies</h2>
                <p class="text-lg font-medium text-gray-800">
                    {{ user.replies ? user.replies.length : 0 }}
                </p>
            </div>

            <div
                class="bg-gradient-to-br from-yellow-50 to-orange-50 p-4 rounded-xl"
            >
                <h2 class="text-sm font-semibold text-gray-600">Followers</h2>
                <p class="text-lg font-medium text-gray-800">
                    {{ user.followers ? user.followers.length : 0 }}
                </p>
            </div>
        </div>
        <h2 class="text-lg font-semibold text-gray-800 mb-2 mt-8">{{ user.name }}'s Threads</h2>
        <!-- Threads -->
        <div v-if="user.threads && user.threads.length" class="space-y-6">
            <div v-for="thread in user.threads" :key="thread.id" class="bg-white rounded-lg shadow p-6 flex flex-col md:flex-row md:items-center justify-between relative">
                <div class="flex-[3]">
                    <h2 class="text-lg font-semibold text-gray-800">{{ thread.title }}</h2>
                    <p class="mt-2 line-clamp-2">{{ thread.body }}</p>
                    <div class="text-gray-500 mt-3">
                        </div>
                    <span class="text-gray-400 mt-2">{{ moment(thread.created_at, "YYYYMMDD").fromNow() }}</span>
                </div>
                <div class="flex flex-col gap-2 mt-4 md:mt-0">
                    <!-- Replies, Views, View (horizontal group) -->
                    <div class="flex gap-6">
                        <span class="text-gray-400">
                            <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2h2"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h-6a2 2 0 00-2 2v0a2 2 0 002 2h6a2 2 0 002-2v0a2 2 0 00-2-2z"></path>
                            </svg>
                            {{ thread.replies.length }} {{ thread.replies.length > 1 ? 'replies' : 'reply' }}
                        </span>
                        <!-- <span class="text-gray-400">
                            <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"></path>
                            </svg>
                            {{ thread.views }} views
                        </span> -->
                        <Link :href="'/threads/'+thread.id" class="text-indigo-600 hover:underline">View</Link>
                    </div>
                    <!-- Edit & Delete buttons at bottom right -->
                    <div
                        v-if="$page.props.auth.user && $page.props.auth.user.id === thread.user_id"
                        class="md:absolute bottom-4 right-4 flex gap-2"
                    >
                        <Link
                            :href="route('threads.edit', thread.id)"
                            class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg font-semibold shadow hover:bg-yellow-200 transition flex items-center gap-2"
                            is="a"
                        >
                            <!-- Edit Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/>
                            </svg>

                        </Link>
                        <button
                            @click="openDeleteModal(thread)"
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
        </div>
        <div v-else class="text-gray-400 italic">No threads yet.</div>
        <!-- Replies -->
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ user.name }}'s Replies</h2>
            <!-- Replies List -->
            <div v-if="user.replies && user.replies.length" class="space-y-6">
                <div
                    v-for="reply in user.replies"
                    :key="reply.id"
                    class="bg-indigo-50 rounded-xl p-6 shadow flex flex-col gap-2"
                >
                    <Link
                        :href="route('threads.show', reply.thread_id)"
                        class="flex items-center gap-2 text-indigo-600 font-semibold hover:underline"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            class="w-5 h-5"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"
                            />
                        </svg>
                        See original thread
                    </Link>
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2">
                        <div class="flex flex-row gap-2">
                        <p
                            class="hidden md:flex w-8 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center text-white font-bold text-xl"
                        >
                            {{ user.name.charAt(0).toUpperCase() }}
                        </p>
                        <span class="font-semibold text-indigo-700">{{
                            user.name
                        }}</span>
                        </div>
                        <span class="text-gray-400 text-xs">{{
                            moment(reply.created_at).format(
                                "MMM D, YYYY, h:mm A"
                            )
                        }}</span>
                    </div>

                    <p class="text-gray-700">{{ reply.body }}</p>
                </div>
            </div>
            <div v-else class="text-gray-400 italic">No replies yet.</div>
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
import { Link, usePage, router } from "@inertiajs/vue3";
import moment from "moment";

export default {
    data() {
        return {
            // UI-only follow state
            isFollowing: this.followed? true : false,
            replyBody: '',
            replyProcessing: false,
            replyError: '',
            replyToDelete: null,
            showModal: false,
        }
    },
    components: {
        Link,
    },
    props: {
        followed: {
            type: Boolean,
        },
    },
    computed: {
        user() {
            return this.$page.props.user || {};
        },
        backUrl() {
            return this.$page.props.backUrl || "/";
        },
    },
    methods: {
        // Simple UI-only follow/unfollow
        follow() {
            this.isFollowing = true
            router.post(route('user.follow', this.user.id), {
                preserveScroll: true,
                onError: () => {
                    this.isFollowing = false
                }
            })
        },
        unfollow() {
            this.isFollowing = false
            router.delete(route('user.unfollow', this.user.id), {
                preserveScroll: true,
                onError: () => {
                    this.isFollowing = true
                }
            })
        },
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
};
</script>
