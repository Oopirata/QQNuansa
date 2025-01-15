<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref } from "vue";

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route("logout"));
};

// Jika kita perlu menggunakan asset dari Laravel, kita bisa membuat computed property
const asset = (path) => `/assets/${path}`;
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
                    href="/"
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
            <div
                class="bg-yellow-100 p-4 flex justify-center space-x-16 w-2/5 mx-auto"
            >
                <Link
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]"
                    href="/"
                >
                    Company Profile
                </Link>
                <Link
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]"
                    href="/legalitas"
                >
                    Legalitas
                </Link>
                <Link
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]"
                    href="/missions"
                >
                    Missions
                </Link>
                <Link
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]"
                    href="/services"
                >
                    Services
                </Link>
            </div>
        </main>

        <!-- Content Section -->
        <main class="p-8">
            <h1 class="text-center text-2xl font-bold mb-8 -mt-10">
                OUR SERVICES
            </h1>
            <div class="grid grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Recruitment -->
                <div class="text-center">
                    <img
                        alt="Recruitment Icon"
                        class="mx-auto mb-4"
                        height="100"
                        src="images/recruitment.png"
                        width="100"
                    />
                    <h2 class="text-xl font-bold">Recruitment</h2>
                    <p>
                        Pencarian kandidat-kandidat terbaik sesuai kualifikasi
                        yang dibutuhkan perusahaan
                    </p>
                </div>
                <!-- Psiko Test -->
                <div class="text-center">
                    <img
                        alt="Psiko Test Icon"
                        class="mx-auto mb-4"
                        height="100"
                        src="images/psiko test.jpeg"
                        width="100"
                    />
                    <h2 class="text-xl font-bold">Psiko Test</h2>
                    <p>Menggali potensi yang dimiliki oleh kandidat</p>
                </div>
                <!-- Assessment -->
                <div class="text-center">
                    <img
                        alt="Assessment Icon"
                        class="mx-auto mb-4"
                        height="100"
                        src="images/assesment.jpeg"
                        width="100"
                    />
                    <h2 class="text-xl font-bold">Assessment</h2>
                    <p>
                        Menggali potensi dan kompetensi yang dimiliki oleh
                        kandidat
                    </p>
                </div>
            </div>

            <!-- Second row with 2 items centered -->
            <div class="grid grid-cols-2 gap-8 max-w-4xl mx-auto mt-8">
                <!-- Assessment Center -->
                <div class="text-center">
                    <img
                        alt="Assessment Center Icon"
                        class="mx-auto mb-4"
                        height="100"
                        src="images/assesment center.jpeg"
                        width="100"
                    />
                    <h2 class="text-xl font-bold">Assessment Center</h2>
                    <p>
                        Menggali kompetensi kandidat melalui multi metode dan
                        multi asesor
                    </p>
                </div>
                <!-- Head Hunter -->
                <div class="text-center">
                    <img
                        alt="Head Hunter Icon"
                        class="mx-auto mb-4"
                        height="100"
                        src="images/head hunter.jpeg"
                        width="100"
                    />
                    <h2 class="text-xl font-bold">Head Hunter</h2>
                    <p>
                        Menggali kompetensi kandidat melalui multi metode dan
                        multi asesor
                    </p>
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
</style>
