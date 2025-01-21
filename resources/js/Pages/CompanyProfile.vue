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
        <!-- Header Section -->
        <header class="bg-blue-200 p-4 flex justify-between items-center">
            <div class="flex items-center -mr-16">
                <img
                    alt="Company Logo"
                    class="h-14 w-14"
                    src="/images/QQ.png"
                />
            </div>
            <nav class="space-x-10 -ml-60 mr-80">
                <Link
                    class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]"
                    href="/companyprofile"
                >
                    Tentang Kami
                </Link>
                <Link
                    class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]"
                    href="#"
                >
                    Psiko Test
                </Link>
                <Link
                    class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]"
                    href="#"
                >
                    Konsultasi
                </Link>
                <Link
                    class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]"
                    href="#"
                >
                    Pelatihan
                </Link>
            </nav>
            <div class="-mr-72">
                <Link
                    class="text-[#5932EA] font-bold transition-colors duration-200 hover:text-[#4D62D7]"
                    href="/hiring"
                >
                    We are hiring!!
                </Link>
            </div>
            <div class="flex items-center space-x-2">
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
                            <DropdownLink href="/adminDashboard">
                                Admin Dashboard
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
                            class="bg-green-200 text-green-800 px-4 py-2 rounded mr-3"
                        >
                            Log In
                        </button>
                    </Link>
                    <i class="fas fa-user-circle text-blue-900 text-3xl"></i>
                </template>
            </div>
        </header>

        <!-- Navigation Menu -->
        <main class="p-8 -mt-8">
            <div class="bg-yellow-100 p-4 flex justify-center w-2/5 mx-auto">
                <div class="flex justify-between w-full px-4">
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
        </main>

        <!-- Content Section -->
        <section class="ml-40 mr-40">
            <h1 class="text-center text-2xl font-bold">COMPANY PROFILE</h1>
            <p class="mt-4">
                PT. Ara Nuansa Katumbiri (ANK) adalah perusahaan konsultan yang
                berfokus pada pengembangan manajemen sumber daya manusia.
                Berdiri sejak tahun 2003, perusahaan ini sebelumnya dikenal
                dengan nama QQNuansa Consultant dan berkantor pusat di
                Pekalongan, Jawa Tengah. Meskipun demikian, area kerja dan
                distribusi layanan PT. Ara Nuansa Katumbiri mencakup seluruh
                wilayah Indonesia, dengan konsentrasi utama di wilayah Jawa
                Tengah.
            </p>
            <p class="mt-4">
                Perusahaan ini menawarkan tiga layanan utama yang menjadi inti
                kompetensi dan kegiatan bisnisnya:
            </p>
            <ol class="list-decimal list-inside mt-4">
                <li class="mt-2">
                    <strong>Consulting</strong>
                    <br />
                    Layanan ini menyediakan jasa konsultan manajemen sumber daya
                    manusia untuk organisasi. Fokus utama adalah membantu
                    organisasi dalam meningkatkan efektivitas pengelolaan SDM
                    melalui pendekatan profesional dan strategis, termasuk
                    penyusunan strategi organisasi, pengembangan sistem SDM, dan
                    solusi manajemen yang relevan.
                </li>
                <li class="mt-2">
                    <strong>Learning Center</strong>
                    <br />
                    Melalui layanan ini, PT. Ara Nuansa Katumbiri
                    menyelenggarakan berbagai program pelatihan dan pengembangan
                    SDM. Kegiatan yang ditawarkan meliputi:
                </li>
                <li class="mt-2">
                    <strong>Recruitment and Assessment</strong>
                    <br />
                    Layanan ini bertujuan membantu organisasi dalam mencari,
                    memilih, dan merekomendasikan individu-individu dengan bakat
                    terbaik untuk dikembangkan lebih lanjut. Proses ini mencakup
                    rekrutmen dan penilaian calon karyawan secara menyeluruh,
                    baik untuk kebutuhan industri maupun pendidikan, guna
                    memastikan organisasi mendapatkan SDM yang
                    berkualitas.Dengan berfokus pada pengelolaan sumber daya
                    manusia, PT. Ara Nuansa Katumbiri berperan sebagai mitra
                    strategis bagi organisasi dalam menciptakan tenaga kerja
                    yang produktif dan kompetitif.
                </li>
            </ol>
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
