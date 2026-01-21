import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import UserLayout from './UserLayout.vue';
import AdminLayout from './AdminLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${appName} | ${title}`,
        resolve: (name) =>{
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        let page = pages[`./Pages/${name}.vue`]
        // Decide layout based on the page name (works with Inertia client-side visits)
        if (name && name.toString().startsWith('admin/')) {
            page.default.layout = AdminLayout;
        }
        if (page && page.default && page.default.layout === undefined) {
            page.default.layout = UserLayout;
        }
        return page;
  },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
