<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref, onMounted, onBeforeUnmount } from "vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

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

// Initialize AOS
onMounted(() => {
    AOS.init({
        duration: 1200,
        once: true,
        offset: 50,
    });
    document.addEventListener("click", closeDropdown);
});

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
    <header
        class="p-4 flex justify-between items-center mt-2 container mx-auto"
        data-aos="fade-down"
    >
        <!-- Logo Section -->
        <div
            class="flex items-center"
            data-aos="fade-right"
            data-aos-delay="200"
        >
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
        <nav class="flex-grow px-20" data-aos="fade-up" data-aos-delay="400">
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
        <div
            class="flex items-center space-x-6"
            data-aos="fade-left"
            data-aos-delay="600"
        >
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
    <div class="bg-white">
        <!-- Content Section -->
        <section class="mt-4 ml-40 mr-40">
            <img
                alt="Legalitas"
                class="w-3/4 align-middle justify-center ml-32"
                src="/images/Legalitas.png"
                data-aos="fade-up"
                data-aos-duration="1500"
                data-aos-delay="800"
            />
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
