# CompanyProfile.vue
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

        <!-- Rest of your existing template code remains the same -->
        <main class="p-8 -mt-8">
            <!-- Navigation Menu -->
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
            <h1 class="text-center text-2xl font-bold">
                COMPANY PROFILE
            </h1>
            <p class="mt-4">
                PT. Ara Nuansa Katumbiri (ANK) adalah perusahaan konsultan yang berfokus pada pengembangan manajemen sumber daya manusia. Berdiri sejak tahun 2003, perusahaan ini sebelumnya dikenal dengan nama QQNuansa Consultant dan berkantor pusat di Pekalongan, Jawa Tengah. Meskipun demikian, area kerja dan distribusi layanan PT. Ara Nuansa Katumbiri mencakup seluruh wilayah Indonesia, dengan konsentrasi utama di wilayah Jawa Tengah.
            </p>
            <p class="mt-4">
                Perusahaan ini menawarkan tiga layanan utama yang menjadi inti kompetensi dan kegiatan bisnisnya:
            </p>
            <ol class="list-decimal list-inside mt-4">
                <li class="mt-2">
                    <strong>Consulting</strong>
                    <br/>
                    Layanan ini menyediakan jasa konsultan manajemen sumber daya manusia untuk organisasi. Fokus utama adalah membantu organisasi dalam meningkatkan efektivitas pengelolaan SDM melalui pendekatan profesional dan strategis, termasuk penyusunan strategi organisasi, pengembangan sistem SDM, dan solusi manajemen yang relevan.
                </li>
                <li class="mt-2">
                    <strong>Learning Center</strong>
                    <br/>
                    Melalui layanan ini, PT. Ara Nuansa Katumbiri menyelenggarakan berbagai program pelatihan dan pengembangan SDM. Kegiatan yang ditawarkan meliputi:
                </li>
                <li class="mt-2">
                    <strong>Recruitment and Assessment</strong>
                    <br/>
                    Layanan ini bertujuan membantu organisasi dalam mencari, memilih, dan merekomendasikan individu-individu dengan bakat terbaik untuk dikembangkan lebih lanjut. Proses ini mencakup rekrutmen dan penilaian calon karyawan secara menyeluruh, baik untuk kebutuhan industri maupun pendidikan, guna memastikan organisasi mendapatkan SDM yang berkualitas.Dengan berfokus pada pengelolaan sumber daya manusia, PT. Ara Nuansa Katumbiri berperan sebagai mitra strategis bagi organisasi dalam menciptakan tenaga kerja yang produktif dan kompetitif.
                </li>
            </ol>
        </section>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: 'Kaisei Opti', sans-serif;
}
</style>