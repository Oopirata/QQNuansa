<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref } from "vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const currentRoute = computed(() => page.url);

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
    { text: "Tentang Kami", href: "/" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];
</script>

<template>
    <Head title="Company Profile" />

    <div class="bg-white">
        <!-- Header Section bg-blue-200-->
        <header
            class="p-4 flex justify-between items-center mt-2 container mx-auto"
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
                    class="pb-2 border-b-4 border-[#CDC052] w-fit mx-auto rounded-lg"
                >
                    <div class="flex space-x-8 px-4">
                        <Link
                            v-for="(item, index) in menuItems"
                            :key="index"
                            :href="item.href"
                            class="nav-link relative text-gray-700 text-center whitespace-nowrap transition-colors duration-200 hover:text-[#CDC052]"
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
                <div class="flex items-center">
                    <template v-if="$page.props.auth.user">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                    >
                                        {{ $page.props.auth.user.name }}
                                        <i
                                            class="fas fa-user-circle text-blue-900 text-3xl ml-2"
                                        ></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
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

        <!-- Navigation Menu bg-yellow-100 -->

        <!-- Content Section -->
        <section class="ml-52 mr-40">
            <h1 class="text-center text-2xl font-bold -ml-4 mb-5 mt-5">
                MISI KAMI
            </h1>

            <div
                style="
                    display: flex;
                    justify-content: center;
                    align-items: center;
                "
            >
                <img
                    alt="Missions Icon"
                    class="h-auto w-auto mt-5 mb-5 -ml-10 rounded-lg"
                    src="/images/seminar.JPG"
                />
            </div>
            <p class="text-2xl mt-5 mr-64 -ml-5 mb-10">
                Pengembangan potensi individu dan pengelolaan sumber daya
                manusia kini menjadi kebutuhan penting, baik secara personal
                maupun organisasi.Layanan ini mendukung peningkatan kualitas
                diri secara holistik dan membantu organisasi menciptakan
                lingkungan kerja yang produktif, dengan pendekatan psikologi
                yang terintegrasi teknologi modern.Berikut merupakan Misi kami
                yang diantaranya adalah:
            </p>
            <p class="text-2xl font-bold -ml-2 mt-5">
                1. Meningkatkan Potensi Individu Secara Optimal & Positif
            </p>
            <div style="padding: 20px; text-align: left; font-size: 1rem">
                <p class="-mt-4">
                    Layanan ini bertujuan untuk mengembangkan kemampuan individu
                    dalam berbagai aspek kehidupan. Penjelasannya meliputi:
                </p>
                <ul>
                    <li class="mb-4 mt-2">
                        <strong>Kognitif:</strong> Memberikan pelatihan atau
                        program yang meningkatkan kemampuan berpikir kritis,
                        pemecahan masalah, daya ingat, dan kemampuan belajar
                        individu. <br />
                        <em>Contoh:</em> Tes kecerdasan, pelatihan logika, dan
                        seminar manajemen waktu.
                    </li>
                    <li class="mb-4">
                        <strong>Emosi:</strong> Meningkatkan keterampilan
                        pengelolaan emosi seperti pengendalian stres, manajemen
                        konflik, dan peningkatan ketahanan emosional. <br />
                        <em>Contoh:</em> Konseling psikologi, terapi stres, atau
                        pelatihan mindfulness.
                    </li>
                    <li class="mb-4">
                        <strong>Sosial:</strong> Mengembangkan keterampilan
                        sosial individu agar lebih percaya diri dalam
                        berinteraksi dengan lingkungan sosial. <br />
                        <em>Contoh:</em> Pelatihan komunikasi, kerjasama tim,
                        dan peningkatan empati sosial.
                    </li>
                    <li class="mb-4">
                        <strong>Komunikasi:</strong> Membantu individu
                        meningkatkan keterampilan verbal dan non-verbal agar
                        dapat menyampaikan ide secara efektif. <br />
                        <em>Contoh:</em> Public speaking training, pelatihan
                        negosiasi, dan penanganan konflik interpersonal.
                    </li>
                    <li class="mb-4">
                        <strong>Perilaku:</strong> Mengarahkan individu untuk
                        membentuk kebiasaan yang mendukung pengembangan diri,
                        seperti disiplin, tanggung jawab, dan motivasi diri.
                        <br />
                        <em>Contoh:</em> Coaching perilaku produktif dan program
                        modifikasi kebiasaan.
                    </li>
                    <li class="mb-4">
                        <strong>Spiritual:</strong> Mendukung individu dalam
                        menemukan makna hidup dan membangun hubungan dengan
                        aspek spiritual, yang dapat membantu meningkatkan
                        kesehatan mental. <br />
                        <em>Contoh:</em> Workshop pengembangan spiritualitas dan
                        pelatihan meditasi.
                    </li>
                    <li class="mb-4">
                        <strong>Visual Motorik:</strong> Melatih koordinasi
                        antara penglihatan dan gerakan fisik untuk mendukung
                        keterampilan praktis. <br />
                        <em>Contoh:</em> Terapi motorik halus untuk anak-anak
                        atau pelatihan koordinasi fisik untuk pekerjaan
                        tertentu.
                    </li>
                </ul>
            </div>
            <div
                style="
                    display: flex;
                    justify-content: center;
                    align-items: center;
                "
            ></div>
            <p class="text-2xl font-bold -ml-2 mt-5 -mb-4">
                2. Menyediakan Jasa Aplikasi Psikologi dan Konsultan SDM
            </p>
            <div style="padding: 20px; text-align: left; font-size: 1rem">
                <p class="mb-4">
                    <strong>Aplikasi Psikologi:</strong> Layanan yang
                    memanfaatkan teknologi untuk memberikan solusi berbasis
                    psikologi. <br />
                    <em>Contoh:</em> Pengembangan aplikasi tes psikologi online,
                    alat asesmen kepribadian, atau platform e-learning berbasis
                    psikologi.
                </p>
                <p>
                    <strong>Konsultan SDM:</strong> Membantu organisasi dan
                    perusahaan dalam mengembangkan sumber daya manusia secara
                    efektif.
                </p>
                <ul>
                    <li>
                        Menyusun program pelatihan karyawan untuk meningkatkan
                        keterampilan tertentu.
                    </li>
                    <li>
                        Memberikan layanan asesmen karyawan untuk promosi atau
                        rekrutmen.
                    </li>
                    <li>
                        Konsultasi terkait pengembangan budaya kerja yang
                        mendukung produktivitas dan kesejahteraan karyawan.
                    </li>
                </ul>
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
    background-color: #fefce8;
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
    color: #cdc052;
}
</style>
