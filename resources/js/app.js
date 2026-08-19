import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.ClassicEditor = ClassicEditor;

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue');

        if (!pages[`./Pages/${name}.vue`]) {
            throw new Error(`Inertia Page component not found: ./Pages/${name}.vue`);
        }

        return pages[`./Pages/${name}.vue`]();
    },
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(App, props),
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
});
