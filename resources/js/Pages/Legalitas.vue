<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref, onMounted, onBeforeUnmount } from "vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

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

// Initialize AOS
onMounted(() => {
    AOS.init({
        duration: 1200,
        once: true,
        offset: 50,
    });
    document.addEventListener("click", closeDropdown);
});

const asset = (path) => `/assets/${path}`;

const menuItems = [
    { text: "Tentang Kami", href: "/companyprofile" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

// Data legalitas untuk konten yang lebih kaya
const legalitasItems = [
    {
        title: "Akta Pendirian",
        number: "No. 12/2023",
        date: "15 Januari 2023",
        icon: "fas fa-file-contract",
        color: "#0E74B8",
    },
    {
        title: "SIUP",
        number: "No. 8273/SIP/2023",
        date: "25 Januari 2023",
        icon: "fas fa-certificate",
        color: "#5932EA",
    },
    {
        title: "NPWP",
        number: "99.876.543.2-000.000",
        date: "30 Januari 2023",
        icon: "fas fa-id-card",
        color: "#FF5733",
    },
    {
        title: "TDP",
        number: "No. TDP-12345/2023",
        date: "5 Februari 2023",
        icon: "fas fa-building",
        color: "#4CAF50",
    },
];
</script>

<template>
    <Head title="Legalitas" />
    <header
        class="p-4 flex justify-between items-center mt-2 container mx-auto"
        data-aos="fade-down"
    >
        <!-- Logo Section -->
        <div class="flex items-center">
            <img
                alt="Company Logo"
                class="h-14 w-20 hover:scale-105 transition-transform duration-300"
                src="/images/QQ crop.png"
            />
            <img
                alt="Company Logo"
                class="ml-2 h-14 w-36 hover:scale-105 transition-transform duration-300"
                src="/images/Nuansa crop.png"
            />
        </div>

        <!-- Navigation Menu Section -->
        <nav class="flex-grow px-20">
            <div
                class="pb-2 border-b-4 border-[#5099D5] w-fit mx-auto rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
            >
                <div class="flex space-x-8 px-4">
                    <Link
                        v-for="(item, index) in menuItems"
                        :key="index"
                        :href="item.href"
                        class="nav-link relative text-gray-700 text-center whitespace-nowrap transition-colors duration-200 hover:text-[#0E74B8] font-semibold"
                        :class="{ 'nav-link-active': isActive(item.href) }"
                    >
                        {{ item.text }}
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Right Section -->
        <div class="flex items-center space-x-6">
            <!-- Hiring Link -->
            <Link
                class="text-[#5932EA] font-bold transition-colors duration-200 hover:text-[#4D62D7] hiring-pulse"
                href="/hiring"
            >
                We are hiring!!
            </Link>

            <!-- Auth Section -->
            <div class="flex items-center relative" ref="dropdownRef">
                <template v-if="$page.props.auth.user">
                    <button
                        @click="dropdownOpen = !dropdownOpen"
                        class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 shadow-sm hover:shadow-md"
                    >
                        {{ $page.props.auth.user.name }}
                        <i
                            class="fas fa-user-circle text-blue-900 text-3xl ml-2"
                        ></i>
                    </button>

                    <div
                        v-show="dropdownOpen"
                        class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50"
                        style="top: 100%"
                    >
                        <Link
                            :href="route('profile.edit')"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            Profile
                        </Link>
                        <Link
                            v-if="
                                $page.props.auth.user.roles.some(
                                    (role) => role.role_name === 'admin'
                                )
                            "
                            :href="route('admin.dashboard')"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            Admin Dashboad
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            Log Out
                        </Link>
                    </div>
                </template>
                <template v-else>
                    <Link href="/login">
                        <button
                            class="bg-green-200 text-green-800 px-4 py-2 rounded shadow hover:shadow-md transition-all hover:bg-green-300"
                        >
                            Log In
                        </button>
                    </Link>
                    <i
                        class="fas fa-user-circle text-blue-900 text-3xl ml-4"
                    ></i>
                </template>
            </div>
        </div>
    </header>

    <div class="bg-white">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 py-12 mb-10">
            <div class="container mx-auto px-6">
                <h1
                    class="text-4xl font-bold text-center text-[#0E74B8] mb-4"
                    data-aos="fade-up"
                >
                    Legalitas Perusahaan
                </h1>
                <p
                    class="text-lg text-gray-600 text-center max-w-3xl mx-auto"
                    data-aos="fade-up"
                    data-aos-delay="200"
                >
                    Legalitas kami merupakan bukti komitmen kami terhadap
                    transparansi dan profesionalisme dalam memberikan layanan
                    terbaik kepada klien.
                </p>
            </div>
        </div>

        <!-- Content Section -->
        <section class="container mx-auto px-6 mb-20">
            <div
                class="flex flex-col md:flex-row items-center justify-center gap-10"
            >
                <!-- Legalitas Image -->
                <div
                    class="md:w-1/2"
                    data-aos="fade-right"
                    data-aos-duration="1500"
                >
                    <img
                        alt="Legalitas"
                        class="w-full rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-300"
                        src="/images/Legalitas.png"
                    />
                </div>

                <!-- Legalitas Details -->
                <div
                    class="md:w-1/2"
                    data-aos="fade-left"
                    data-aos-duration="1500"
                    data-aos-delay="300"
                >
                    <h2
                        class="text-2xl font-bold mb-6 text-[#0E74B8] border-b-2 border-[#5099D5] pb-2"
                    >
                        Dokumen Legalitas
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            v-for="(item, index) in legalitasItems"
                            :key="index"
                            class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 border-l-4"
                            :class="`border-l-[${item.color}]`"
                            data-aos="zoom-in"
                            :data-aos-delay="300 + index * 100"
                        >
                            <div class="flex items-center mb-3">
                                <i
                                    :class="`${item.icon} text-2xl`"
                                    :style="`color: ${item.color}`"
                                ></i>
                                <h3 class="text-xl font-semibold ml-3">
                                    {{ item.title }}
                                </h3>
                            </div>
                            <p class="text-gray-700">{{ item.number }}</p>
                            <p class="text-gray-500 text-sm">
                                Tanggal: {{ item.date }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="mt-8 bg-blue-50 p-6 rounded-lg border border-blue-200"
                        data-aos="fade-up"
                        data-aos-delay="600"
                    >
                        <h3 class="text-lg font-semibold text-[#0E74B8] mb-2">
                            Keunggulan Legalitas Kami
                        </h3>
                        <ul class="ml-5 space-y-2">
                            <li class="flex items-center">
                                <i
                                    class="fas fa-check-circle text-green-500 mr-2"
                                ></i>
                                <span
                                    >Terdaftar resmi di seluruh instansi
                                    terkait</span
                                >
                            </li>
                            <li class="flex items-center">
                                <i
                                    class="fas fa-check-circle text-green-500 mr-2"
                                ></i>
                                <span
                                    >Memenuhi semua persyaratan
                                    perundang-undangan</span
                                >
                            </li>
                            <li class="flex items-center">
                                <i
                                    class="fas fa-check-circle text-green-500 mr-2"
                                ></i>
                                <span>Pembaruan dokumen secara berkala</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
/* Menerapkan font ke seluruh komponen */
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

.hiring-pulse {
    animation: colorPulse 2s infinite;
}

@keyframes colorPulse {
    0% {
        color: #5932ea;
    }
    50% {
        color: #ff5733;
    }
    100% {
        color: #5932ea;
    }
}

/* Tambahan animasi untuk halaman legalitas */
@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
    100% {
        transform: translateY(0px);
    }
}

.float-animation {
    animation: float 5s ease-in-out infinite;
}
</style>
