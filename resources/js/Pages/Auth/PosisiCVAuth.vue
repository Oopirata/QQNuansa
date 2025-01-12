<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const showDropdown = ref(false);

// Available positions
const positions = [
    'Psikolog Klinis', 'Konselor', 'Asisten Psikolog', 'Administrator',
    'Resepsionis', 'Manajer Klinik', 'Koordinator Program', 'Psikometris',
    'Asisten Administrasi', 'Tenaga IT'
];

// Form handling
const form = useForm({
    position: '',
});

const submitPosition = (position) => {
    form.post(route('positions.apply'), {
        preserveScroll: true,
        onSuccess: () => {
            showDropdown.value = false;
        },
    });
};

defineProps({
    success: {
        type: String,
        default: ''
    }
});
</script>

<template>
    <Head title="Pilih Posisi" />
    
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
                    href="/posisicv"
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
        <div class="min-h-screen flex flex-col items-center justify-center -mt-24">
            <div class="text-center">
                <div v-if="success" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ success }}
                </div>

                <img src="/images/posisi.jpg" 
                     alt="Cartoon figure with a tie standing next to a large magnifying glass" 
                     class="mx-auto mb-4" 
                     width="200" 
                     height="200"
                />
                
                <p class="text-gray-700 mb-4">
                    Mau masuk di posisi yang mana?
                </p>

                <div class="flex justify-center">
                    <div class="relative">
                        <button @click="showDropdown = !showDropdown" 
                                type="button"
                                class="bg-blue-100 text-gray-700 py-2 px-4 rounded w-48 flex items-center justify-between">
                            <span>---Pilih Posisi---</span>
                            <span :class="{'rotate-180': showDropdown}" 
                                  class="transition-transform duration-200">
                                ▼
                            </span>
                        </button>

                        <div v-show="showDropdown" 
                             @click.away="showDropdown = false"
                             class="absolute mt-1 w-48 bg-white border border-gray-200 rounded shadow-lg z-10">
                            <button v-for="position in positions"
                                    :key="position"
                                    type="button"
                                    @click="submitPosition(position)"
                                    class="w-full text-left px-4 py-2 hover:bg-blue-50 text-gray-700">
                                {{ position }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: 'Kaisei Opti', sans-serif;
}
</style>