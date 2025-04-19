import '../css/app.css' // Make sure TailwindCSS or other CSS is loaded
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
    resolve: name => {
        // Dynamically import pages based on the route name
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    title: title => title ? `${title} - Ping CRM` : 'Ping CRM',
    setup({ el, App, props, plugin }) {
        // Create and mount the Vue app
        createApp({
            render: () => h(App, props),
        })
            .use(plugin) // Use Inertia plugin
            .mount(el) // Mount the app on the provided element
    },
})
