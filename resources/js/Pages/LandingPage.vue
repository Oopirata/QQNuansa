<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";
import { ref, onMounted, onBeforeUnmount } from "vue";

AOS.init({
    duration: 1000,
    once: true,
});

const isParagraphVisible = ref(false);

onMounted(() => {
    AOS.init({
        duration: 1000,
        once: true,
        offset: 50,
    });
    document.addEventListener("click", closeDropdown);
});

const page = usePage();
const currentRoute = computed(() => page.url);
const dropdownOpen = ref(false);
const dropdownRef = ref(null);

const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        dropdownOpen.value = false;
    }
};

onBeforeUnmount(() => {
    document.removeEventListener("click", closeDropdown);
});

const isActive = (href) => {
    return currentRoute.value === href;
};

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route("logout"));
};

const menuItems = [
    { text: "Tentang Kami", href: "/companyprofile" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];
</script>

<template>
    <Head title="LandingPage" />
    <div class="bg-white min-h-screen flex flex-col">
        <div
            class="flex-grow bg-gradient-to-br from-gray-900 to-gray-800 relative"
        >
            <!-- Background Image -->
            <div
                class="absolute inset-0 p-8"
                data-aos="fade-in"
                data-aos-duration="1500"
            >
                <img
                    src="/images/asik.jpg"
                    alt="Background"
                    class="w-full h-full object-cover opacity-30 rounded-2xl shadow-lg"
                />
            </div>

            <!-- Main Content -->
            <div
                class="relative container mx-auto px-4 h-full flex items-center justify-center"
            >
                <div class="text-center mt-44">
                    <!-- Logo and Text -->
                    <div class="flex items-center justify-center gap-8 mb-16">
                        <img
                            src="/images/QQ White.png"
                            alt="Developing Your People"
                            class="w-64 h-64 object-contain"
                            data-aos="fade-right"
                            data-aos-delay="300"
                            data-aos-duration="1000"
                        />

                        <h1
                            class="text-4xl font-bold"
                            data-aos="fade-left"
                            data-aos-delay="600"
                            data-aos-duration="1000"
                        >
                            <span class="text-white">Developing</span><br />
                            <span class="text-white">Your</span>
                            <span class="text-orange-500 ml-2">People</span>
                        </h1>
                    </div>

                    <!-- Navigation Buttons -->
                    <div
                        class="flex justify-center gap-6"
                        data-aos="fade-up"
                        data-aos-delay="900"
                        data-aos-duration="1000"
                    >
                        <Link
                            v-for="item in menuItems"
                            :key="item.href"
                            :href="item.href"
                            class="px-6 py-3 bg-white bg-opacity-10 hover:bg-opacity-20 text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg border border-white border-opacity-20"
                        >
                            {{ item.text }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}

.nav-link {
    padding: 0.5rem 1rem;
    position: relative;
    z-index: 1;
    min-width: fit-content;
}

.nav-link::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e0f2fe;
    border-radius: 0.375rem;
    transform: scale(0.7);
    opacity: 0;
    transition: all 0.2s ease-in-out;
    z-index: -1;
}

.nav-link:hover::before,
.nav-link-active::before {
    transform: scale(1);
    opacity: 1;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}

.nav-link-active {
    color: #0e74b8;
}

h1 {
    font-family: "Times New Roman", serif;
}
</style>
