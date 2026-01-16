<template>
    <div class="max-w-2xl mx-auto mt-16 bg-white rounded-2xl shadow-xl p-10">
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
        <h1 class="text-3xl font-extrabold text-indigo-700 mb-8 text-center">
            {{ thread ? "Edit" : "Create New" }} Thread
        </h1>
        <form @submit.prevent="submitThread" class="space-y-7">
            <!-- Title -->
            <div>
                <label
                    class="block text-lg font-semibold text-gray-700 mb-2"
                    for="title"
                    >Title</label
                >
                <input
                    v-model="form.title"
                    id="title"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Enter thread title"
                />
                <span v-if="errors.title" class="text-red-500 text-sm">{{
                    errors.title
                }}</span>
            </div>
            <!-- Body -->
            <div>
                <label
                    class="block text-lg font-semibold text-gray-700 mb-2"
                    for="body"
                    >Body</label
                >
                <textarea
                    v-model="form.body"
                    id="body"
                    rows="6"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Describe your thread..."
                ></textarea>
                <span v-if="errors.body" class="text-red-500 text-sm">{{
                    errors.body
                }}</span>
            </div>
            <!-- Category Dropdown -->
            <div>
                <label
                    class="block text-lg font-semibold text-gray-700 mb-2"
                    for="category"
                    >Category</label
                >
                <select
                    v-model="form.category_id"
                    id="category"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                >
                    <option value="" disabled>Select a category</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
                <span v-if="errors.category_id" class="text-red-500 text-sm">{{
                    errors.category_id
                }}</span>
            </div>
            <!-- Tags Input (only tag ids in form, show names in UI) -->
            <div>
                <div class="flex flex-row gap-2 items-center justify-between">
                <label class="block text-lg font-semibold text-gray-700 mb-2"
                    >Tags</label
                ><span class="text-sm text-gray-500">(Optional)</span>
                </div>
                <div class="flex flex-wrap gap-2 mb-2">
                    <span
                        v-for="tagId in form.tags"
                        :key="tagId"
                        class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1"
                    >
                        #{{ tags.find((t) => t.id === tagId)?.name }}
                        <button
                            type="button"
                            @click="removeTag(tagId)"
                            class="ml-1 text-indigo-500 hover:text-red-500 font-bold"
                        >
                            &times;
                        </button>
                    </span>
                </div>
                <span class="text-gray-700">Select a tag</span>
                <!-- <input
                    v-model="tagInput"
                    @keydown.enter.prevent="addTag"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Add a tag and press Enter"
                /> -->
                <!-- <span v-if="errors.tags" class="text-red-500 text-sm">{{
                    errors.tags
                }}</span> -->
                <div class="mt-2 flex flex-wrap gap-2">
                    <span
                        v-for="tag in tags"
                        :key="tag.id"
                        @click="addTagFromList(tag.id)"
                        class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-sm font-semibold cursor-pointer hover:bg-indigo-200 transition"
                    >
                        #{{ tag.name }}
                    </span>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="pt-4">
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-bold py-3 rounded-lg shadow hover:scale-105 transition"
                >
                    {{ thread ? "Update" : "Create" }} Thread
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { ref } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";

export default {
    components: {
        Link,
    },
    props: {
        thread: {
            type: Object,
            default: null,
        },
    },
    computed: {
        errors() {
            return this.$page.props.errors;
        },
        categories() {
            return usePage().props.categories || [];
        },
        tags() {
            return usePage().props.tags || [];
        },
    },
    data() {
        return {
            form: {
                title: this.thread?.title || '',
                body: this.thread?.body || '',
                category_id: this.thread?.category_id || '',
                tags: this.thread?.tags
                    ? this.thread.tags.map((tag) => tag.id)
                    : [],
            },
            tagInput: "",
        };
    },
    methods: {
        goBack() {
            // Try to go back in browser history first
            if (window.history.length > 1) {
                window.history.back();
            } else {
                // Fallback to a default route using Inertia router
                router.visit(route("threads.index"));
            }
        },
        addTag() {
            const tag = this.tagInput.trim().toLowerCase();
            if (tag && !this.form.tags.includes(tag)) {
                this.form.tags.push(tag);
            }
            this.tagInput = "";
        },
        addTagFromList(tag) {
            if (!this.form.tags.includes(tag)) {
                this.form.tags.push(tag);
            }
        },
        removeTag(tag) {
            this.form.tags = this.form.tags.filter((t) => t !== tag);
        },
        submitThread() {
            if (this.thread) {
                router.put(route("threads.update", this.thread.id), this.form);
            } else {
                router.post(route("threads.store"), this.form);
            }
        },
    },
};
</script>

<style></style>
