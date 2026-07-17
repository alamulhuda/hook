import { createApp, h, type DefineComponent } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { createPinia } from 'pinia'
import AppLayout from './app.vue'
import './lib/utils'
import './lib/axios'
import '../css/inertia.css'

import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

// Core MVP pages
const corePages = import.meta.glob<DefineComponent>('./core/**/*.vue')
// Non-MVP module pages (each module owns its own pages/ subfolder)
const modulePages = import.meta.glob<DefineComponent>('./modules/**/pages/**/*.vue')

const allPages = { ...corePages, ...modulePages }

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: async (name) => {
        // Inertia name from controller is e.g.:
        //   "core/dashboard"
        //   "core/admin/users/Index"
        //   "modules/akunting/pages/input-transaksi/Index"
        const module = await resolvePageComponent(`./${name}.vue`, allPages)
        const page = (module as any).default || module
        if (!page.layout) {
            page.layout = AppLayout
        }
        return page
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia()
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
        return vueApp.mount(el)
    },
    progress: {
        color: '#4F46E5',
        showSpinner: true,
    },
})
