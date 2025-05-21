<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount } from "vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

// Page and route handling
const page = usePage();
const currentRoute = computed(() => page.url);

// Dropdown state
const dropdownOpen = ref(false);
const dropdownRef = ref(null);

// Active route checker
const isActive = (href) => {
    return currentRoute.value === href;
};

// Dropdown handlers
const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        dropdownOpen.value = false;
    }
};

// Lifecycle hooks with proper error handling
onMounted(() => {
    try {
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 50,
        });
    } catch (error) {
        console.error("AOS initialization failed:", error);
    }

    // Add dropdown listener
    document.addEventListener("click", closeDropdown);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", closeDropdown);
});

// Navigation items
const menuItems = [
    { text: "Tentang Kami", href: "/companyprofile" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

// Service tags
const topRowTags = [
    {
        text: "Assessment",
        href: "/serviceassesment",
        icon: "fas fa-clipboard-check",
    },
    { text: "Psikotes", href: "/servicepsikotest", icon: "fas fa-brain" },
    { text: "Konseling", href: "/servicekonseling", icon: "fas fa-comments" },
    {
        text: "Coaching",
        href: "/servicecoaching",
        icon: "fas fa-user-graduate",
    },
    { text: "Outbond", href: "/servicesoutbond", icon: "fas fa-mountain" },
];

const bottomRowTags = [
    {
        text: "Seminar",
        href: "/servicesseminar",
        icon: "fas fa-chalkboard-teacher",
    },
    { text: "Training", href: "/servicestraining", icon: "fas fa-dumbbell" },
    {
        text: "Hypnotheraphy",
        href: "/serviceshypnotheraphy",
        icon: "fas fa-spa",
    },
    {
        text: "Finger Test STIFIN",
        href: "/servicesSTIFIN",
        icon: "fas fa-fingerprint",
    },
];

// Service categories
const serviceCategories = [
    {
        title: "CONSULTING",
        description:
            "Jasa konsultan di bidang manajemen sumber daya manusia, terutama untuk pengembangan organisasi.",
        icon: "fas fa-comments-dollar",
        color: "from-blue-600 to-cyan-500",
    },
    {
        title: "LEARNING CENTER",
        description:
            "Jasa pelatihan dan pengembangan sumber daya manusia melalui berbagai program seperti inhouse training, public training, dan outbound.",
        icon: "fas fa-graduation-cap",
        color: "from-green-600 to-teal-500",
    },
    {
        title: "RECRUITMENT AND ASSESSMENT",
        description:
            "Mencari, menyeleksi, dan merekomendasikan talenta terbaik untuk dikembangkan lebih lanjut dalam dunia industri maupun pendidikan.",
        icon: "fas fa-user-check",
        color: "from-purple-600 to-indigo-500",
    },
];
</script>

<template>
    <Head title="Layanan" />

    <div class="bg-gradient-to-b from-white to-blue-50 min-h-screen">
        <!-- Header Section -->
        <header
            class="p-4 flex justify-between items-center mt-2 container mx-auto"
            data-aos="fade-down"
        >
            <!-- Logo Section -->
            <div class="flex items-center">
                <img
                    alt="Company Logo"
                    class="h-14 w-20"
                    src="/images/QQ crop.png"
                />
                <img
                    alt="Company Logo"
                    class="ml-2 h-14 w-36"
                    src="/images/Nuansa crop.png"
                />
            </div>

            <!-- Navigation Menu Section -->
            <nav class="flex-grow px-20">
                <div
                    class="pb-2 border-b-4 border-[#5099D5] w-fit mx-auto rounded-lg shadow-md"
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

        <h1
            class="text-center text-3xl font-bold mt-16 relative"
            data-aos="fade-down"
        >
            <span class="relative inline-block">
                LAYANAN KAMI
                <span
                    class="absolute bottom-0 left-0 w-full h-1 bg-blue-500 rounded-full"
                ></span>
            </span>
        </h1>

        <!-- Main Content -->
        <main class="p-8">
            <div class="max-w-7xl mx-auto mt-8">
                <!-- Service Categories -->
                <div class="grid md:grid-cols-3 gap-6 mb-16" data-aos="fade-up">
                    <div
                        v-for="(category, index) in serviceCategories"
                        :key="index"
                        class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow transform hover:-translate-y-1 duration-300 flex flex-col"
                    >
                        <div
                            :class="`bg-gradient-to-r ${category.color} p-6 text-white`"
                        >
                            <div class="flex items-center justify-center mb-4">
                                <i :class="`${category.icon} text-4xl`"></i>
                            </div>
                            <h3 class="text-xl font-bold text-center">
                                {{ category.title }}
                            </h3>
                        </div>
                        <div class="p-6 flex-grow">
                            <p class="text-gray-700">
                                {{ category.description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Service Tags Section -->
                <div
                    class="py-8 px-4 mb-12 bg-white rounded-xl shadow-lg"
                    data-aos="fade-up"
                >
                    <h2 class="text-2xl font-bold mb-8 text-center">
                        Layanan Spesifik Kami
                    </h2>

                    <div class="flex flex-wrap gap-4 justify-center mb-6">
                        <Link
                            v-for="tag in topRowTags"
                            :key="tag.text"
                            :href="tag.href"
                            class="tag-link group"
                        >
                            <i
                                :class="`${tag.icon} mr-2 group-hover:text-blue-600`"
                            ></i>
                            {{ tag.text }}
                        </Link>
                    </div>

                    <div class="flex flex-wrap gap-4 justify-center">
                        <Link
                            v-for="tag in bottomRowTags"
                            :key="tag.text"
                            :href="tag.href"
                            class="tag-link group"
                        >
                            <i
                                :class="`${tag.icon} mr-2 group-hover:text-blue-600`"
                            ></i>
                            {{ tag.text }}
                        </Link>
                    </div>
                </div>

                <!-- Gallery Grid -->
                <div data-aos="fade-up">
                    <h2 class="text-2xl font-bold mb-8 text-center">
                        Dokumentasi Kegiatan
                    </h2>
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto"
                    >
                        <div
                            class="relative overflow-hidden rounded-xl shadow-lg group"
                            data-aos="zoom-in"
                            data-aos-delay="100"
                        >
                            <img
                                src="/images/sakjose.jpeg"
                                alt="Gallery Image 1"
                                class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end"
                            >
                                <div class="p-4 text-white">
                                    <h3 class="font-bold">
                                        Pelatihan Teamwork
                                    </h3>
                                    <p class="text-sm">
                                        Membangun kolaborasi tim yang efektif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="relative overflow-hidden rounded-xl shadow-lg group"
                            data-aos="zoom-in"
                            data-aos-delay="200"
                        >
                            <img
                                src="/images/asik.jpg"
                                alt="Gallery Image 2"
                                class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end"
                            >
                                <div class="p-4 text-white">
                                    <h3 class="font-bold">
                                        Workshop Pengembangan SDM
                                    </h3>
                                    <p class="text-sm">
                                        Strategi pengembangan potensi karyawan
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="relative overflow-hidden rounded-xl shadow-lg group"
                            data-aos="zoom-in"
                            data-aos-delay="300"
                        >
                            <img
                                src="/images/seminar 2.jpeg"
                                alt="Gallery Image 3"
                                class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end"
                            >
                                <div class="p-4 text-white">
                                    <h3 class="font-bold">
                                        Seminar Kepemimpinan
                                    </h3>
                                    <p class="text-sm">
                                        Membangun jiwa kepemimpinan yang tangguh
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div
                    class="bg-white p-8 rounded-xl shadow-lg mt-16"
                    data-aos="fade-up"
                >
                    <h2 class="text-2xl font-bold mb-6 text-center">
                        Tentang Layanan Kami
                    </h2>
                    <p
                        class="text-lg text-gray-700 leading-relaxed max-w-4xl mx-auto"
                    >
                        PT. Ara Nuansa Katumbiri menawarkan tiga layanan utama
                        yang sesuai dengan keahlian dan bidang jasa yang
                        dikelola. Layanan pertama adalah
                        <span class="font-semibold text-blue-700"
                            >CONSULTING</span
                        >, yang berfokus pada jasa konsultan di bidang manajemen
                        sumber daya manusia, terutama untuk pengembangan
                        organisasi. Layanan kedua adalah
                        <span class="font-semibold text-green-700"
                            >LEARNING CENTER</span
                        >, yang menyediakan jasa pelatihan dan pengembangan
                        sumber daya manusia melalui berbagai program, seperti
                        inhouse training, public training, dan outbound, yang
                        dirancang untuk memenuhi kebutuhan spesifik organisasi
                        atau perusahaan. Layanan ketiga adalah
                        <span class="font-semibold text-purple-700"
                            >RECRUITMENT AND ASSESSMENT</span
                        >, yang bertujuan untuk mencari, menyeleksi, dan
                        merekomendasikan talenta terbaik untuk dikembangkan
                        lebih lanjut, baik dalam dunia industri maupun dunia
                        pendidikan.
                    </p>
                </div>

                <!-- Bottom Image with Overlay -->
                <div class="max-w-6xl mx-auto mt-16" data-aos="fade-up">
                    <div class="relative overflow-hidden rounded-xl shadow-xl">
                        <img
                            src="/images/seminar.jpg"
                            alt="seminar"
                            class="w-full object-cover h-96"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-900/70 to-transparent flex items-center"
                        >
                            <div class="text-white p-12">
                                <h2 class="text-3xl font-bold mb-4">
                                    Bangun SDM unggul bersama kami.
                                </h2>
                                <p class="text-xl mb-6 max-w-md">
                                    Kami siap mendukung pengembangan SDM
                                    organisasi Anda bersama tim ahli terpercaya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonials Section -->
                <div class="py-16" data-aos="fade-up">
                    <h2 class="text-2xl font-bold mb-12 text-center">
                        Apa Kata Klien Kami
                    </h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <div class="text-yellow-400 text-xl">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="italic text-gray-600 mb-6">
                                "Program training yang sangat terstruktur dan
                                relevan dengan kebutuhan perusahaan kami. Tim
                                fasilitator sangat profesional dan mampu
                                menyampaikan materi dengan menarik."
                            </p>
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500"
                                >
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold">Budi Santoso</h4>
                                    <p class="text-gray-500 text-sm">
                                        HR Manager, PT Maju Bersama
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <div class="text-yellow-400 text-xl">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="italic text-gray-600 mb-6">
                                "Layanan assessment yang sangat membantu kami
                                dalam proses rekrutmen. Metodologi yang
                                digunakan sangat akurat dalam mengidentifikasi
                                talenta yang sesuai dengan kebutuhan kami."
                            </p>
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500"
                                >
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold">Siti Rahma</h4>
                                    <p class="text-gray-500 text-sm">
                                        Direktur SDM, CV Sejahtera
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <div class="text-yellow-400 text-xl">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                            <p class="italic text-gray-600 mb-6">
                                "Konsultasi pengembangan organisasi yang
                                diberikan sangat membantu transformasi
                                perusahaan kami. Pendekatan yang komprehensif
                                dan solusi yang diberikan benar-benar sesuai
                                dengan tantangan yang kami hadapi."
                            </p>
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500"
                                >
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold">Ahmad Fajar</h4>
                                    <p class="text-gray-500 text-sm">
                                        CEO, PT Inovasi Indonesia
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer CTA -->
    </div>
</template>

<style scoped>
/* Base styles */
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}

/* Navigation styles */
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

/* Dropdown styles */
.text-gray-700 {
    pointer-events: auto !important;
    cursor: pointer !important;
}

.hover\:bg-gray-100:hover {
    background-color: #f3f4f6 !important;
}

/* Tag styles */
.tag-link {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 100px;
    font-size: 15px;
    font-weight: 500;
    color: #4b5563;
    transition: all 0.3s ease;
    text-decoration: none;
    white-space: nowrap;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.tag-link:hover {
    background-color: #f9fafb;
    border-color: #d1d5db;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: #1e40af;
}

/* Responsive styles */
@media (max-width: 768px) {
    .tag-link {
        font-size: 14px;
        padding: 8px 16px;
    }
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
</style>
