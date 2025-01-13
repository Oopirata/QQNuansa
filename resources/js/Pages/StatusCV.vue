<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref } from 'vue';

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};

// Define props for user data
defineProps({
    auth: {
        type: Object,
        required: true,
    },
    userData: {
        type: Object,
        default: () => ({
            name: 'Surya Fajar',
            email: 'suryafajar04@gmail.com',
            phone: '08767512341',
            location: 'Semarang',
            linkedinUrl: '-',
            desiredPosition: '-',
            desiredSalary: '-'
        })
    }
});
</script>

<template>
    <Head title="Upload Confirmation" />
    
    <div class="bg-gray-100">
        <!-- Header Section -->
        <header class="bg-blue-200 p-4 flex justify-between items-center">
            <div class="flex items-center -mr-16">
                <img alt="Company Logo" class="h-14 w-14" src="/images/QQ.png" />
            </div>
            <nav class="space-x-10 -ml-60 mr-80">
                <Link 
                    class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]" 
                    href="/companyProfile"
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

        <!-- Main Content -->
        <main class="p-8">
            <div class="bg-white p-6 rounded shadow-md">
                <h1 class="text-xl font-bold mb-4">
                    CV berhasil di-Upload! silahkan tunggu approval HRD untuk lanjut ke tahap Interview
                </h1>
                
                <div class="mb-4">
                    <span class="font-bold">Status</span>:
                    <span class="bg-yellow-200 text-gray-700 px-4 py-2 rounded ml-2">
                        Menunggu Verifikasi
                    </span>
                </div>
                
                <div class="mb-4">
                    <div class="grid grid-cols-[200px_1fr] gap-2">
                        <span class="font-bold">Nama</span>
                        <span>: {{ userData.name }}</span>
                        
                        <span class="font-bold">Email</span>
                        <span>: {{ userData.email }}</span>
                        
                        <span class="font-bold">Nomer Telepon</span>
                        <span>: {{ userData.phone }}</span>
                        
                        <span class="font-bold">Location</span>
                        <span>: {{ userData.location }}</span>
                        
                        <span class="font-bold">LinkedIn URL</span>
                        <span>: {{ userData.linkedinUrl }}</span>

                        <span class="font-bold">Posisi yang diinginkan</span>
                        <span>: {{ userData.desiredPosition }}</span>

                        <span class="font-bold">Gaji yang diinginkan</span>
                        <span>: {{ userData.desiredSalary }}</span>
                    </div>
                </div>

                <p class="text-gray-700">
                    Cek Email untuk update info status yang lebih cepat dan tepat, Good luck!!
                </p>
            </div>
        </main>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: 'Kaisei Opti', sans-serif;
}</style>