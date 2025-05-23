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

const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error("CSRF token not found");
}

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

// function updateCsrfToken() {
//     const token = document.querySelector('meta[name="csrf-token"]');
//     if (token) {
//         axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
//         console.log("CSRF token updated:", token.content);
//     } else {
//         console.warn("CSRF token meta tag not found!");
//     }
// }

// // Panggil sekali saat app mulai
// updateCsrfToken();

// // Setiap kali Inertia selesai navigasi, update ulang token
// Inertia.on("navigate", () => {
//     updateCsrfToken();
// });
