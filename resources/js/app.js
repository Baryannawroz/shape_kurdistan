import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { initHostedVideoPlaceholders } from './embed-video.js';

function mountHostedVideos() {
    requestAnimationFrame(() => initHostedVideoPlaceholders());
}

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];

        if (! page) {
            throw new Error(`Page not found: ${name}`);
        }

        return page.default;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) }).use(plugin);
        app.mount(el);
        mountHostedVideos();

        return app;
    },
    progress: {
        color: '#2563eb',
    },
});

router.on('navigate', () => mountHostedVideos());

document.addEventListener('DOMContentLoaded', () => mountHostedVideos());
