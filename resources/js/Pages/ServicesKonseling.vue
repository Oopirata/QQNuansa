// Services.vue
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

const dropdownRef = ref(null);
const dropdownOpen = ref(false);
// Menu items
const menuItems = [
    { text: "Tentang Kami", href: "/" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

// Data lists
const tujuanList = [
    {
        title: "Pemahaman Diri",
        description:
            "Membantu individu mengenali perasaan, pola pikir, dan perilaku mereka secara lebih baik.",
    },
    {
        title: "Pengambilan Keputusan",
        description:
            "Membimbing individu dalam mengambil keputusan yang tepat untuk masalah yang dihadapi.",
    },
    {
        title: "Kesejahteraan Mental",
        description:
            "Mengurangi stres, kecemasan, atau depresi, serta meningkatkan kesehatan mental dan emosional.",
    },
];

const metodeList = [
    {
        title: "Pendekatan Psikodinamik",
        description:
            "Berdasarkan teori Sigmund Freud, metode ini fokus pada pengalaman masa lalu dan bagaimana hal tersebut mempengaruhi perilaku atau emosi di masa kini.",
        techniques: "Asosiasi bebas, Interpretasi mimpi",
    },
    {
        title: "Pendekatan Kognitif-Perilaku (CBT)",
        description:
            "Metode ini bertujuan untuk mengubah pola pikir negatif dan perilaku maladaptif dengan strategi yang lebih positif dan produktif.",
        techniques: "Reframing, Pengelolaan kecemasan, Eksposur bertahap",
    },
    {
        title: "Pendekatan Humanistik (Client-Centered Therapy)",
        description:
            "Metode ini menekankan empati, penerimaan tanpa syarat, dan dukungan emosional. Konselor berperan sebagai fasilitator yang membantu klien menemukan solusinya sendiri.",
        techniques:
            "Refleksi perasaan, Non-direktif (Konselor tidak memberikan solusi langsung, tetapi mendorong klien menemukan jawabannya sendiri.)",
    },
];

const manfaatList = [
    {
        title: "Mengurangi Stres dan Kecemasan",
        description:
            "Konseling membantu klien mengenali sumber stres atau kecemasan dan memberikan teknik untuk mengatasinya secara efektif.",
    },
    {
        title: "Meningkatkan Kepercayaan Diri",
        description:
            "Klien belajar menghargai potensi dan kekuatannya sendiri melalui dukungan dan pandangan yang lebih positif.",
    },
];
</script>

<template>
    <Head title="Services Konseling" />
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
                <section class="mb-16 px-4" data-aos="fade-up">
                    <h1
                        class="text-4xl font-bold text-gray-900 mb-6 text-center"
                    >
                        Layanan Konseling
                    </h1>
                    <div class="prose max-w-none">
                        <p
                            class="text-center text-xl text-gray-700 max-w-3xl mx-auto"
                        >
                            Konseling adalah proses pemberian bantuan yang
                            dilakukan oleh seorang profesional (konselor) kepada
                            individu atau kelompok yang menghadapi masalah,
                            dengan tujuan membantu mereka memahami, mengatasi,
                            dan menyelesaikan masalah tersebut.
                        </p>
                    </div>
                </section>

                <!-- Tujuan Section -->
                <section
                    class="mb-16 px-4"
                    data-aos="fade-up"
                    data-aos-delay="100"
                >
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Tujuan Konseling
                    </h2>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                    >
                        <div
                            v-for="(tujuan, index) in tujuanList"
                            :key="index"
                            class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        >
                            <h3
                                class="text-xl font-semibold text-gray-800 mb-3"
                            >
                                {{ tujuan.title }}
                            </h3>
                            <p class="text-gray-600">
                                {{ tujuan.description }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Metode Section -->
                <section
                    class="mb-16 px-4"
                    data-aos="fade-up"
                    data-aos-delay="200"
                >
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Metode Konseling
                    </h2>
                    <div class="space-y-6">
                        <div
                            v-for="(metode, index) in metodeList"
                            :key="index"
                            class="bg-white p-8 rounded-xl shadow-lg"
                        >
                            <h3
                                class="text-xl font-semibold text-gray-800 mb-3"
                            >
                                {{ metode.title }}
                            </h3>
                            <p class="text-gray-600 mb-4">
                                {{ metode.description }}
                            </p>
                            <div class="pl-4 border-l-4 border-blue-500">
                                <p class="text-gray-700">
                                    Teknik: {{ metode.techniques }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Manfaat Section -->
                <section
                    class="mb-16 px-4"
                    data-aos="fade-up"
                    data-aos-delay="300"
                >
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Manfaat Konseling
                    </h2>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="(manfaat, index) in manfaatList"
                            :key="index"
                            class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        >
                            <h3
                                class="text-xl font-semibold text-gray-800 mb-3"
                            >
                                {{ manfaat.title }}
                            </h3>
                            <p class="text-gray-600">
                                {{ manfaat.description }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Jadwal Section -->
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
                                Lokasi Pelaksanaan
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
</style>
