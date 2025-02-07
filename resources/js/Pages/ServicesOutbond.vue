<!-- Content Section -->
<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount } from "vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

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

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
const page = usePage();
const dropdownRef = ref(null);
const dropdownOpen = ref(false);
const currentRoute = computed(() => page.url);

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

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        dropdownOpen.value = false;
    }
};

const outboundTypes = [
    {
        title: "Outbound Team Building",
        description:
            "Fokus pada kerja sama tim melalui permainan yang mendorong koordinasi, kepercayaan, dan komunikasi yang efektif.",
        icon: "fas fa-users",
    },
    {
        title: "Corporate Outbound Training",
        description:
            "Program khusus untuk perusahaan, bertujuan meningkatkan kinerja tim dan membangun sinergi antar departemen.",
        icon: "fas fa-building",
    },
    {
        title: "Leadership Camp",
        description:
            "Pelatihan kepemimpinan untuk individu atau kelompok, membantu mengasah kemampuan memimpin dan pengambilan keputusan.",
        icon: "fas fa-flag",
    },
    {
        title: "Adventure Outbound",
        description:
            "Aktivitas petualangan seperti arung jeram, flying fox, trekking, dan wall climbing untuk meningkatkan keberanian dan semangat tantangan.",
        icon: "fas fa-mountain",
    },
    {
        title: "Family Outbound",
        description:
            "Program rekreasi keluarga yang menggabungkan permainan menyenangkan dengan momen kebersamaan.",
        icon: "fas fa-home",
    },
    {
        title: "Motivational Outbound",
        description:
            "Meningkatkan motivasi dan semangat hidup melalui kegiatan yang memotivasi secara mental dan emosional.",
        icon: "fas fa-star",
    },
];

const outboundBenefits = [
    {
        title: "Peningkatan Komunikasi dan Kerja Sama Tim",
        description:
            "Mendorong peserta untuk bekerja sama, saling mendukung, dan berkomunikasi lebih efektif.",
        icon: "fas fa-comments",
    },
    {
        title: "Mengasah Kepemimpinan",
        description:
            "Membantu peserta mengenali potensi kepemimpinan mereka melalui situasi yang menuntut pengambilan keputusan dan tanggung jawab.",
        icon: "fas fa-crown",
    },
    {
        title: "Meningkatkan Kepercayaan Diri",
        description:
            "Aktivitas menantang akan mendorong peserta keluar dari zona nyaman mereka.",
        icon: "fas fa-shield-alt",
    },
    {
        title: "Mengurangi Stres dan Meningkatkan Kebugaran",
        description:
            "Kegiatan di alam terbuka membantu relaksasi mental dan fisik.",
        icon: "fas fa-heart",
    },
    {
        title: "Membangun Hubungan yang Lebih Baik",
        description:
            "Mempererat hubungan antar peserta, baik dalam lingkungan kerja maupun keluarga.",
        icon: "fas fa-handshake",
    },
];

const outboundConcepts = [
    {
        title: "Fun Games",
        description:
            "Permainan seru yang menghibur, bertujuan untuk mencairkan suasana dan mempererat hubungan antar peserta.",
        icon: "fas fa-gamepad",
    },
    {
        title: "Leadership Training",
        description:
            "Program yang dirancang untuk mengasah keterampilan kepemimpinan melalui simulasi dan tantangan di lapangan.",
        icon: "fas fa-chess-king",
    },
    {
        title: "Survival Activities",
        description:
            "Aktivitas petualangan yang menantang, seperti simulasi bertahan hidup di alam bebas untuk meningkatkan ketahanan mental dan fisik.",
        icon: "fas fa-campground",
    },
];
</script>

<template>
    <Head title="Services Outbond" />
    <div
        class="min-h-screen bg-gradient-to-b from-white to-blue-50 overflow-hidden"
    >
        <!-- Header section sama seperti sebelumnya -->
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
                        Layanan Outbound
                    </h1>
                    <div class="prose max-w-none">
                        <p
                            class="text-center text-xl text-gray-700 max-w-3xl mx-auto"
                        >
                            Outbound adalah kegiatan luar ruangan yang dirancang
                            untuk meningkatkan kerja sama tim, kepemimpinan,
                            komunikasi, serta membangun motivasi dan semangat di
                            antara peserta.
                        </p>
                    </div>
                </section>

                <!-- Outbound Concepts -->
                <section class="mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Konsep Outbound
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div
                            v-for="(concept, index) in outboundConcepts"
                            :key="index"
                            class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow text-center"
                        >
                            <i
                                :class="
                                    concept.icon +
                                    ' text-4xl text-blue-600 mb-4'
                                "
                            ></i>
                            <h3
                                class="text-xl font-semibold text-gray-800 mb-3"
                            >
                                {{ concept.title }}
                            </h3>
                            <p class="text-gray-600">
                                {{ concept.description }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Outbound Types -->
                <section class="mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Jenis Outbound
                    </h2>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="(type, index) in outboundTypes"
                            :key="index"
                            class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        >
                            <div class="flex items-center mb-4">
                                <i
                                    :class="
                                        type.icon +
                                        ' text-3xl text-blue-600 mr-4'
                                    "
                                ></i>
                                <h3 class="text-xl font-semibold text-gray-800">
                                    {{ type.title }}
                                </h3>
                            </div>
                            <p class="text-gray-600">{{ type.description }}</p>
                        </div>
                    </div>
                </section>

                <!-- Benefits Section -->
                <section class="mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                        Manfaat Outbound
                    </h2>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                    >
                        <div
                            v-for="(benefit, index) in outboundBenefits"
                            :key="index"
                            class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        >
                            <div class="flex items-center mb-4">
                                <i
                                    :class="
                                        benefit.icon +
                                        ' text-3xl text-green-600 mr-4'
                                    "
                                ></i>
                                <h3 class="text-xl font-semibold text-gray-800">
                                    {{ benefit.title }}
                                </h3>
                            </div>
                            <p class="text-gray-600">
                                {{ benefit.description }}
                            </p>
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
