<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref } from 'vue';

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};

defineProps({
    auth: {
        type: Object,
        required: true,
    },
});

// Jika kita perlu menggunakan asset dari Laravel, kita bisa membuat computed property
const asset = (path) => `/assets/${path}`;
</script>

<template>
    <Head title="Company Profile" />
    
    <div class="bg-white">
        <!-- Header Section -->
        <header class="bg-blue-200 p-4 flex justify-between items-center">
            <div class="flex items-center -mr-16">
                <img alt="Company Logo" class="h-14 w-14" src="/images/QQ.png" />
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
            <!-- Auth Buttons -->
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
                                    <i class="fas fa-user-circle text-blue-900 text-3xl ml-2"></i>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">
                                Profile
                            </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </template>
                <template v-else>
                    <Link href="/login">
                        <button class="bg-green-200 text-green-800 px-4 py-2 rounded mr-3">
                            Log In
                        </button>
                    </Link>
                    <i class="fas fa-user-circle text-blue-900 text-3xl"></i>
                </template>
            </div>            
        </header>

        <!-- Navigation Menu -->
        <main class="p-8 -mt-8">
            <div class="bg-yellow-100 p-4 flex justify-center space-x-16 w-2/5 mx-auto">
                <Link 
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]" 
                    href="/companyProfileAuth"
                >
                    Company Profile
                </Link>
                <Link 
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]" 
                    href="/legalitasAuth"
                >
                    Legalitas
                </Link>
                <Link 
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]" 
                    href="/missionsAuth"
                >
                    Missions
                </Link>
                <Link 
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]" 
                    href="/servicesAuth"
                >
                    Services
                </Link>
            </div>
        </main>

        <!-- Content Section -->
        <section class="mt-4 ml-40 mr-40">
            <img alt="Legalitas" class=" w-3/4 align-middle justify-center ml-32 "  src="/images/Legalitas.png"/>
        </section>
    </div>
</template>

<style scoped>
/* Menerapkan font ke seluruh komponen */
:deep(*) {
    font-family: 'Kaisei Opti', sans-serif;
}
</style>