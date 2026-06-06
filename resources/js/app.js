import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { initHostedVideoPlaceholders } from './embed-video.js';

function mountHostedVideos() {
    requestAnimationFrame(() => initHostedVideoPlaceholders());
}

function removeLoadingFallback() {
    document.getElementById('app-loading-fallback')?.remove();
}

function showLoadFailure(message) {
    removeLoadingFallback();

    const panel = document.createElement('div');
    panel.setAttribute('role', 'alert');
    panel.style.cssText = 'min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;background:#f1f5f9;color:#0f172a;font-family:system-ui,sans-serif;text-align:center;';
    panel.innerHTML = `<div><p style="font-weight:600;margin-bottom:0.5rem;">Unable to load the admin panel</p><p style="color:#475569;max-width:28rem;">${message}</p><p style="margin-top:1rem;color:#64748b;font-size:0.875rem;">Try a hard refresh (Ctrl+F5). If this continues, ask your administrator to run <code>npm run build</code> on the server.</p></div>`;
    document.body.prepend(panel);
}

const pageModules = import.meta.glob('./Pages/**/*.vue');

createInertiaApp({
    resolve: async (name) => {
        const importPage = pageModules[`./Pages/${name}.vue`];

        if (! importPage) {
            throw new Error(`Page not found: ${name}`);
        }

        const module = await importPage();

        return module.default;
    },
    setup({ el, App, props, plugin }) {
        try {
            const app = createApp({ render: () => h(App, props) }).use(plugin);
            app.mount(el);
            removeLoadingFallback();
            mountHostedVideos();

            return app;
        } catch (error) {
            showLoadFailure(error instanceof Error ? error.message : 'An unexpected error occurred.');
            throw error;
        }
    },
    progress: {
        color: '#2563eb',
    },
});

window.addEventListener('error', (event) => {
    const target = event.target;

    if (target instanceof HTMLScriptElement || target instanceof HTMLLinkElement) {
        showLoadFailure('A required script or stylesheet failed to load. Check your network connection or browser extensions that block scripts.');
    }
}, true);

router.on('navigate', () => mountHostedVideos());

document.addEventListener('DOMContentLoaded', () => mountHostedVideos());

const loadingTimeoutMs = 20_000;

window.setTimeout(() => {
    const fallback = document.getElementById('app-loading-fallback');

    if (! fallback) {
        return;
    }

    fallback.innerHTML = '<div style="max-width:24rem;text-align:center;line-height:1.5;"><p style="font-weight:600;color:#0f172a;">Still loading…</p><p style="margin-top:0.5rem;">This can happen on slow connections. Wait a little longer, or hard-refresh (Ctrl+F5).</p></div>';
}, loadingTimeoutMs);
