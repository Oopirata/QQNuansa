<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';


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
                    href="/uploadcv"
                >
                    We are hiring!!
                </Link>
            </div>
            <div class="flex items-center space-x-2">
                <Link href="/login">
                    <button class="bg-green-200 text-green-800 px-4 py-2 rounded mr-3">
                        Log In
                    </button>
                </Link>
                <i class="fas fa-user-circle text-blue-900 text-3xl"></i>
            </div>
        </header>

        <!-- Navigation Menu -->
        <main class="p-8 -mt-8">
            <div class="bg-yellow-100 p-4 flex justify-center space-x-16 w-2/5 mx-auto">
                <Link 
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]" 
                    href="/companyprofile"
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