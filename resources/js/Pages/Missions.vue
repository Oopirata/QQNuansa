<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";
import { ref, onMounted, onBeforeUnmount } from "vue";

AOS.init({
    duration: 1000, // Durasi animasi (dalam ms)
    once: true, // Hanya memicu animasi sekali
});

const isParagraphVisible = ref(false);

onMounted(() => {
    AOS.init({
        duration: 1000,
        once: true,
        offset: 50,
    });
    document.addEventListener("click", closeDropdown);
});

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

// Jika kita perlu menggunakan asset dari Laravel, kita bisa membuat computed property
const asset = (path) => `/assets/${path}`;

const menuItems = [
    { text: "Tentang Kami", href: "/companyprofile" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];
</script>

<template>
    <Head title="Misi" />

    <div class="bg-gradient-to-b from-white to-blue-50">
        <!-- Header Section bg-blue-200-->
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

        <!-- Content Section -->
        <section class="container mx-auto py-10 px-6 md:px-12">
            <div class="max-w-5xl mx-auto">
                <h1
                    class="text-center text-3xl font-bold mb-12 relative"
                    data-aos="fade-down"
                >
                    <span class="relative inline-block">
                        MISI KAMI
                        <span
                            class="absolute bottom-0 left-0 w-full h-1 bg-blue-500 rounded-full"
                        ></span>
                    </span>
                </h1>

                <div
                    class="bg-white p-8 rounded-xl shadow-lg mb-14"
                    data-aos="fade-up"
                >
                    <p
                        class="text-xl leading-relaxed text-gray-700"
                        :class="{ 'animate-slideInLeft': true }"
                    >
                        PT. Ara Nuansa Katumbiri (ANK) adalah perusahaan yang
                        bergerak di bidang konsultasi pengembangan manajemen
                        sumber daya manusia. Berdiri sejak tahun 2003,
                        perusahaan ini berkomitmen untuk memberikan solusi
                        inovatif dan strategis dalam meningkatkan kualitas
                        pengelolaan sumber daya manusia bagi berbagai
                        organisasi.
                    </p>
                </div>

                <div
                    class="flex justify-center items-center"
                    :class="{ 'animate-zoomIn': true }"
                >
                    <img
                        alt="Missions Icon"
                        class="h-auto w-full max-w-3xl rounded-xl shadow-2xl transform hover:scale-[1.02] transition-transform duration-300 object-cover"
                        src="/images/seminar.jpeg"
                    />
                </div>

                <div
                    class="bg-white p-8 rounded-xl shadow-lg mt-14 mb-14"
                    data-aos="zoom-in"
                >
                    <p class="text-xl leading-relaxed text-gray-700">
                        Seiring dengan meningkatnya kebutuhan akan pengembangan
                        potensi individu dan pengelolaan sumber daya manusia,
                        baik secara personal maupun organisasi, PT. Ara Nuansa
                        Katumbiri hadir untuk mendukung peningkatan kualitas
                        diri secara holistik. Layanan ini dirancang untuk
                        membantu organisasi menciptakan lingkungan kerja yang
                        produktif melalui pendekatan psikologi yang terintegrasi
                        dengan teknologi modern. Berikut merupakan Misi kami
                        yang diantaranya adalah:
                    </p>
                </div>

                <!-- Mission 1 -->
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl shadow-lg mb-12"
                    data-aos="fade-right"
                >
                    <h2
                        class="text-2xl font-bold text-blue-800 mb-6 flex items-center"
                    >
                        <span
                            class="flex items-center justify-center bg-blue-800 text-white rounded-full w-10 h-10 mr-4"
                            >1</span
                        >
                        Meningkatkan Potensi Individu Secara Optimal & Positif
                    </h2>

                    <p class="mb-6 text-gray-700">
                        Layanan ini bertujuan untuk mengembangkan kemampuan
                        individu dalam berbagai aspek kehidupan. Penjelasannya
                        meliputi:
                    </p>

                    <div class="space-y-6">
                        <div
                            class="bg-white p-5 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 transition-transform duration-300"
                            data-aos="zoom-in"
                        >
                            <h3 class="text-lg font-bold text-blue-700 mb-2">
                                Kognitif
                            </h3>
                            <p class="text-gray-700 mb-2">
                                Memberikan pelatihan atau program yang
                                meningkatkan kemampuan berpikir kritis,
                                pemecahan masalah, daya ingat, dan kemampuan
                                belajar individu.
                            </p>
                            <p class="text-gray-600 italic">
                                Contoh: Tes kecerdasan, pelatihan logika, dan
                                seminar manajemen waktu.
                            </p>
                        </div>

                        <div
                            class="bg-white p-5 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 transition-transform duration-300"
                            data-aos="zoom-in"
                        >
                            <h3 class="text-lg font-bold text-blue-700 mb-2">
                                Emosi
                            </h3>
                            <p class="text-gray-700 mb-2">
                                Meningkatkan keterampilan pengelolaan emosi
                                seperti pengendalian stres, manajemen konflik,
                                dan peningkatan ketahanan emosional.
                            </p>
                            <p class="text-gray-600 italic">
                                Contoh: Konseling psikologi, terapi stres, atau
                                pelatihan mindfulness.
                            </p>
                        </div>

                        <div
                            class="bg-white p-5 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 transition-transform duration-300"
                            data-aos="zoom-in"
                        >
                            <h3 class="text-lg font-bold text-blue-700 mb-2">
                                Sosial
                            </h3>
                            <p class="text-gray-700 mb-2">
                                Mengembangkan keterampilan sosial individu agar
                                lebih percaya diri dalam berinteraksi dengan
                                lingkungan sosial.
                            </p>
                            <p class="text-gray-600 italic">
                                Contoh: Pelatihan komunikasi, kerjasama tim, dan
                                peningkatan empati sosial.
                            </p>
                        </div>

                        <div
                            class="bg-white p-5 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 transition-transform duration-300"
                            data-aos="zoom-in"
                        >
                            <h3 class="text-lg font-bold text-blue-700 mb-2">
                                Komunikasi
                            </h3>
                            <p class="text-gray-700 mb-2">
                                Membantu individu meningkatkan keterampilan
                                verbal dan non-verbal agar dapat menyampaikan
                                ide secara efektif.
                            </p>
                            <p class="text-gray-600 italic">
                                Contoh: Public speaking training, pelatihan
                                negosiasi, dan penanganan konflik interpersonal.
                            </p>
                        </div>

                        <div
                            class="bg-white p-5 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 transition-transform duration-300"
                            data-aos="zoom-in"
                        >
                            <h3 class="text-lg font-bold text-blue-700 mb-2">
                                Perilaku
                            </h3>
                            <p class="text-gray-700 mb-2">
                                Mengarahkan individu untuk membentuk kebiasaan
                                yang mendukung pengembangan diri, seperti
                                disiplin, tanggung jawab, dan motivasi diri.
                            </p>
                            <p class="text-gray-600 italic">
                                Contoh: Coaching perilaku produktif dan program
                                modifikasi kebiasaan.
                            </p>
                        </div>

                        <div
                            class="bg-white p-5 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 transition-transform duration-300"
                            data-aos="zoom-in"
                        >
                            <h3 class="text-lg font-bold text-blue-700 mb-2">
                                Spiritual
                            </h3>
                            <p class="text-gray-700 mb-2">
                                Mendukung individu dalam menemukan makna hidup
                                dan membangun hubungan dengan aspek spiritual,
                                yang dapat membantu meningkatkan kesehatan
                                mental.
                            </p>
                            <p class="text-gray-600 italic">
                                Contoh: Workshop pengembangan spiritualitas dan
                                pelatihan meditasi.
                            </p>
                        </div>

                        <div
                            class="bg-white p-5 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 transition-transform duration-300"
                            data-aos="zoom-in"
                        >
                            <h3 class="text-lg font-bold text-blue-700 mb-2">
                                Visual Motorik
                            </h3>
                            <p class="text-gray-700 mb-2">
                                Melatih koordinasi antara penglihatan dan
                                gerakan fisik untuk mendukung keterampilan
                                praktis.
                            </p>
                            <p class="text-gray-600 italic">
                                Contoh: Terapi motorik halus untuk anak-anak
                                atau pelatihan koordinasi fisik untuk pekerjaan
                                tertentu.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mission 2 -->
                <div
                    class="bg-gradient-to-r from-indigo-50 to-blue-50 p-8 rounded-xl shadow-lg mb-10"
                    data-aos="fade-left"
                >
                    <h2
                        class="text-2xl font-bold text-blue-800 mb-6 flex items-center"
                    >
                        <span
                            class="flex items-center justify-center bg-blue-800 text-white rounded-full w-10 h-10 mr-4"
                            >2</span
                        >
                        Menyediakan Jasa Aplikasi Psikologi dan Konsultan SDM
                    </h2>

                    <div class="grid md:grid-cols-2 gap-8">
                        <div
                            class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 transition-transform duration-300"
                            data-aos="zoom-in"
                        >
                            <h3 class="text-xl font-bold text-blue-700 mb-4">
                                Aplikasi Psikologi
                            </h3>
                            <p class="text-gray-700 mb-3">
                                Layanan yang memanfaatkan teknologi untuk
                                memberikan solusi berbasis psikologi.
                            </p>
                            <p class="text-gray-600 italic">
                                Contoh: Pengembangan aplikasi tes psikologi
                                online, alat asesmen kepribadian, atau platform
                                e-learning berbasis psikologi.
                            </p>
                            <div class="mt-4">
                                <div
                                    class="w-full bg-blue-100 h-2 rounded-full overflow-hidden"
                                >
                                    <div
                                        class="bg-blue-500 h-2 rounded-full"
                                        style="width: 75%"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 transition-transform duration-300"
                            data-aos="zoom-in"
                        >
                            <h3 class="text-xl font-bold text-blue-700 mb-4">
                                Konsultan SDM
                            </h3>
                            <p class="text-gray-700 mb-3">
                                Membantu organisasi dan perusahaan dalam
                                mengembangkan sumber daya manusia secara
                                efektif.
                            </p>
                            <ul class="text-gray-700 space-y-2 list-disc ml-5">
                                <li>
                                    Menyusun program pelatihan karyawan untuk
                                    meningkatkan keterampilan tertentu.
                                </li>
                                <li>
                                    Memberikan layanan asesmen karyawan untuk
                                    promosi atau rekrutmen.
                                </li>
                                <li>
                                    Konsultasi terkait pengembangan budaya kerja
                                    yang mendukung produktivitas dan
                                    kesejahteraan karyawan.
                                </li>
                            </ul>
                            <div class="mt-4">
                                <div
                                    class="w-full bg-blue-100 h-2 rounded-full overflow-hidden"
                                >
                                    <div
                                        class="bg-blue-500 h-2 rounded-full"
                                        style="width: 85%"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
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
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideInLeft {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes zoomIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.animate-fadeIn {
    animation: fadeIn 1s ease-out;
}

.animate-slideInLeft {
    animation: slideInLeft 1s ease-out;
}

.animate-zoomIn {
    animation: zoomIn 1s ease-out;
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
