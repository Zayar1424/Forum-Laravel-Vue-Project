<template>
  <div class="max-w-4xl mx-auto p-0 md:p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Forum Threads</h1>
      <div class="flex flex-col lg:flex-row items-center gap-3">
        <Link
          v-if="$page.props.auth.user"
          href="/new-thread"
          class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition"
        >
          + New Thread
        </Link>

        <div class="relative">
          <select
            v-model="filter"
            class="appearance-none bg-white px-4 py-2 pr-10 text-sm font-medium text-gray-700 border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 transition"
          >
            <option value="latest">Latest</option>
            <option value="most_replied">Most replied</option>
            <option v-if="$page.props.auth.user" value="followed_threads">Followed threads</option>
          </select>
          <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Threads list (client-side paginated) -->
    <div class="space-y-6">
      <div
        v-for="thread in paginatedThreads"
        :key="thread.id"
        class="bg-white rounded-lg shadow p-6 flex flex-col md:flex-row md:items-center justify-between relative"
      >
        <div class="flex-1">
          <h2 class="text-lg font-semibold text-gray-800">{{ thread.title }}</h2>
          <p class="mt-2 text-gray-600 line-clamp-2">{{ thread.body }}</p>
          <p class="text-gray-500 mt-2">
            Started by
            <Link :href="route('user.show', thread.user_id)" class="font-medium text-indigo-600">
              {{ thread.user?.name || 'Unknown' }}
            </Link>
            · {{ moment(thread.created_at).fromNow() }}
          </p>
        </div>

        <div class="flex flex-col gap-2 mt-4 md:mt-0 items-end">
          <div class="text-gray-400">
            <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            {{ thread.replies?.length || 0 }} {{ (thread.replies?.length || 0) > 1 ? 'replies' : 'reply' }}
            · <Link :href="route('threads.show', thread.id)" class="text-indigo-600 hover:underline">View</Link>
          </div>

          <div v-if="$page.props.auth.user && $page.props.auth.user.id === thread.user_id" class="md:absolute bottom-4 right-4 flex gap-2">
            <Link :href="route('threads.edit', thread.id)" class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded font-semibold">
                <!-- Edit Icon -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/>
                </svg>
            </Link>
            <button @click="openDeleteModal(thread)" class="bg-red-100 text-red-700 px-3 py-1 rounded font-semibold">
                <!-- Delete Icon -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h10"/>
                </svg>
            </button>
          </div>
        </div>
      </div>

      <p v-if="threadsList.length === 0" class="text-gray-500">No threads to show.</p>
    </div>

    <!-- Client-side pagination controls -->
    <div class="mt-6 flex items-center justify-center gap-2">
      <button class="px-3 py-1 border rounded" :disabled="currentPage === 1" @click="changePage(currentPage - 1)">Previous</button>

      <template v-for="page in totalPagesArray" :key="page">
        <button
          @click="changePage(page)"
          :class="['px-3 py-1 border rounded', { 'bg-indigo-600 text-white': page === currentPage }]"
        >
          {{ page }}
        </button>
      </template>

      <button class="px-3 py-1 border rounded" :disabled="currentPage === totalPages" @click="changePage(currentPage + 1)">Next</button>
    </div>

    <!-- Delete Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
      <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <h2 class="text-xl font-bold text-red-700 mb-4">Confirm Deletion</h2>
        <p class="mb-6 text-gray-700">
          Are you sure you want to delete <span class="font-semibold text-indigo-700">"{{ threadToDelete?.title }}"</span>? This action cannot be undone.
        </p>
        <div class="flex justify-end gap-4">
          <button @click="closeModal" class="px-5 py-2 rounded-lg bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200">Cancel</button>
          <button @click="confirmDelete" class="px-5 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import moment from 'moment'

export default {
  name: 'Home',
  components: { Link },
  props: {
    // Inertia may send either: an array of threads OR a paginator object
    threads: { type: [Array, Object], default: () => [] },
  },
  data() {
    return {
      threadsList: [],            // local array used for client-side pagination
      currentPage: 1,
      pageSize: 5,
      filter: this.$page?.props?.filter || 'latest',
      showModal: false,
      threadToDelete: null,
    }
  },
  mounted() {
    this.setThreadsFromProps()
  },
  watch: {
    // When the user changes the filter, request the server. preserveState: true keeps component instance,
    // we rely on the $page.props.threads watcher below to sync the local array.
    filter() {
      this.currentPage = 1
      this.$inertia.get(window.location.pathname, { filter: this.filter }, { preserveState: true, replace: true })
    },

    // Watch the Inertia prop and update local threadsList whenever the server returns new data.
    '$page.props.threads': {
      handler() {
        this.setThreadsFromProps()
        this.currentPage = 1
      },
      deep: false,
    },
  },
  computed: {
    // total pages based on local array
    totalPages() {
      return Math.max(1, Math.ceil(this.threadsList.length / this.pageSize))
    },
    // convenience array to render page numbers
    totalPagesArray() {
      return Array.from({ length: this.totalPages }, (_, i) => i + 1)
    },
    // slice that we render
    paginatedThreads() {
      const start = (this.currentPage - 1) * this.pageSize
      return this.threadsList.slice(start, start + this.pageSize)
    },
    moment() {
      return moment
    },
  },
  methods: {
    // initialize threadsList from Inertia props (supports paginator or array)
    setThreadsFromProps() {
      const p = this.$page?.props?.threads
      if (!p) {
        this.threadsList = []
        return
      }
      if (Array.isArray(p)) {
        this.threadsList = p
      } else if (p.data && Array.isArray(p.data)) {
        // Laravel paginator -> use data array
        this.threadsList = p.data
      } else {
        // fallback: empty
        this.threadsList = []
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page
        // optionally scroll to top of list: window.scrollTo(...)
      }
    },
    openDeleteModal(thread) {
      this.threadToDelete = thread
      this.showModal = true
    },
    closeModal() {
      this.showModal = false
      this.threadToDelete = null
    },
    confirmDelete() {
      // delete on server then remove locally so UI updates instantly
      this.$inertia.delete(route('threads.destroy', this.threadToDelete.id), {
        onSuccess: () => {
          this.threadsList = this.threadsList.filter(t => t.id !== this.threadToDelete.id)
          if (this.currentPage > this.totalPages) this.currentPage = this.totalPages
        }
      })
      this.closeModal()
    },
  }
}
</script>
