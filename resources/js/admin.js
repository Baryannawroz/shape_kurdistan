import './bootstrap';
import '../css/admin.css';

import { router } from '@inertiajs/vue3';
import { bootInertia, removeLoadingFallback } from './inertia-boot.js';

const pageModules = import.meta.glob('./Pages/Admin/**/*.vue');

bootInertia(pageModules);

router.on('success', () => {
    removeLoadingFallback();
});
