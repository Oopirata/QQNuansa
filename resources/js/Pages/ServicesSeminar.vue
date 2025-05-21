<!-- Content Section -->
<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount } from "vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

const page = usePage();
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

const dropdownRef = ref(null);
const dropdownOpen = ref(false);

const isActive = (href) => {
    return currentRoute.value === href;
};

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route("logout"));
};

const asset = (path) => `/assets/${path}`;

const menuItems = [
    { text: "Tentang Kami", href: "/" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

const seminarTopics = [
    {
        category: "Pengembangan Pribadi",
        topics: [
            "Motivasi Diri dan Manajemen Waktu",
            "Mengembangkan Pola Pikir Positif (Growth Mindset)",
            "Keseimbangan Hidup dan Karier (Work-Life Balance)",
        ],
        icon: "fas fa-user-plus",
    },
    {
        category: "Kepemimpinan dan Manajemen",
        topics: [
            "Leadership for Future Leaders",
            "Manajemen Konflik dan Pengambilan Keputusan",
            "Strategi Meningkatkan Kinerja Tim",
        ],
        icon: "fas fa-crown",
    },
    {
        category: "Komunikasi dan Hubungan Interpersonal",
        topics: [
            "Public Speaking dan Komunikasi Efektif",
            "Membangun Hubungan yang Harmonis di Tempat Kerja",
            "Etika dan Komunikasi Profesional",
        ],
        icon: "fas fa-comments",
    },
    // ... more topics ...
];

const seminarFormats = [
    {
        title: "Berdasarkan Penyajian",
        formats: [
            {
                name: "Seminar Klasik / Tradisional",
                description:
                    "Diselenggarakan di ruangan fisik (auditorium, hotel, ruang konferensi). Pembicara memberikan presentasi di depan audiens, diikuti dengan sesi tanya jawab.",
                icon: "fas fa-chalkboard-teacher",
            },
            {
                name: "Webinar (Seminar Online)",
                description:
                    "Diselenggarakan melalui platform digital seperti Zoom, Microsoft Teams, atau Google Meet. Cocok untuk audiens yang lebih luas, hemat biaya, dan fleksibel dalam pelaksanaan.",
                icon: "fas fa-laptop",
            },
            {
                name: "Workshop / Hands-on Seminar",
                description:
                    "Lebih interaktif dengan kegiatan praktik langsung. Peserta terlibat aktif, bukan hanya mendengarkan presentasi.",
                icon: "fas fa-hands-helping",
            },
            {
                name: "Panel Discussion",
                description:
                    "Beberapa pembicara ahli berbagi pandangan tentang satu topik, biasanya dipandu oleh moderator. Peserta bisa berpartisipasi melalui sesi tanya jawab.",
                icon: "fas fa-users",
            },
        ],
    },
    {
        title: "Berdasarkan Durasi",
        formats: [
            {
                name: "Seminar Singkat (1-2 Jam)",
                description:
                    "Fokus pada topik yang spesifik dan langsung pada inti masalah.",
                icon: "fas fa-clock",
            },
            {
                name: "Seminar Sehari Penuh",
                description:
                    "Melibatkan beberapa sesi dengan topik terkait yang dibahas secara lebih mendalam.",
                icon: "fas fa-sun",
            },
            {
                name: "Seminar Berseri / Multi-hari",
                description:
                    "Biasanya diadakan selama beberapa hari dengan topik yang saling berkesinambungan.",
                icon: "fas fa-calendar-alt",
            },
        ],
    },
    {
        title: "Berdasarkan Peserta",
        formats: [
            {
                name: "Seminar Terbuka untuk Umum",
                description: "Siapa pun bisa mendaftar dan berpartisipasi.",
                icon: "fas fa-clock",
            },
            {
                name: "Seminar Khusus (In-house)",
                description:
                    "Ditujukan untuk kelompok tertentu, misalnya seminar pelatihan khusus bagi karyawan perusahaan.",
                icon: "fas fa-sun",
            },
        ],
    },
];

const seminarStructure = [
    {
        phase: "Pembukaan",
        activities: [
            "Registrasi peserta",
            "Sambutan dari panitia atau moderator",
        ],
        icon: "fas fa-door-open",
    },
    {
        phase: "Sesi Utama",
        activities: ["Presentasi dari narasumber", "Tanya jawab interaktif"],
        icon: "fas fa-chalkboard-teacher",
    },
    {
        phase: "Istirahat",
        activities: ["Coffee Break / Lunch Break"],
        icon: "fas fa-coffee",
    },
    {
        phase: "Sesi Kedua",
        activities: ["Workshop atau panel diskusi"],
        icon: "fas fa-users",
    },
    {
        phase: "Penutupan",
        activities: [
            "Ringkasan seminar oleh moderator",
            "Ucapan terima kasih dan dokumentasi",
        ],
        icon: "fas fa-flag-checkered",
    },
];
</script>

<template>
    <Head title="Services Seminar" />
    <div
        class="min-h-screen bg-gradient-to-b from-white to-blue-50 overflow-hidden"
    >
        <!-- Header section remains the same -->
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
                        Layanan Seminar
                    </h1>
                    <div class="prose max-w-none">
                        <p
                            class="text-center text-xl text-gray-700 max-w-3xl mx-auto"
                        >
                            Seminar adalah sebuah pertemuan atau acara yang
                            diadakan untuk membahas suatu topik tertentu secara
                            mendalam. Seminar biasanya melibatkan seorang atau
                            beberapa narasumber ahli yang memberikan presentasi
                            atau pemaparan, diikuti dengan sesi diskusi atau
                            tanya jawab bersama peserta.
                        </p>
                    </div>
                </section>

                <!-- Seminar Topics Section -->
                <section class="mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Topik Seminar
                    </h2>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="(category, index) in seminarTopics"
                            :key="index"
                            class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        >
                            <div class="flex items-center mb-4">
                                <i
                                    :class="
                                        category.icon +
                                        ' text-3xl text-blue-600 mr-4'
                                    "
                                ></i>
                                <h3 class="text-xl font-semibold text-gray-800">
                                    {{ category.category }}
                                </h3>
                            </div>
                            <ul class="space-y-2 text-gray-600">
                                <li
                                    v-for="(topic, tIndex) in category.topics"
                                    :key="tIndex"
                                    class="flex items-start"
                                >
                                    <i
                                        class="fas fa-check text-green-500 mt-1 mr-2"
                                    ></i>
                                    <span>{{ topic }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Seminar Formats Section -->
                <section class="mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Format Seminar
                    </h2>
                    <div class="space-y-8">
                        <div
                            v-for="(formatCategory, index) in seminarFormats"
                            :key="index"
                        >
                            <h3
                                class="text-2xl font-semibold text-gray-800 mb-4"
                            >
                                {{ formatCategory.title }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div
                                    v-for="(
                                        format, fIndex
                                    ) in formatCategory.formats"
                                    :key="fIndex"
                                    class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                                >
                                    <div class="flex items-center mb-3">
                                        <i
                                            :class="
                                                format.icon +
                                                ' text-2xl text-blue-600 mr-3'
                                            "
                                        ></i>
                                        <h4
                                            class="text-lg font-semibold text-gray-800"
                                        >
                                            {{ format.name }}
                                        </h4>
                                    </div>
                                    <p class="text-gray-600">
                                        {{ format.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Seminar Structure Section -->
                <section class="mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Struktur Seminar
                    </h2>
                    <div class="relative">
                        <div
                            class="absolute top-0 bottom-0 left-1/2 w-0.5 bg-blue-200 transform -translate-x-1/2"
                        ></div>
                        <div class="space-y-8 relative">
                            <div
                                v-for="(phase, index) in seminarStructure"
                                :key="index"
                                class="flex items-start"
                            >
                                <div
                                    class="bg-white p-4 rounded-full shadow-lg z-10 mr-4"
                                >
                                    <i
                                        :class="
                                            phase.icon +
                                            ' text-2xl text-blue-600'
                                        "
                                    ></i>
                                </div>
                                <div
                                    class="bg-white p-6 rounded-xl shadow-lg flex-grow"
                                >
                                    <h4
                                        class="text-lg font-semibold text-gray-800 mb-2"
                                    >
                                        {{ phase.phase }}
                                    </h4>
                                    <ul class="space-y-1 text-gray-600">
                                        <li
                                            v-for="(
                                                activity, aIndex
                                            ) in phase.activities"
                                            :key="aIndex"
                                            class="flex items-center"
                                        >
                                            <i
                                                class="fas fa-circle text-xs text-blue-400 mr-2"
                                            ></i>
                                            {{ activity }}
                                        </li>
                                    </ul>
                                </div>
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
