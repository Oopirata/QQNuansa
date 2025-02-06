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

// Menu items
const menuItems = [
    { text: "Tentang Kami", href: "/" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

// Training data
const trainingFields = [
    {
        title: "Soft Skill Training",
        description:
            "Pelatihan ini berfokus pada pengembangan keterampilan non-teknis yang penting untuk meningkatkan kinerja individu di lingkungan kerja maupun kehidupan sehari-hari.",
        topics: [
            "Komunikasi Efektif",
            "Manajemen Waktu",
            "Problem Solving & Decision Making",
            "Emotional Intelligence (Kecerdasan Emosional)",
            "Negosiasi & Persuasi",
        ],
        benefits: [
            "Meningkatkan kemampuan komunikasi dan kerja sama",
            "Membantu pengambilan keputusan yang lebih baik",
            "Memperkuat kepercayaan diri dan empati",
        ],
        icon: "fas fa-brain",
    },
    {
        title: "Leadership Training",
        description:
            "Pelatihan ini dirancang untuk mengembangkan keterampilan kepemimpinan, baik bagi calon pemimpin maupun manajer yang ingin meningkatkan kemampuan memimpin tim.",
        topics: [
            "Strategi Kepemimpinan Efektif",
            "Manajemen Konflik",
            "Coaching & Mentoring",
            "Pengambilan Keputusan Strategis",
            "Membangun Motivasi Tim",
        ],
        benefits: [
            "Mengasah jiwa kepemimpinan dan kemampuan menginspirasi tim",
            "Memahami gaya kepemimpinan yang sesuai dengan situasi",
            "Meningkatkan kemampuan delegasi dan pengembangan tim",
        ],
        icon: "fas fa-crown",
    },
    {
        title: "Corporate Training",
        description:
            "Program pelatihan ini dirancang khusus untuk perusahaan, dengan fokus pada peningkatan keterampilan yang sesuai dengan kebutuhan organisasi.",
        topics: [
            "Customer Service Excellence",
            "Business Communication & Presentation Skills",
            "Change Management",
            "Digital Transformation Training",
            "Team Building & Collaboration",
        ],
        benefits: [
            "Meningkatkan kinerja karyawan secara keseluruhan",
            "Membantu perusahaan beradaptasi dengan perubahan industri",
            "Memperkuat budaya kerja yang positif dan produktif",
        ],
        icon: "fas fa-building",
    },
];

const trainingMethods = [
    {
        title: "Berdasarkan Media Pelaksanaan",
        types: [
            {
                name: "Online Training",
                description:
                    "Pelatihan yang dilakukan secara daring melalui platform digital seperti Zoom, Microsoft Teams, atau Learning Management System (LMS).",
                advantages: [
                    "Fleksibel waktu dan tempat",
                    "Hemat biaya perjalanan dan akomodasi",
                    "Dapat direkam untuk dipelajari kembali",
                ],
                examples: ["Webinar", "e-learning", "virtual workshop"],
                icon: "fas fa-laptop",
            },
            {
                name: "Offline Training",
                description:
                    "Pelatihan tatap muka langsung di tempat tertentu, seperti ruang kelas, hotel, atau area outdoor.",
                advantages: [
                    "Interaksi lebih intensif dan langsung",
                    "Memudahkan kegiatan praktik atau simulasi",
                    "Membangun relasi dan kerja tim lebih efektif",
                ],
                examples: [
                    "Seminar",
                    "workshop",
                    "coaching tatap muka",
                    "outbound training",
                ],
                icon: "fas fa-chalkboard-teacher",
            },
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
    <Head title="Services Training" />
    <div
        class="min-h-screen bg-gradient-to-b from-white to-blue-50 overflow-hidden"
    >
        <!-- Header section tetap sama -->
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

        <div class="container mx-auto py-12 px-8 md:px-16 lg:px-24">
            <!-- Introduction Section -->
            <section class="mb-16" data-aos="fade-up">
                <h1 class="text-4xl font-bold text-gray-900 mb-6 text-center">
                    Layanan Training
                </h1>
                <div class="prose max-w-none">
                    <p
                        class="text-center text-xl text-gray-700 max-w-3xl mx-auto"
                    >
                        Training adalah program pelatihan yang dirancang untuk
                        meningkatkan pengetahuan, keterampilan, dan sikap
                        individu atau tim agar mampu mencapai tujuan tertentu.
                    </p>
                </div>
            </section>
            <!-- Images Section -->
            <section class="mb-16" data-aos="fade-up">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <img
                            src="/images/qqnuansatraining1.jpg"
                            alt="Training Image 1"
                            class="w-full h-64 object-cover"
                        />
                        <div class="p-4 bg-white">
                            <p class="text-gray-700 text-center">
                                Pelatihan Soft Skill
                            </p>
                        </div>
                    </div>
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <img
                            src="/images/qqnuansatrainingPKL.jpg"
                            alt="Training Image 2"
                            class="w-full h-64 object-cover"
                        />
                        <div class="p-4 bg-white">
                            <p class="text-gray-700 text-center">
                                Pelatihan Kepemimpinan
                            </p>
                        </div>
                    </div>
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <img
                            src="/images/qqnuansatrainingBTG.jpg"
                            alt="Training Image 3"
                            class="w-full h-64 object-cover"
                        />
                        <div class="p-4 bg-white">
                            <p class="text-gray-700 text-center">
                                Pelatihan Korporat
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Training Fields Section -->
            <section class="mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                    Bidang Training
                </h2>
                <div class="space-y-8">
                    <div
                        v-for="(field, index) in trainingFields"
                        :key="index"
                        class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                    >
                        <div class="flex items-center mb-4">
                            <i
                                :class="
                                    field.icon + ' text-3xl text-blue-600 mr-4'
                                "
                            ></i>
                            <h3 class="text-2xl font-semibold text-gray-800">
                                {{ field.title }}
                            </h3>
                        </div>
                        <p class="text-gray-600 mb-6">
                            {{ field.description }}
                        </p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Topics -->
                            <div>
                                <h4
                                    class="text-lg font-semibold text-gray-800 mb-3"
                                >
                                    Topik yang bisa dilatih:
                                </h4>
                                <ul class="space-y-2">
                                    <li
                                        v-for="(topic, tIndex) in field.topics"
                                        :key="tIndex"
                                        class="flex items-start"
                                    >
                                        <i
                                            class="fas fa-check text-green-500 mt-1 mr-2"
                                        ></i>
                                        <span class="text-gray-600">{{
                                            topic
                                        }}</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Benefits -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4
                                    class="text-lg font-semibold text-gray-800 mb-3"
                                >
                                    Manfaat:
                                </h4>
                                <ul class="space-y-2">
                                    <li
                                        v-for="(
                                            benefit, bIndex
                                        ) in field.benefits"
                                        :key="bIndex"
                                        class="flex items-start"
                                    >
                                        <i
                                            class="fas fa-star text-yellow-500 mt-1 mr-2"
                                        ></i>
                                        <span class="text-gray-600">{{
                                            benefit
                                        }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Training Methods Section -->
            <section class="mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-semibold text-gray-900 mb-6">
                    Metode Training
                </h2>
                <div
                    v-for="(category, index) in trainingMethods"
                    :key="index"
                    class="space-y-6"
                >
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">
                        {{ category.title }}
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div
                            v-for="(method, mIndex) in category.types"
                            :key="mIndex"
                            class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow"
                        >
                            <div class="flex items-center mb-4">
                                <i
                                    :class="
                                        method.icon +
                                        ' text-3xl text-blue-600 mr-4'
                                    "
                                ></i>
                                <h4 class="text-xl font-semibold text-gray-800">
                                    {{ method.name }}
                                </h4>
                            </div>
                            <p class="text-gray-600 mb-4">
                                {{ method.description }}
                            </p>

                            <!-- Advantages -->
                            <div class="mb-4">
                                <h5 class="font-semibold text-gray-800 mb-2">
                                    Keunggulan:
                                </h5>
                                <ul class="space-y-1">
                                    <li
                                        v-for="(
                                            adv, aIndex
                                        ) in method.advantages"
                                        :key="aIndex"
                                        class="flex items-start"
                                    >
                                        <i
                                            class="fas fa-check text-green-500 mt-1 mr-2"
                                        ></i>
                                        <span class="text-gray-600">{{
                                            adv
                                        }}</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Examples -->
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <h5 class="font-semibold text-gray-800 mb-2">
                                    Contoh:
                                </h5>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="(
                                            example, eIndex
                                        ) in method.examples"
                                        :key="eIndex"
                                        class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm"
                                    >
                                        {{ example }}
                                    </span>
                                </div>
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
                                    ✓ Di QQNuansa Consultant sesuai kesepakatan
                                    waktu
                                </li>
                                <li>
                                    ✓ In-house di perusahaan sesuai kesepakatan
                                </li>
                                <li>
                                    ✓ Biaya transport gratis untuk peserta >5
                                    orang di Kota Pekalongan
                                </li>
                                <li>
                                    ✓ Biaya transport khusus untuk peserta <5
                                    atau di luar Kota Pekalongan
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
