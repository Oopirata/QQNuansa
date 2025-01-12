# UploadCV.vue
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
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

const fileName = ref('');

const form = useForm({
    cv_file: null,
    nama_lengkap: '',
    email: '',
    lokasi: '',
    nomer_telepon: '',
    linkedin_url: '',
    gaji: ''
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        fileName.value = 'File terpilih: ' + file.name;
        form.cv_file = file;
    } else {
        fileName.value = '';
        form.cv_file = null;
    }
};

const submit = () => {
    form.post(route('cv.upload'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            fileName.value = '';
        },
    });
};
</script>

<template>
    <Head title="Upload CV" />
    
    <div class="bg-white">
        <!-- Header Section -->
        <header class="bg-blue-200 p-4 flex justify-between items-center">
            <div class="flex items-center -mr-16">
                <img alt="Company Logo" class="h-14 w-14" src="/images/QQ.png" />
            </div>
            <nav class="space-x-10 -ml-80 mr-96">
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
        <main class="flex flex-col items-center mt-6">
            <div>
                <h1 class="text-2xl font-bold mb-6 text-center">
                    Psikolog Klinis
                </h1>
                <h1 class="text-lg font-normal mb-6 text-center ml-20 mr-20">
                    Psikolog klinis yang bekerja di perusahaan konsultan memiliki peran yang unik karena mereka tidak hanya fokus pada terapi individu seperti di lingkungan klinis tradisional, tetapi juga memberikan layanan yang disesuaikan dengan kebutuhan organisasi. Berikut adalah jobdesk atau tanggung jawab umum seorang psikolog klinis dalam perusahaan konsultan:
                </h1>
            </div>
            
            <h1 class="text-2xl font-bold mb-6">
                Upload CV anda pada tombol yang ada di bawah ini
            </h1>
            
            <!-- File Upload Section -->
            <div class="flex flex-col items-center mb-6">
                <input 
                    type="file" 
                    id="cv_file" 
                    class="hidden" 
                    accept=".pdf,.doc,.docx"
                    @change="handleFileChange"
                />
                <label 
                    for="cv_file" 
                    class="bg-blue-400 text-white px-6 py-2 rounded shadow mb-2 cursor-pointer hover:bg-blue-500 transition-colors duration-200 flex items-center space-x-2"
                >
                    <i class="fas fa-upload mr-2"></i>
                    <span>Upload CV</span>
                </label>
                <div class="text-sm text-gray-600 mt-2">{{ fileName }}</div>
                <div v-if="form.errors.cv_file" class="text-red-500 text-sm mt-1">
                    {{ form.errors.cv_file }}
                </div>
            </div>

            <!-- Form Section -->
            <form @submit.prevent="submit" class="w-full max-w-lg">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="nama-lengkap">
                            Nama Lengkap
                        </label>
                        <input 
                            v-model="form.nama_lengkap"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" 
                            id="nama-lengkap" 
                            type="text"
                        />
                        <div v-if="form.errors.nama_lengkap" class="text-red-500 text-xs italic">
                            {{ form.errors.nama_lengkap }}
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">
                            Email
                        </label>
                        <input 
                            v-model="form.email"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" 
                            id="email" 
                            type="email"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="lokasi">
                            Lokasi
                        </label>
                        <input 
                            v-model="form.lokasi"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" 
                            id="lokasi" 
                            type="text"
                        />
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="nomer-telepon">
                            Nomer Telepon
                        </label>
                        <input 
                            v-model="form.nomer_telepon"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" 
                            id="nomer-telepon" 
                            type="text"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="linkedin-url">
                            LinkedIn URL
                        </label>
                        <input 
                            v-model="form.linkedin_url"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" 
                            id="linkedin-url" 
                            type="text"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="gaji">
                            Gaji yang diinginkan
                        </label>
                        <input 
                            v-model="form.gaji"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" 
                            id="gaji" 
                            type="text"
                        />
                    </div>
                </div>

                <div class="flex justify-center">
                    <button 
                        type="submit"
                        class="bg-green-300 text-white px-6 py-2 rounded hover:bg-green-400 transition-colors duration-200"
                        :disabled="form.processing"
                    >
                        Submit
                    </button>
                </div>
            </form>
        </main>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: 'Kaisei Opti', sans-serif;
}
</style>