import { createApp, h, type DefineComponent } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { createPinia } from 'pinia'
import App from './app.vue'
import './lib/utils'
import './lib/axios'
import '../css/inertia.css'

const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue')

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => {
        const page = pages[`./pages/${name}.vue`]
        if (!page) {
            console.error(`Page not found: ${name}`)
            return pages['./pages/app/dashboard.vue']
        }
        return page()
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia()
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .mount(el)
    },
    progress: {
        color: '#4F46E5',
        showSpinner: true,
    },
})
