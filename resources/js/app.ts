import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, createSSRApp, h } from 'vue';
import { route, ZiggyVue } from 'ziggy-js';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    setup({ el, App, props, plugin }) {
        const ziggyConfig = (props.initialPage.props as Record<string, unknown>).ziggy as object | undefined;
        const isServer = el === null;
        const app = isServer
            ? createSSRApp({ render: () => h(App, props) })
            : createApp({ render: () => h(App, props) });

        app.use(plugin);
        app.use(ZiggyVue, ziggyConfig);

        // Make route() available globally in both browser and SSR (Node.js) contexts
        const boundRoute = (name: Parameters<typeof route>[0], params?: Parameters<typeof route>[1], absolute?: Parameters<typeof route>[2]) =>
            route(name, params, absolute, ziggyConfig);
        (globalThis as Record<string, unknown>).route = boundRoute;

        if (!isServer) {
            app.mount(el!);
        }

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
