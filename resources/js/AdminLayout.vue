<template>
    <Head :title="pageTitle" />
  <div class="min-h-screen flex bg-gray-100">
    <aside class="fixed left-0 top-0 h-screen w-64 bg-white border-r flex flex-col shadow-lg z-50">
      <div class="p-5 font-extrabold text-2xl tracking-tight">Admin Panel</div>
      <nav class="px-3 space-y-1 flex-1">
        <Link
          v-for="item in navItems"
          :key="item.href"
          :href="item.href"
          :class="[
            'block px-3 py-2.5 rounded-md text-[15px] md:text-base font-medium transition',
            item.href === currrentPath ? 'bg-gray-900 text-white' : 'text-gray-800 hover:bg-gray-100'
          ]"
        >
          {{ item.title }}
        </Link>
      </nav>
      <nav class="px-3 space-y-1 border-t pt-4 pb-4">
        <Link
          href="/logout"
          method="post"
          as="button"
          class="block w-full text-left px-3 py-2.5 rounded-md text-[15px] md:text-base font-medium text-gray-800 hover:bg-gray-100 flex items-center gap-2 transition"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Log Out
        </Link>
      </nav>
    </aside>
    <main class="ml-64 flex-1 overflow-y-auto h-screen">
      <section class="max-w-7xl mx-auto px-4 py-6">
        <slot />
      </section>
    </main>
  </div>
</template>

<script>
 import { Link, Head } from '@inertiajs/vue3';

 export default {
   components: {Link, Head},
   data() {
    return {
      navItems : [
         { title: 'Dashboard', href: '/admin/dashboard' },
         { title: 'Users', href: '/admin/users' },
         { title: 'Categories', href: '/admin/categories' },
        //  { title: 'Comments', href: '/admin/comments' },
        //  { title: 'Threads', href: '/admin/threads' },
       ]
    }
   },
  computed: {
    currrentPath(){
      return this.$page.url;
    },
    pageTitle(){
      return this.$page.props.title || 'Admin Panel';
    }
  }
 }
</script>

<style>
</style>
