<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
});

const showDropdown = ref(false);
const cities = ref(["Jakarta", "Surabaya", "Bandung", "Medan", "Makassar"]);

const selectCity = (city) => {
    form.lokasi = city;
    showDropdown.value = false;
};

const hideDropdown = () => {
    setTimeout(() => {
        showDropdown.value = false;
    }, 200);
};

onMounted(() => {
    AOS.init({ duration: 1200, once: true, offset: 50 });
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

const isActive = (href) => currentRoute.value === href;

const form = useForm({
    cv_file: null,
    nama_lengkap: "",
    email: "",
    lokasi: "",
    IPK: "",
    nomer_telepon: "",
    linkedin_url: "",
    gaji: "",
});

const fileName = ref("");

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        fileName.value = "File terpilih: " + file.name;
        form.cv_file = file;
    } else {
        fileName.value = "";
        form.cv_file = null;
    }
};

const submit = () => {
    const formData = new FormData();
    formData.append("cv_file", form.cv_file);
    formData.append("nama_lengkap", form.nama_lengkap);
    formData.append("email", form.email);
    formData.append("lokasi", form.lokasi);
    formData.append("IPK", form.IPK);
    formData.append("nomer_telepon", form.nomer_telepon);
    formData.append("linkedin_url", form.linkedin_url);
    formData.append("gaji", form.gaji);

    form.post(route("cv.upload"), {
        data: formData,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            fileName.value = "";
        },
    });
};
const menuItems = [
    { text: "Tentang Kami", href: "/" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];
</script>

<template>
    <Head title="Upload CV" />

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
                    class="pb-2 border-b-4 border-[#5099D5] w-fit mx-auto rounded-lg"
                >
                    <div class="flex space-x-8 px-4">
                        <Link
                            v-for="(item, index) in menuItems"
                            :key="index"
                            :href="item.href"
                            class="nav-link relative text-gray-700 text-center whitespace-nowrap transition-colors duration-200 hover:text-[#0E74B8]"
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
                            <Link
                                v-if="
                                    $page.props.auth.user.roles.some(
                                        (role) => role.role_name === 'admin'
                                    )
                                "
                                :href="route('admin.dashboard')"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
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

        <!-- Main Content -->
        <main class="flex flex-col items-center mt-6">
            <div>
                <h1 class="text-2xl font-bold mb-6 text-center">
                    {{ job.title }}
                </h1>
                <h1 class="text-lg font-normal mb-6 text-center ml-20 mr-20">
                    {{ job.description }}
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
                <div
                    v-if="form.errors.cv_file"
                    class="text-red-500 text-sm mt-1"
                >
                    {{ form.errors.cv_file }}
                </div>
            </div>

            <!-- Form Section -->
            <form @submit.prevent="submit" class="w-full max-w-lg">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="nama-lengkap"
                        >
                            Nama Lengkap
                        </label>
                        <input
                            v-model="form.nama_lengkap"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="nama-lengkap"
                            type="text"
                        />
                        <div
                            v-if="form.errors.nama_lengkap"
                            class="text-red-500 text-xs italic"
                        >
                            {{ form.errors.nama_lengkap }}
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="email"
                        >
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
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="lokasi"
                        >
                            Lokasi
                        </label>
                        <input
                            v-model="form.lokasi"
                            @focus="showDropdown = true"
                            @blur="hideDropdown"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="lokasi"
                            type="text"
                        />
                        <ul
                            v-if="showDropdown"
                            class="bg-white border border-gray-300 mt-1 rounded"
                        >
                            <li
                                v-for="(city, index) in cities"
                                :key="index"
                                @mousedown="selectCity(city)"
                                class="p-2 cursor-pointer hover:bg-gray-100"
                            >
                                {{ city }}
                            </li>
                        </ul>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="nomer-telepon"
                        >
                            IPK
                        </label>
                        <input
                            v-model="form.IPK"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="nomer-telepon"
                            type="text"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="linkedin-url"
                        >
                            Nomer Telepon
                        </label>
                        <input
                            v-model="form.nomer_telepon"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="linkedin-url"
                            type="text"
                        />
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="gaji"
                        >
                            Linkedin URL
                        </label>
                        <input
                            v-model="form.linkedin_url"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="gaji"
                            type="text"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="gaji"
                        >
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
                        class="bg-green-300 text-white px-6 py-2 rounded hover:bg-green-400 transition-colors duration-200 mb-5"
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
    background-color: #e0f2fe;
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
    color: #0e74b8;
}
</style>
