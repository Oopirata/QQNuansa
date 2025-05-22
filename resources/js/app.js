import "../css/app.css";
import "./bootstrap";

import { Inertia } from "@inertiajs/inertia";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import axios from "axios";

axios.defaults.withCredentials = true;
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
}

function updateCsrfToken() {
    const token = getCookie("XSRF-TOKEN");
    if (token) {
        axios.defaults.headers.common["X-CSRF-TOKEN"] =
            decodeURIComponent(token);
        console.log("CSRF token updated:", token);
    } else {
        console.warn("CSRF token cookie not found");
    }
}

// Panggil sekali saat app mulai
updateCsrfToken();

// Update token setiap kali Inertia navigasi (SPA page change)
Inertia.on("navigate", () => {
    updateCsrfToken();
});

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
