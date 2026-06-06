import './bootstrap';
import '../css/app.css';

import { router } from '@inertiajs/vue3';
import { bootInertia } from './inertia-boot.js';
import { initHostedVideoPlaceholders } from './embed-video.js';

function mountHostedVideos() {
    requestAnimationFrame(() => initHostedVideoPlaceholders());
}

const pageModules = import.meta.glob('./Pages/Front/**/*.vue');

bootInertia(pageModules);

router.on('navigate', () => mountHostedVideos());

document.addEventListener('DOMContentLoaded', () => mountHostedVideos());
