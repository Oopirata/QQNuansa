<!-- Content Section -->
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

const asset = (path) => `/assets/${path}`;

const menuItems = [
    { text: "Tentang Kami", href: "/" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

const topRowTags = [
    "Assesment",
    "Psikotes",
    "Konseling",
    "Coaching",
    "Outbond",
];

const bottomRowTags = [
    "Seminar",
    "Training",
    "Hypnotheraphy",
    "Finger Test STIFIN",
];
</script>

<template>
    <Head title="Company Profile" />

    <div class="bg-white">
        <!-- Header Section -->
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

        <!-- Navigation Menu -->
        <!-- <main class="p-8">
            <div
                class="pb-2 border-b-4 border-[#CDC052] flex justify-center w-2/5 mx-auto rounded-lg"
            >
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
        </main> -->

        <!-- Tags Content Section -->
        <main class="p-8">
            <h1 class="text-center text-2xl font-bold -mt-5">Layanan Kami</h1>
            <div class="max-w-7xl mx-auto mt-8">
                <!-- Top Row Tags -->
                <div class="flex flex-wrap gap-3 justify-center mb-4">
                    <Link
                        v-for="tag in topRowTags"
                        :key="tag"
                        :href="`/tag/${tag.toLowerCase()}`"
                        class="tag-link"
                    >
                        {{ tag }}
                    </Link>
                </div>

                <!-- Bottom Row Tags -->
                <div class="flex flex-wrap gap-3 justify-center mb-12">
                    <Link
                        v-for="tag in bottomRowTags"
                        :key="tag"
                        :href="`/tag/${tag.toLowerCase()}`"
                        class="tag-link"
                    >
                        {{ tag }}
                    </Link>
                </div>

                <!-- Gallery Grid -->
                <div class="grid grid-cols-3 gap-4 max-w-6xl mx-auto mt-8">
                    <!-- Gallery Item 1 -->
                    <div
                        class="relative overflow-hidden rounded-lg aspect-square"
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
