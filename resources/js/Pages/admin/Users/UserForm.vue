<template>
  <div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-gradient-to-tr from-indigo-600 to-purple-500 flex items-center justify-center text-white shadow">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M3 12h18M3 17h18" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-semibold text-gray-800">
            {{ isEdit ? 'Edit User' : 'Create User' }}
          </h1>
          <p class="text-sm text-gray-500">Add a new user to the system.</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <Link :href="backUrl" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
          Back
        </Link>
        <button
          @click="submit"
          :disabled="submitting"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow hover:opacity-95 disabled:opacity-60"
        >
          <svg v-if="!submitting" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 animate-spin" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-opacity="0.25" fill="none"></circle>
            <path d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="4" stroke-linecap="round" fill="none"></path>
          </svg>
          <span>{{ isEdit ? 'Update' : 'Create' }}</span>
        </button>
      </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
      <div class="px-6 py-5 border-b bg-gray-50">
        <div class="flex items-center justify-between">
          <div class="font-medium text-gray-700">User Details</div>
          <!-- <div class="text-sm text-gray-500">All fields are optional except name</div> -->
        </div>
      </div>

      <div class="px-6 py-6 space-y-6">
        <div class="grid grid-cols-1 gap-6">
          <label class="block">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">Name</span>
              <span class="text-xs text-gray-400">{{ form.name.length }}/100</span>
            </div>
            <input
              v-model="form.name"
              type="text"
              placeholder="e.g. John Doe"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200"
              maxlength="100"
            />
            <p v-if="errors?.name" class="text-sm text-red-600 mt-2">{{ errors.name }}</p>
          </label>

          <label class="block">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">Email</span>
            </div>
            <input
              v-model="form.email"
              type="text"
              placeholder="e.g. user@example.com"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200"
            />
            <p class="text-xs text-gray-400 mt-2">Email should be a valid email address.</p>
            <p v-if="errors?.email" class="text-sm text-red-600 mt-2">{{ errors.email }}</p>
          </label>

          <label class="block">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">Password</span>
            </div>
            <input
              v-model="form.password"
              type="password"
              placeholder="Enter password"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200"
            />
            <p class="text-xs text-gray-400 mt-2">Use strong password.</p>
            <p v-if="errors?.password" class="text-sm text-red-600 mt-2">{{ errors.password }}</p>
          </label>

          <label class="block">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">Role</span>
            </div>
            <select
              v-model="form.role"
              class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200"
            >
            <option value="" disabled>Select role</option>
              <option value=0>User</option>
              <option value=1>Admin</option>
            </select>
            <p v-if="errors?.role" class="text-sm text-red-600 mt-2">{{ errors.role }}</p>
          </label>
        </div>

        <div class="pt-2 border-t">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">Preview</div>
            <div class="text-sm text-gray-500">Visible to all users</div>
          </div>

          <div class="mt-4">
            <div class="p-4 rounded-lg bg-gray-50 border">
              <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold">
                  {{ previewInitial }}
                </div>
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <div class="text-sm font-semibold text-gray-800">{{ form.name || 'User name' }}</div>
                  </div>
                  <p class="text-sm text-gray-600 mt-1">{{ form.email || 'user@example.com' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- px-6 py-6 -->
    </div> <!-- card -->
  </div>
</template>

<script>
import { Link, router} from '@inertiajs/vue3'

export default {
  components: { Link },
  props: {
    user: { type: Object, default: null },
    errors: { type: Object, default: () => ({}) },
    backUrl: { type: String, default: () => route('admin.users') },
  },
  data() {
    return {
      form: {
        name: this.user?.name || '',
        email: this.user?.email || '',
        role: this.user?.is_admin || '',
        password: this.user?.password || '',
      },
      submitting: false,
    }
  },
  computed: {
    isEdit() {
      return !!this.user
    },
    previewInitial() {
      return (this.form.name || 'U').charAt(0).toUpperCase()
    }
  },
  methods: {
    submit() {
      // UI-only: emit event so parent/controller can handle persistence
      this.submitting = true
      this.$emit('submit', { ...this.form, isEdit: this.isEdit })
    //   // allow parent to toggle submitting off; keep simple here
      setTimeout(() => (this.submitting = false), 600)
        if (this.user) {
            router.put(route("admin.users.update", this.user.id), this.form);
        } else {
            router.post(route("admin.users.store"), this.form);
        }
    }
  }
}
</script>

<style scoped>
/* small polish consistent with admin theme */
</style>
