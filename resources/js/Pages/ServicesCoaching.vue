# Script section
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

// Menu items
const menuItems = [
    { text: "Tentang Kami", href: "/" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

// Data untuk coaching types
const coachingTypes = [
    {
        title: "Life Coaching",
        description:
            "Life coaching membantu individu dalam meningkatkan kualitas hidup dan mencapai keseimbangan di berbagai aspek, seperti hubungan, kesehatan, keuangan, dan pengembangan diri.",
        examples: [
            "Meningkatkan kepercayaan diri",
            "Mengatasi kebiasaan buruk",
            "Menemukan tujuan hidup",
        ],
        suitableFor:
            "Orang yang merasa terjebak dalam rutinitas atau ingin membuat perubahan besar dalam hidupnya.",
    },
    {
        title: "Business Coaching",
        description:
            "Business coaching membantu pemilik bisnis, manajer, atau eksekutif dalam mengembangkan strategi bisnis dan meningkatkan kinerja organisasi.",
        examples: [
            "Membuat strategi pengembangan bisnis",
            "Meningkatkan keterampilan kepemimpinan",
            "Mengelola konflik dalam tim",
        ],
        suitableFor:
            "Pengusaha, pemimpin bisnis, atau manajer yang ingin membawa bisnis mereka ke level berikutnya.",
    },
    {
        title: "Career Coaching",
        description:
            "Career coaching mendukung individu dalam menentukan, merencanakan, dan mengembangkan karier.",
        examples: [
            "Membantu transisi karier",
            "Menyusun strategi pencarian kerja",
            "Mengembangkan keterampilan wawancara dan personal branding",
        ],
        suitableFor:
            "Seseorang yang ingin berganti profesi, mencari pekerjaan baru, atau mengembangkan potensi di tempat kerja.",
    },
];

// Data untuk coaching methods
const coachingMethods = [
    {
        title: "Pendekatan GROW",
        description:
            "Framework coaching yang populer membantu klien untuk menentukan tujuan, memahami kondisi saat ini, mengeksplorasi opsi, dan membuat rencana tindakan.",
        steps: [
            {
                letter: "G",
                meaning: "Goal",
                desc: "Menentukan tujuan spesifik yang ingin dicapai",
            },
            {
                letter: "R",
                meaning: "Reality",
                desc: "Mengevaluasi situasi saat ini dan mengidentifikasi hambatan",
            },
            {
                letter: "O",
                meaning: "Options",
                desc: "Mengeksplorasi berbagai opsi dan solusi yang mungkin",
            },
            {
                letter: "W",
                meaning: "Will",
                desc: "Membuat rencana tindakan dan komitmen untuk mencapainya",
            },
        ],
    },
    {
        title: "Solution-Focused Coaching",
        description:
            "Fokus pada membangun solusi daripada menganalisis masalah. Membantu klien menemukan solusi dari pengalaman mereka yang berhasil sebelumnya.",
        techniques: [
            "Pertanyaan 'keajaiban' untuk membayangkan masa depan tanpa masalah",
            "Scaling (penilaian dari 1-10) untuk mengukur kemajuan",
        ],
    },
    {
        title: "Cognitive Behavioral Coaching (CBC)",
        description:
            "Menggunakan prinsip-prinsip CBT untuk membantu klien mengubah pola pikir yang membatasi menjadi lebih positif dan produktif.",
        techniques: [
            "Identifikasi keyakinan yang membatasi",
            "Reframing (mengubah cara pandang)",
            "Self-monitoring dan penguatan perilaku positif",
        ],
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
        anchorPlacement: "top-bottom",
    });
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
    <Head title="Services Coaching" />
    <div
        class="min-h-screen bg-gradient-to-b from-white to-blue-50 overflow-hidden"
    >
        <!-- Header section -->
        <header
            class="p-4 flex justify-between items-center mt-2 container mx-auto"
            data-aos="fade-down"
        >
            <div></div>
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
                    class="pb-2 border-b-4 border-[#5099D5] w-fit mx-auto rounded-lg"
                >
                    <div class="flex space-x-8 px-4">
                        <Link
                            v-for="(item, index) in menuItems"
                            :key="index"
                            :href="item.href"
                            class="nav-link relative text-gray-700 text-center whitespace-nowrap transition-colors duration-200 hover:text-[#0E74B8]"
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
                    class="text-[#5932EA] font-bold transition-colors duration-200 hover:text-[#4D62D7]"
                    href="/hiring"
                >
                    We are hiring!!
                </Link>

                <!-- Auth Section -->
                <div class="flex items-center relative" ref="dropdownRef">
                    <template v-if="$page.props.auth.user">
                        <button
                            @click="dropdownOpen = !dropdownOpen"
                            class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
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
                                Admin Dashboard
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
                                class="bg-green-200 text-green-800 px-4 py-2 rounded"
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
                        Layanan Coaching
                    </h1>
                    <div class="prose max-w-none">
                        <p
                            class="text-center text-xl text-gray-700 max-w-3xl mx-auto"
                        >
                            Coaching adalah proses pendampingan yang berfokus
                            pada membantu individu mencapai tujuan spesifik,
                            mengembangkan potensi, serta meningkatkan kinerja di
                            bidang tertentu, seperti karier, bisnis, atau
                            kehidupan pribadi.
                        </p>
                    </div>
                </section>

                <!-- Coaching Types Section -->
                <section class="mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Jenis Coaching
                    </h2>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="(type, index) in coachingTypes"
                            :key="index"
                            class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        >
                            <h3
                                class="text-xl font-semibold text-gray-800 mb-4"
                            >
                                {{ type.title }}
                            </h3>
                            <p class="text-gray-600 mb-4">
                                {{ type.description }}
                            </p>
                            <div class="space-y-2">
                                <p class="font-semibold text-gray-700">
                                    Contoh:
                                </p>
                                <ul
                                    class="list-disc list-inside space-y-1 text-gray-600"
                                >
                                    <li
                                        v-for="(example, i) in type.examples"
                                        :key="i"
                                    >
                                        {{ example }}
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-700">
                                    <span class="font-semibold"
                                        >Cocok untuk:</span
                                    >
                                    {{ type.suitableFor }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Coaching Methods Section -->
                <section class="mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Metode Coaching
                    </h2>
                    <div class="space-y-8">
                        <!-- GROW Method -->
                        <div class="bg-white p-8 rounded-xl shadow-lg">
                            <h3
                                class="text-xl font-semibold text-gray-800 mb-4"
                            >
                                {{ coachingMethods[0].title }}
                            </h3>
                            <p class="text-gray-600 mb-6">
                                {{ coachingMethods[0].description }}
                            </p>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                            >
                                <div
                                    v-for="step in coachingMethods[0].steps"
                                    :key="step.letter"
                                    class="bg-blue-50 p-4 rounded-lg"
                                >
                                    <div class="font-bold text-blue-600 mb-2">
                                        {{ step.letter }} - {{ step.meaning }}
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        {{ step.desc }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Other Methods -->
                        <div
                            v-for="(method, index) in coachingMethods.slice(1)"
                            :key="index"
                            class="bg-white p-8 rounded-xl shadow-lg"
                        >
                            <h3
                                class="text-xl font-semibold text-gray-800 mb-4"
                            >
                                {{ method.title }}
                            </h3>
                            <p class="text-gray-600 mb-4">
                                {{ method.description }}
                            </p>
                            <div class="pl-4 border-l-4 border-blue-500">
                                <ul class="space-y-2">
                                    <li
                                        v-for="(
                                            technique, i
                                        ) in method.techniques"
                                        :key="i"
                                        class="text-gray-700"
                                    >
                                        {{ technique }}
                                    </li>
                                </ul>
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
