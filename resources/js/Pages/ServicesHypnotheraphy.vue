<!-- Content Section -->
<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";

// Page and route handling
const page = usePage();
const currentRoute = computed(() => page.url);

const isActive = (href) => {
    return currentRoute.value === href;
};

// Menu items remain the same
const menuItems = [
    { text: "Tentang Kami", href: "/" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

// Hypnotherapy data
const problems = [
    {
        category: "Masalah Psikologis dan Emosional",
        items: [
            "Stres dan Kecemasan: Membantu menenangkan pikiran dan mengelola kecemasan",
            "Depresi Ringan: Membantu membangun pola pikir positif dan meningkatkan motivasi hidup",
            "Trauma dan PTSD: Membantu mengatasi pengalaman traumatis secara perlahan",
            "Fobia dan Ketakutan Berlebihan: Seperti fobia ketinggian, ruang sempit, atau serangga",
        ],
        icon: "fas fa-brain",
    },
    {
        category: "Masalah Kebiasaan Buruk",
        items: [
            "Kecanduan Merokok atau Alkohol: Mengubah kebiasaan buruk dengan sugesti positif",
            "Makan Berlebihan atau Emotional Eating: Membantu mengontrol nafsu makan dan pola makan yang sehat",
            "Menggigit Kuku atau Kebiasaan Lain yang Tidak Diinginkan",
        ],
        icon: "fas fa-ban",
    },
    {
        category: "Masalah Fisik dan Kesehatan",
        items: [
            "Gangguan Tidur (Insomnia): Membantu menciptakan pola tidur yang lebih sehat",
            "Nyeri Kronis atau Psikosomatis: Mengelola rasa sakit yang tidak teratasi dengan pengobatan medis",
            "Migrain dan Sakit Kepala Berulang",
        ],
        icon: "fas fa-heartbeat",
    },
];

const therapyProcess = [
    {
        step: "Konsultasi Awal (Assessment)",
        description:
            "Terapis melakukan diskusi untuk memahami masalah klien, kebutuhan, serta tujuan dari sesi hypnotherapy. Terapis juga akan menjelaskan apa itu hypnotherapy dan bagaimana prosesnya bekerja.",
        goal: "Mengidentifikasi masalah dan menetapkan tujuan terapi",
        icon: "fas fa-clipboard-list",
    },
    {
        step: "Induksi (Hypnotic Induction)",
        description:
            "Terapis memandu klien untuk masuk ke kondisi relaksasi mendalam menggunakan teknik pernapasan, visualisasi, dan sugesti verbal.",
        techniques: [
            "Teknik Pernapasan: Membantu menenangkan pikiran dan tubuh",
            "Visualisasi: Mengarahkan klien membayangkan situasi yang menenangkan",
            "Sugesti Verbal: Kata-kata yang membantu klien mencapai kondisi trance",
        ],
        icon: "fas fa-cloud",
    },
    {
        step: "Fase Terapi (Suggestion Therapy)",
        description:
            "Saat klien berada dalam kondisi trance, terapis memberikan sugesti positif yang sesuai dengan tujuan terapi.",
        examples: [
            "Kamu merasa lebih percaya diri setiap kali berbicara di depan umum",
            "Rasa stres dan cemas perlahan menghilang, digantikan dengan ketenangan",
        ],
        icon: "fas fa-comment-dots",
    },
    {
        step: "Penguatan dan Kembali ke Kesadaran",
        description:
            "Terapis secara perlahan membawa klien kembali ke kondisi sadar penuh dengan menghitung mundur dan memberikan instruksi agar klien merasa segar dan rileks.",
        icon: "fas fa-arrow-up",
    },
    {
        step: "Evaluasi dan Diskusi Pasca-Sesi",
        description:
            "Setelah sesi selesai, terapis mendiskusikan pengalaman klien selama terapi dan memberikan saran atau latihan tambahan untuk memperkuat hasil terapi.",
        icon: "fas fa-chart-line",
    },
];

onMounted(() => {
    AOS.init({
        duration: 800,
        easing: "ease-in-out",
        once: false,
        mirror: true,
        offset: 50,
        delay: 100,
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

const dropdownRef = ref(null);
const dropdownOpen = ref(false);
</script>

<template>
    <Head title="Services Hypnotherapy" />
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
                <!-- Introduction Section -->
                <section class="mb-16" data-aos="fade-up">
                    <h1
                        class="text-4xl font-bold text-gray-900 mb-6 text-center"
                    >
                        Layanan Hypnotherapy
                    </h1>
                    <div class="prose max-w-none">
                        <p
                            class="text-center text-xl text-gray-700 max-w-3xl mx-auto"
                        >
                            Hypnotherapy atau hipnoterapi adalah metode terapi
                            yang menggunakan teknik hipnosis untuk membantu
                            seseorang mencapai kondisi relaksasi mendalam
                            (trance) dan meningkatkan fokus pikiran.
                        </p>
                    </div>
                </section>

                <!-- Problems Section -->
                <section class="mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Masalah yang Bisa Diatasi
                    </h2>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="(category, index) in problems"
                            :key="index"
                            class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        >
                            <div class="flex items-center mb-4">
                                <i
                                    :class="
                                        category.icon +
                                        ' text-3xl text-purple-600 mr-4'
                                    "
                                ></i>
                                <h3 class="text-xl font-semibold text-gray-800">
                                    {{ category.category }}
                                </h3>
                            </div>
                            <ul class="space-y-3">
                                <li
                                    v-for="(item, iIndex) in category.items"
                                    :key="iIndex"
                                    class="flex items-start"
                                >
                                    <i
                                        class="fas fa-check text-green-500 mt-1 mr-2"
                                    ></i>
                                    <span class="text-gray-600">{{
                                        item
                                    }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Process Section -->
                <section class="mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Proses Hypnotherapy
                    </h2>
                    <div class="relative">
                        <div
                            class="absolute top-0 bottom-0 left-1/2 w-0.5 bg-purple-200 transform -translate-x-1/2"
                        ></div>
                        <div class="space-y-8">
                            <div
                                v-for="(step, index) in therapyProcess"
                                :key="index"
                                class="flex items-start"
                                data-aos="fade-up"
                            >
                                <div
                                    class="bg-white p-4 rounded-full shadow-lg z-10 mr-4"
                                >
                                    <i
                                        :class="
                                            step.icon +
                                            ' text-2xl text-purple-600'
                                        "
                                    ></i>
                                </div>
                                <div
                                    class="bg-white p-6 rounded-xl shadow-lg flex-grow"
                                >
                                    <h3
                                        class="text-xl font-semibold text-gray-800 mb-3"
                                    >
                                        {{ step.step }}
                                    </h3>
                                    <p class="text-gray-600 mb-3">
                                        {{ step.description }}
                                    </p>

                                    <div
                                        v-if="step.techniques"
                                        class="mt-4 bg-purple-50 p-4 rounded-lg"
                                    >
                                        <h4
                                            class="font-semibold text-gray-800 mb-2"
                                        >
                                            Teknik yang Digunakan:
                                        </h4>
                                        <ul class="space-y-2">
                                            <li
                                                v-for="(
                                                    technique, tIndex
                                                ) in step.techniques"
                                                :key="tIndex"
                                                class="flex items-start"
                                            >
                                                <i
                                                    class="fas fa-star text-purple-500 mt-1 mr-2"
                                                ></i>
                                                <span class="text-gray-600">{{
                                                    technique
                                                }}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div
                                        v-if="step.examples"
                                        class="mt-4 bg-purple-50 p-4 rounded-lg"
                                    >
                                        <h4
                                            class="font-semibold text-gray-800 mb-2"
                                        >
                                            Contoh Sugesti:
                                        </h4>
                                        <ul class="space-y-2">
                                            <li
                                                v-for="(
                                                    example, eIndex
                                                ) in step.examples"
                                                :key="eIndex"
                                                class="flex items-start"
                                            >
                                                <i
                                                    class="fas fa-quote-left text-purple-500 mt-1 mr-2"
                                                ></i>
                                                <span
                                                    class="text-gray-600 italic"
                                                    >{{ example }}</span
                                                >
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Duration Section -->
                <section class="mb-16" data-aos="fade-up">
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                            Durasi dan Frekuensi
                        </h2>
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="bg-purple-50 p-6 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <i
                                        class="fas fa-clock text-purple-600 text-2xl mr-3"
                                    ></i>
                                    <h3
                                        class="text-xl font-semibold text-gray-800"
                                    >
                                        Durasi per Sesi
                                    </h3>
                                </div>
                                <p class="text-gray-600">
                                    30-60 menit per sesi
                                </p>
                            </div>
                            <div class="bg-purple-50 p-6 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <i
                                        class="fas fa-calendar-alt text-purple-600 text-2xl mr-3"
                                    ></i>
                                    <h3
                                        class="text-xl font-semibold text-gray-800"
                                    >
                                        Frekuensi
                                    </h3>
                                </div>
                                <p class="text-gray-600">
                                    3-6 sesi, tergantung pada tingkat keparahan
                                    masalah
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
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

.tag-link {
    display: inline-block;
    padding: 8px 16px;
    background-color: #f8f8f8;
    border-radius: 100px;
    font-size: 14px;
    color: #333;
    transition: all 0.2s ease-in-out;
    text-decoration: none;
    white-space: nowrap;
}

.tag-link:hover {
    background-color: #e8e8e8;
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .tag-link {
        font-size: 12px;
        padding: 6px 12px;
    }
}
</style>
