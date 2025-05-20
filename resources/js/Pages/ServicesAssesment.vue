<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, onMounted, computed, onBeforeUnmount } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

const page = usePage();
const dropdownRef = ref(null);
const dropdownOpen = ref(false);
const currentRoute = computed(() => page.url);

onMounted(() => {
    AOS.init({
        duration: 800,
        easing: "ease-in-out",
        once: false,
        mirror: true,
        offset: 50,
        delay: 100,
        anchorPlacement: "top-bottom",
    });
    document.addEventListener("click", handleClickOutside);
});
onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        dropdownOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
const isActive = (href) => {
    return currentRoute.value === href;
};

const menuItems = [
    { text: "Tentang Kami", href: "/companyprofile" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

// Data for assessment page
const generalCompetencies = [
    "Achievement orientation",
    "Analytical thinking",
    "Conceptual thinking",
    "Customer service orientation",
    "Developing others",
    "Adapting to change",
    "Impact and influence",
    "Team leadership",
    "Problem solving",
    "Decision making",
    "Planning & organizing",
    "Teamwork & collaboration",
    "Communication",
    "Digital mindset",
];

const assessmentMethods = [
    "Potential review (psikotes)",
    "Critical incident",
    "Problem analysis",
    "Leaderless group discussion",
    "Presentation",
    "Behavioral event interview",
];

const reportSections = [
    "Profil potensi",
    "Profil kompetensi",
    "Executive summary",
    "Kesimpulan",
    "Saran pengembangan",
];
</script>

<template>
    <Head title="Services Assessment" />

    <div
        class="min-h-screen bg-gradient-to-b from-white to-blue-50 overflow-hidden"
    >
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

        <main class="p-10">
            <div class="container mx-auto py-12 px-8 md:px-16 lg:px-24">
                <!-- Hero Section -->
                <div class="text-center mb-16 px-4" data-aos="fade-up">
                    <h1 class="text-4xl font-bold text-gray-800 mb-4">
                        Layanan Assessment
                    </h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Assessment merupakan metode yang menggabungkan
                        penggalian data potensi dan kompetensi kandidat untuk
                        kebutuhan seleksi dan pengembangan karyawan.
                    </p>
                </div>

                <!-- Benefits Cards -->
                <div class="grid md:grid-cols-3 gap-8 mb-16 px-4">
                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        data-aos="fade-up"
                        data-aos-delay="100"
                    >
                        <div class="text-blue-600 mb-4">
                            <i class="fas fa-user-tie text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">
                            Seleksi Rekrutmen
                        </h3>
                        <p class="text-gray-600">
                            Membantu dalam proses seleksi dan rekrutmen karyawan
                            baru.
                        </p>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        data-aos="fade-up"
                        data-aos-delay="200"
                    >
                        <div class="text-blue-600 mb-4">
                            <i class="fas fa-chart-line text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">
                            Seleksi Promosi
                        </h3>
                        <p class="text-gray-600">
                            Evaluasi karyawan untuk keperluan promosi jabatan.
                        </p>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        data-aos="fade-up"
                        data-aos-delay="300"
                    >
                        <div class="text-blue-600 mb-4">
                            <i class="fas fa-graduation-cap text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">
                            Program Pengembangan
                        </h3>
                        <p class="text-gray-600">
                            Mendukung program pengembangan kompetensi karyawan.
                        </p>
                    </div>
                </div>

                <!-- Competency Aspects -->
                <div
                    class="bg-white p-8 rounded-xl shadow-lg mb-16 mx-4"
                    data-aos="fade-up"
                >
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">
                        Aspek Kompetensi yang Diuji
                    </h2>

                    <div class="grid md:grid-cols-2 gap-8">
                        <div
                            class="bg-blue-50 p-6 rounded-xl"
                            data-aos="fade-right"
                        >
                            <h3
                                class="text-xl font-bold mb-4 text-gray-800 flex items-center"
                            >
                                <i
                                    class="fas fa-building text-blue-600 mr-3"
                                ></i>
                                Spesifik Organisasi
                            </h3>
                            <p class="text-gray-700">
                                Bagi perusahaan yang telah mengembangkan Matrix
                                Kompetensi, kompetensi yang diuji akan
                                disesuaikan dengan matrix tersebut.
                            </p>
                        </div>

                        <div
                            class="bg-blue-50 p-6 rounded-xl"
                            data-aos="fade-left"
                        >
                            <h3
                                class="text-xl font-bold mb-4 text-gray-800 flex items-center"
                            >
                                <i class="fas fa-globe text-blue-600 mr-3"></i>
                                General
                            </h3>
                            <p class="text-gray-700 mb-4">
                                Bagi perusahaan yang belum mengembangkan Matrix
                                Kompetensi, kami menggunakan kompetensi umum
                                yang disesuaikan dengan jabatan dan kebutuhan
                                spesifik perusahaan.
                            </p>
                            <div class="grid grid-cols-2 gap-2">
                                <div
                                    v-for="(
                                        competency, index
                                    ) in generalCompetencies"
                                    :key="index"
                                    class="flex items-center text-gray-700"
                                >
                                    <i
                                        class="fas fa-check text-green-500 w-4 h-4 mr-2"
                                    ></i>
                                    <span class="text-sm">{{
                                        competency
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assessment Methods -->
                <div
                    class="bg-white p-8 rounded-xl shadow-lg mb-16 mx-4"
                    data-aos="fade-up"
                >
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">
                        Metode Assessment
                    </h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div
                            v-for="(method, index) in assessmentMethods"
                            :key="index"
                            class="bg-blue-50 p-4 rounded-lg text-center"
                            data-aos="zoom-in"
                            :data-aos-delay="index * 100"
                        >
                            <i
                                class="fas fa-clipboard-check text-blue-600 text-xl mb-2"
                            ></i>
                            <p class="text-gray-700">{{ method }}</p>
                        </div>
                    </div>
                </div>

                <!-- Report Format -->
                <div
                    class="bg-white p-8 rounded-xl shadow-lg mb-16 mx-4"
                    data-aos="fade-up"
                >
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">
                        Format Laporan
                    </h2>
                    <div class="grid md:grid-cols-5 gap-4">
                        <div
                            v-for="(section, index) in reportSections"
                            :key="index"
                            class="bg-blue-50 p-4 rounded-lg text-center"
                            data-aos="flip-left"
                            :data-aos-delay="index * 100"
                        >
                            <p class="text-gray-700 font-medium">
                                {{ section }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Schedule Section -->
                <div
                    class="bg-white p-8 rounded-xl shadow-lg mx-4"
                    data-aos="fade-up"
                >
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">
                        Jadwal Pelaksanaan
                    </h2>

                    <div class="grid md:grid-cols-2 gap-8">
                        <div data-aos="fade-right">
                            <h3
                                class="text-xl font-semibold mb-4 flex items-center"
                            >
                                <i
                                    class="fas fa-clock text-blue-600 w-6 h-6 mr-2"
                                ></i>
                                Waktu Pelaksanaan
                            </h3>
                            <div class="space-y-3 text-gray-700">
                                <p>Hari Kerja (Senin - Jumat):</p>
                                <ul class="list-disc list-inside pl-4">
                                    <li>Sesi pagi: 08.30 – 12.00 WIB</li>
                                    <li>Sesi siang: 13.00 – 16.00 WIB</li>
                                </ul>
                                <p>Hari Sabtu dengan kesepakatan</p>
                            </div>
                        </div>

                        <div data-aos="fade-left">
                            <h3
                                class="text-xl font-semibold mb-4 flex items-center"
                            >
                                <i
                                    class="fas fa-map-pin text-blue-600 w-6 h-6 mr-2"
                                ></i>
                                Lokasi dan Biaya
                            </h3>
                            <div class="space-y-3 text-gray-700">
                                <ul class="space-y-2">
                                    <li>
                                        ✓ Di QQNuansa Consultant sesuai
                                        kesepakatan waktu
                                    </li>
                                    <li>
                                        ✓ In-house di perusahaan sesuai
                                        kesepakatan
                                    </li>
                                    <li>
                                        ✓ Biaya transport gratis untuk peserta
                                        >5 orang di Kota Pekalongan
                                    </li>
                                    <li>
                                        ✓ Biaya transport khusus untuk peserta
                                        <5 atau di luar Kota Pekalongan
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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
/* Keep your existing styles */
</style>
