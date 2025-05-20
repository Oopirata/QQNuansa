<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

const flipped = ref(false);
const page = usePage();
const dropdownRef = ref(null);
const dropdownOpen = ref(false);
const currentRoute = computed(() => page.url);

// Initialize everything on mount
onMounted(() => {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: "ease-in-out",
        once: false,
        mirror: true,
        offset: 50,
        delay: 100,
        anchorPlacement: "top-bottom",
    });

    // Add click outside listener
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});

const isActive = (href) => {
    return currentRoute.value === href;
};

// Click outside to close dropdown
const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        dropdownOpen.value = false;
    }
};

// Navigation menu items
const menuItems = [
    { text: "Tentang Kami", href: "/companyprofile" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

// Assessment content
const intellectualCapabilities = [
    "Intelegensi Umum",
    "Berpikir Analisa sintesa",
    "Berpikir abstrak",
    "Berpikir Konseptual",
    "Logika Berpikir",
    "Daya Tangkap",
    "Kemampuan Numerik",
];

const workAttitudes = [
    "Hasrat berprestasi",
    "Inisiatif",
    "Sistematika kerja",
    "Energi kerja",
    "Semangat kerja",
    "Ketekunan",
    "Peduli kualitas",
    "Keajegan kerja",
    "Tempo kerja",
    "Daya tahan terhadap tekanan",
];

const testTools = [
    "IST",
    "EPPS",
    "DISC",
    "PAPI KOSTICK",
    "KRAEPELIN",
    "16 PF",
    "PAULI",
    "MSDT",
    "APM",
    "WARTEG",
    "GRAFIS",
    "TKD",
    "MBTI",
    "POLA KEPRIBADIAN",
    "LOGIKA VERBAL",
    "KREATIVITAS VERBAL",
    "SPM",
    "CPM",
    "RMIB",
    "KUDER",
    "NST",
];
</script>

<template>
    <Head title="Layanan Psikotes" />

    <div
        class="min-h-screen bg-gradient-to-b from-white to-blue-50 overflow-hidden"
    >
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

        <!-- Main Content -->
        <main class="p-6 md:p-10">
            <div class="container mx-auto px-6 py-10 md:px-16 lg:px-24">
                <!-- Page Title Section -->
                <div class="text-center mb-20" data-aos="fade-up">
                    <h1
                        class="text-4xl md:text-5xl font-bold text-gray-800 mb-6"
                    >
                        Layanan Psikotes
                    </h1>
                    <p
                        class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"
                    >
                        Membantu partner dalam memilih kandidat karyawan yang
                        fit, sesuai dengan posisi jabatan yang dibutuhkan
                        berdasarkan potensi yang dimilikinya.
                    </p>
                </div>

                <!-- Process Cards -->
                <div class="grid md:grid-cols-2 gap-10 mb-20">
                    <div
                        class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow transform hover:-translate-y-1 duration-300"
                        data-aos="fade-up"
                        data-aos-delay="100"
                    >
                        <div class="text-blue-600 mb-5">
                            <i class="fas fa-check-circle text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-semibold mb-4">
                            Proses Seleksi
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Dilakukan dengan menggunakan beberapa metode antara
                            lain psikotes dan interview.
                        </p>
                    </div>

                    <div
                        class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow transform hover:-translate-y-1 duration-300"
                        data-aos="fade-up"
                        data-aos-delay="200"
                    >
                        <div class="text-blue-600 mb-5">
                            <i class="fas fa-brain text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-semibold mb-4">
                            Hasil Seleksi
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Berupa psikogram, uraian dinamika psikologis,
                            kelebihan dan kelemahan kandidat beserta
                            rekomendasi.
                        </p>
                    </div>
                </div>

                <!-- Flip Card Psikotest -->
                <div class="mb-20" data-aos="fade-up">
                    <h2
                        class="text-3xl font-bold mb-8 text-gray-800 text-center"
                    >
                        Siap untuk Psikotest?
                    </h2>
                    <div class="flex justify-center">
                        <div class="flip-card" @click="flipped = !flipped">
                            <div
                                class="flip-card-inner"
                                :class="{ flipped: flipped }"
                            >
                                <!-- Front -->
                                <div
                                    class="flip-card-front bg-indigo-100 rounded-xl p-8 flex flex-col justify-center items-center text-center shadow-lg"
                                >
                                    <i
                                        class="fas fa-user-check text-indigo-600 text-6xl mb-6"
                                    ></i>
                                    <h3
                                        class="text-2xl font-semibold text-gray-800 mb-2"
                                    >
                                        Klik untuk mulai Psikotest
                                    </h3>
                                    <p class="text-gray-600 mt-2">
                                        Dapatkan evaluasi mendalam
                                    </p>
                                </div>

                                <!-- Back -->
                                <div
                                    class="flip-card-back bg-white rounded-xl p-8 text-gray-700 shadow-lg flex flex-col justify-center items-center"
                                >
                                    <h3
                                        class="text-2xl font-semibold text-indigo-600 mb-4"
                                    >
                                        Ayo Mulai Psikotest
                                    </h3>
                                    <p class="text-gray-600 mb-6 text-center">
                                        Jawab pertanyaan dan dapatkan hasil tes
                                        berdasarkan aspek psikologis yang
                                        terukur.
                                    </p>
                                    <Link href="/psychotest/entry">
                                        <button
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-full transition duration-300 font-medium"
                                        >
                                            Masuk ke Halaman Psikotest
                                        </button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assessment Aspects Section -->
                <div
                    class="bg-white p-10 rounded-xl shadow-lg mb-20"
                    data-aos="fade-up"
                >
                    <h2 class="text-3xl font-bold mb-6 text-gray-800">
                        Aspek Penilaian
                    </h2>
                    <p class="text-lg text-gray-600 mb-10">
                        Kami melakukan penilaian komprehensif pada dua aspek
                        utama berikut:
                    </p>

                    <div class="grid md:grid-cols-2 gap-10">
                        <!-- Intellectual Capabilities -->
                        <div
                            class="bg-blue-50 p-8 rounded-xl hover:shadow-md transition-shadow"
                            data-aos="fade-right"
                        >
                            <h3
                                class="text-2xl font-bold mb-5 text-gray-800 flex items-center"
                            >
                                <i
                                    class="fas fa-brain text-blue-600 mr-4 text-2xl"
                                ></i>
                                Kemampuan Intelektual
                            </h3>
                            <ul class="space-y-4">
                                <li
                                    v-for="(
                                        item, index
                                    ) in intellectualCapabilities"
                                    :key="index"
                                    class="flex items-center text-gray-700"
                                >
                                    <i
                                        class="fas fa-check-circle text-green-500 w-5 h-5 mr-3"
                                    ></i>
                                    {{ item }}
                                </li>
                            </ul>
                        </div>

                        <!-- Work Attitudes -->
                        <div
                            class="bg-blue-50 p-8 rounded-xl hover:shadow-md transition-shadow"
                            data-aos="fade-left"
                        >
                            <h3
                                class="text-2xl font-bold mb-5 text-gray-800 flex items-center"
                            >
                                <i
                                    class="fas fa-user-tie text-blue-600 mr-4 text-2xl"
                                ></i>
                                Sikap Kerja
                            </h3>
                            <ul class="space-y-4">
                                <li
                                    v-for="(item, index) in workAttitudes"
                                    :key="index"
                                    class="flex items-center text-gray-700"
                                >
                                    <i
                                        class="fas fa-check-circle text-green-500 w-5 h-5 mr-3"
                                    ></i>
                                    {{ item }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tools Section -->
                <div
                    class="bg-white p-10 rounded-xl shadow-lg mb-20"
                    data-aos="fade-up"
                >
                    <h2 class="text-3xl font-bold mb-6 text-gray-800">
                        Alat Tes
                    </h2>
                    <p class="text-lg text-gray-600 mb-8">
                        Alat tes yang digunakan disesuaikan dengan level jabatan
                        yang dituju dan aspek yang akan digali.
                    </p>
                    <div
                        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                    >
                        <div
                            v-for="(tool, index) in testTools"
                            :key="index"
                            class="bg-blue-50 p-4 rounded-lg text-center text-gray-700 hover:bg-blue-100 transition-colors transform hover:-translate-y-1 duration-300 shadow-sm hover:shadow"
                            data-aos="zoom-in"
                            :data-aos-delay="index * 50"
                        >
                            {{ tool }}
                        </div>
                    </div>
                </div>

                <!-- Schedule Section -->
                <div
                    class="bg-white p-10 rounded-xl shadow-lg"
                    data-aos="fade-up"
                >
                    <h2 class="text-3xl font-bold mb-8 text-gray-800">
                        Jadwal Pelaksanaan
                    </h2>

                    <div class="grid md:grid-cols-2 gap-10">
                        <div
                            class="p-6 rounded-lg bg-gray-50"
                            data-aos="fade-right"
                        >
                            <h3
                                class="text-2xl font-semibold mb-5 flex items-center text-gray-800"
                            >
                                <i
                                    class="fas fa-clock text-blue-600 w-6 h-6 mr-3"
                                ></i>
                                Waktu Pelaksanaan
                            </h3>
                            <div class="space-y-4 text-gray-700 pl-2">
                                <p class="font-medium">
                                    Hari Kerja (Senin - Jumat):
                                </p>
                                <ul
                                    class="list-disc list-inside pl-6 space-y-2"
                                >
                                    <li>Sesi pagi: 08.30 – 12.00 WIB</li>
                                    <li>Sesi siang: 13.00 – 16.00 WIB</li>
                                </ul>
                                <p class="font-medium mt-4">
                                    Hari Sabtu dengan kesepakatan
                                </p>
                            </div>
                        </div>

                        <div
                            class="p-6 rounded-lg bg-gray-50"
                            data-aos="fade-left"
                        >
                            <h3
                                class="text-2xl font-semibold mb-5 flex items-center text-gray-800"
                            >
                                <i
                                    class="fas fa-map-pin text-blue-600 w-6 h-6 mr-3"
                                ></i>
                                Lokasi Pelaksanaan
                            </h3>
                            <div class="space-y-4 text-gray-700 pl-2">
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <span
                                            class="text-green-500 font-bold mr-2"
                                            >✓</span
                                        >
                                        Di QQNuansa Consultant sesuai
                                        kesepakatan waktu
                                    </li>
                                    <li class="flex items-start">
                                        <span
                                            class="text-green-500 font-bold mr-2"
                                            >✓</span
                                        >
                                        In-house di perusahaan sesuai
                                        kesepakatan
                                    </li>
                                    <li class="flex items-start">
                                        <span
                                            class="text-green-500 font-bold mr-2"
                                            >✓</span
                                        >
                                        Biaya transport gratis untuk peserta >5
                                        orang di Kota Pekalongan
                                    </li>
                                    <li class="flex items-start">
                                        <span
                                            class="text-green-500 font-bold mr-2"
                                            >✓</span
                                        >
                                        Biaya transport khusus untuk peserta <5
                                        atau di luar Kota Pekalongan
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
    transition: all 0.3s ease-in-out;
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
    font-weight: 600;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.flip-card {
    background-color: transparent;
    width: 340px;
    height: 340px;
    perspective: 1000px;
    cursor: pointer;
}

.flip-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
    transition: transform 0.8s;
}

.flip-card-inner.flipped {
    transform: rotateY(180deg);
}

.flip-card-front,
.flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 1rem;
}

.flip-card-back {
    transform: rotateY(180deg);
}
</style>
