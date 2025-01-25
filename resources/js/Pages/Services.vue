<!-- Content Section -->
<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref, onMounted, onBeforeUnmount } from "vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

onMounted(() => {
    AOS.init({
        duration: 1200,
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

// const showingNavigationDropdown = ref(false);

// const logout = () => {
//     router.post(route("logout"));
// };

// const asset = (path) => `/assets/${path}`;

const menuItems = [
    { text: "Tentang Kami", href: "/" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

const topRowTags = [
    { text: "Assessment", href: "/serviceassesment" }, // Update href untuk Assessment
    { text: "Psikotes", href: "/servicepsikotest" },
    { text: "Konseling", href: "/servicekonseling" },
    { text: "Coaching", href: "/servicecoaching" },
    { text: "Outbond", href: "/servicesoutbond" },
];

const bottomRowTags = [
    { text: "Seminar", href: "/servicesseminar" },
    { text: "Training", href: "/servicestraining" },
    { text: "Hypnotheraphy", href: "/serviceshypnotheraphy" },
    { text: "Finger Test STIFIN", href: "/servicesSTIFIN" },
];
</script>

<template>
    <Head title="Services" />

    <div class="bg-white">
        <!-- Header Section -->
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
                            <Link v-if="$page.props.auth.user.roles.some(role => role.role_name === 'admin')" 
                                :href="route('admin.dashboard')" 
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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

        <!-- Tags Content Section -->
        <main class="p-8" data-aos="fade-up">
            <h1 class="text-center text-2xl font-bold -mt-5">Layanan Kami</h1>
            <div class="max-w-7xl mx-auto mt-8">
                <!-- Top Row Tags -->
                <div
                    class="flex flex-wrap gap-3 justify-center mb-4"
                    data-aos="fade-right"
                >
                    <Link
                        v-for="tag in topRowTags"
                        :key="tag.text"
                        :href="tag.href"
                        class="tag-link"
                    >
                        {{ tag.text }}
                    </Link>
                </div>

                <!-- Bottom Row Tags -->
                <div class="flex flex-wrap gap-3 justify-center mb-12">
                    <Link
                        v-for="tag in bottomRowTags"
                        :key="tag.text"
                        :href="tag.href"
                        class="tag-link"
                    >
                        {{ tag.text }}
                    </Link>
                </div>

                <!-- Gallery Grid -->
                <div class="grid grid-cols-3 gap-4 max-w-6xl mx-auto mt-8">
                    <!-- Gallery Item 1 -->
                    <div
                        class="relative overflow-hidden rounded-lg aspect-square"
                        data-aos="zoom-in"
                    >
                        <img
                            src="/images/sakjose.jpeg"
                            alt="Gallery Image 1"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                        />
                    </div>
                    <!-- Gallery Item 2 -->
                    <div
                        class="relative overflow-hidden rounded-lg aspect-square"
                        data-aos="zoom-in"
                    >
                        <img
                            src="/images/asik.jpg"
                            alt="Gallery Image 2"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                        />
                    </div>
                    <!-- Gallery Item 3 -->
                    <div
                        class="relative overflow-hidden rounded-lg aspect-auto"
                    >
                        <img
                            src="/images/seminar 2.jpeg"
                            alt="Gallery Image 3"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                        />
                    </div>
                </div>
                <p class="text-xl mt-10 mr-56 mb-10" data-aos="zoom-in">
                    PT. Ara Nuansa Katumbiri menawarkan tiga layanan utama yang
                    sesuai dengan keahlian dan bidang jasa yang dikelola.
                    Layanan pertama adalah CONSULTING, yang berfokus pada jasa
                    konsultan di bidang manajemen sumber daya manusia, terutama
                    untuk pengembangan organisasi. Layanan kedua adalah LEARNING
                    CENTER, yang menyediakan jasa pelatihan dan pengembangan
                    sumber daya manusia melalui berbagai program, seperti
                    inhouse training, public training, dan outbound, yang
                    dirancang untuk memenuhi kebutuhan spesifik organisasi atau
                    perusahaan. Layanan ketiga adalah RECRUITMENT AND
                    ASSESSMENT, yang bertujuan untuk mencari, menyeleksi, dan
                    merekomendasikan talenta terbaik untuk dikembangkan lebih
                    lanjut, baik dalam dunia industri maupun dunia pendidikan
                </p>
                <div class="max-w-6xl mx-auto">
                    <div class="relative overflow-hidden rounded-lg mt-5">
                        <img
                            src="/images/seminar.jpg"
                            alt="asselole"
                            class="object-cover hover:scale-105 transition-transform duration-300"
                        />
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
