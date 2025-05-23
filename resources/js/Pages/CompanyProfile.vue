<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref, onMounted, onBeforeUnmount } from "vue";
import { computed } from "vue";
import ServiceCards from "./ServiceCards.vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

const page = usePage();
console.log(page.props.auth.user);
const currentRoute = computed(() => page.url);
const dropdownOpen = ref(false);
const dropdownRef = ref(null);

const isActive = (href) => {
    return currentRoute.value === href;
};

const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        dropdownOpen.value = false;
    }
};

onMounted(() => {
    AOS.init({
        duration: 1000,
        once: true,
        offset: 50,
    });
    document.addEventListener("click", closeDropdown);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", closeDropdown);
});

const menuItems = [
    { text: "Tentang Kami", href: "/companyprofile" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

// Tambahan data untuk memperkaya tampilan

const teamMembers = [
    {
        name: "Tony Eryanto, ST, C.Ht",
        position: "Associate Trainer, Hypnotherapist &Facilitator",
        image: "/images/Profesional1.jpg",
    },
    {
        name: "Prof. Tjetjep Rohendi Rohidi",
        position: "Associate Trainer, Researcher & Facilitator",
        image: "/images/Profesional2.jpg",
    },
    {
        name: "Vika Himawanti Suprapto",
        position: "SE, C.PS Founder & Director VCTAMA ACADEMY",
        image: "/images/Profesional3.jpg",
    },
    {
        name: "Didit Dudi Kusuma, S.Psi, Psikolog",
        position: "Associate Trainer",
        image: "/images/Profesional4.jpg",
    },
    {
        name: "Nurdin Adyansah, SE,M.IT",
        position: "Associate Trainer, Programmer & Facilitator",
        image: "images/Profesional5.jpg",
    },
];

const testimonials = [
    {
        quote: "PT. Ara Nuansa Katumbiri telah menjadi mitra tepercaya kami dalam pengembangan SDM selama lebih dari 5 tahun.",
        author: "Budi Santoso",
        company: "PT. Maju Bersama",
    },
    {
        quote: "Layanan konsultasi yang diberikan sangat komprehensif dan sesuai dengan kebutuhan perusahaan kami.",
        author: "Dian Permata",
        company: "CV. Permata Abadi",
    },
];

const certifications = [
    "ISO 9001:2015 - Sistem Manajemen Mutu",
    "BNSP - Badan Nasional Sertifikasi Profesi",
    "Kementerian Ketenagakerjaan RI",
];
</script>

<template>
    <Head title="Company Profile" />

    <div class="bg-white">
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

        <!-- Main Content Section -->
        <section class="container mx-auto px-4 py-16">
            <div class="container mx-auto px-4 mb-16">
                <div class="max-w-3xl mx-auto text-center">
                    <h1
                        class="text-3xl font-bold mb-4 relative inline-block"
                        data-aos="fade-up"
                    >
                        <span class="relative z-10">COMPANY PROFILE</span>
                        <span
                            class="absolute -bottom-2 left-0 w-full h-1 bg-[#5099D5]"
                        ></span>
                    </h1>
                    <p class="text-gray-500 italic mb-6">
                        Profesional - Terpercaya - Berpengalaman
                    </p>
                </div>

                <div
                    class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 max-w-3xl mx-auto"
                    data-aos="fade-up"
                    data-aos-delay="200"
                >
                    <p class="text-lg leading-relaxed text-gray-700 mb-6">
                        PT. Ara Nuansa Katumbiri (ANK) adalah perusahaan
                        konsultan yang berfokus pada pengembangan manajemen
                        sumber daya manusia. Berdiri sejak tahun 2003,
                        perusahaan ini sebelumnya dikenal dengan nama QQNuansa
                        Consultant dan berkantor pusat di Pekalongan, Jawa
                        Tengah.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700 mb-6">
                        Meskipun demikian, area kerja dan distribusi layanan PT.
                        Ara Nuansa Katumbiri mencakup seluruh wilayah Indonesia,
                        dengan konsentrasi utama di wilayah Jawa Tengah.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
                        <div
                            class="bg-blue-50 p-6 rounded-lg border-l-4 border-[#0E74B8]"
                        >
                            <h3
                                class="text-xl font-bold text-gray-800 mb-3 flex items-center"
                            >
                                <i
                                    class="fas fa-bullseye text-[#0E74B8] mr-3"
                                ></i>
                                Visi Kami
                            </h3>
                            <p class="text-gray-700">
                                Menjadi konsultan SDM terdepan dengan layanan
                                berkualitas dan terintegrasi di seluruh
                                Indonesia.
                            </p>
                        </div>
                        <div
                            class="bg-blue-50 p-6 rounded-lg border-l-4 border-[#0E74B8]"
                        >
                            <h3
                                class="text-xl font-bold text-gray-800 mb-3 flex items-center"
                            >
                                <i
                                    class="fas fa-handshake text-[#0E74B8] mr-3"
                                ></i>
                                Komitmen Kami
                            </h3>
                            <p class="text-gray-700">
                                Memberikan solusi SDM yang inovatif dan
                                berkelanjutan untuk membantu organisasi mencapai
                                potensi maksimal.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Cards Section - Using existing component -->
            <div class="mb-16">
                <div class="text-center mb-10" data-aos="fade-up">
                    <h2
                        class="text-2xl md:text-3xl font-bold mb-4 relative inline-block"
                    >
                        <span class="relative z-10">Layanan Unggulan</span>
                        <span
                            class="absolute -bottom-2 left-0 w-full h-1 bg-[#5099D5]"
                        ></span>
                    </h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Solusi komprehensif untuk memenuhi kebutuhan organisasi
                        Anda dalam pengembangan sumber daya manusia
                    </p>
                </div>
                <ServiceCards />
            </div>

            <!-- Team Section -->
            <div class="mb-16">
                <div class="text-center mb-10" data-aos="fade-up">
                    <h2
                        class="text-2xl md:text-3xl font-bold mb-4 relative inline-block"
                    >
                        <span class="relative z-10">Tim Profesional Kami</span>
                        <span
                            class="absolute -bottom-2 left-0 w-full h-1 bg-[#5099D5]"
                        ></span>
                    </h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Dipimpin oleh para ahli berpengalaman di bidang
                        manajemen sumber daya manusia
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                    <div
                        v-for="(member, index) in teamMembers"
                        :key="index"
                        class="bg-white rounded-xl overflow-hidden shadow-lg border border-gray-100 transform transition duration-300 hover:-translate-y-2 hover:shadow-xl"
                        data-aos="fade-up"
                        :data-aos-delay="index * 100"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-400 to-blue-600 h-24 relative"
                        >
                            <div
                                class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2"
                            >
                                <div
                                    class="rounded-full border-4 border-white overflow-hidden w-24 h-24"
                                >
                                    <img
                                        :src="member.image"
                                        :alt="member.name"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="pt-16 px-6 pb-6 text-center">
                            <h3 class="text-xl font-bold text-gray-800 mb-1">
                                {{ member.name }}
                            </h3>
                            <p class="text-gray-600 mb-4">
                                {{ member.position }}
                            </p>
                            <div class="flex justify-center space-x-3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial Section -->
            <div class="mb-16 bg-gray-50 rounded-2xl p-8">
                <div class="text-center mb-10" data-aos="fade-up">
                    <h2
                        class="text-2xl md:text-3xl font-bold mb-4 relative inline-block"
                    >
                        <span class="relative z-10">Testimoni Klien</span>
                        <span
                            class="absolute -bottom-2 left-0 w-full h-1 bg-[#5099D5]"
                        ></span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div
                        v-for="(testimonial, index) in testimonials"
                        :key="index"
                        class="bg-white p-6 rounded-lg shadow-md border border-gray-100"
                        data-aos="fade-up"
                        :data-aos-delay="index * 100"
                    >
                        <div class="flex mb-4">
                            <div class="text-[#0E74B8]">
                                <i
                                    class="fas fa-quote-left text-4xl opacity-30"
                                ></i>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-6 italic">
                            {{ testimonial.quote }}
                        </p>
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4"
                            >
                                <i class="fas fa-user text-[#0E74B8]"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ testimonial.author }}
                                </p>
                                <p class="text-gray-600 text-sm">
                                    {{ testimonial.company }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call To Action Section -->
        </section>
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
