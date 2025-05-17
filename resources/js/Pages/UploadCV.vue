<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { usePage } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
// Import SweetAlert library
import Swal from "sweetalert2";

const isActive = (href) => {
    return currentRoute.value === href;
};

const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
});
console.log(props.job);

// State untuk provinsi dan kota
const provinces = ref([]);
const cities = ref([]);
const selectedProvince = ref(null);
const selectedCity = ref(null);
const isLoadingCities = ref(false);

// Fetch data provinsi saat komponen dimount
onMounted(async () => {
    AOS.init({ duration: 1200, once: true, offset: 50 });
    document.addEventListener("click", closeDropdown);

    try {
        const response = await fetch(
            "https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json"
        );
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        provinces.value = data.map((province) => ({
            value: province.id,
            label: province.name,
        }));
    } catch (error) {
        console.error("Error fetching provinces:", error);
    }
});

// Fetch data kota ketika provinsi dipilih
const handleProvinceChange = async (provinceId) => {
    selectedCity.value = null;
    form.lokasi = "";

    if (!provinceId) return;

    isLoadingCities.value = true;
    try {
        const response = await fetch(
            `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`
        );
        const data = await response.json();
        cities.value = data.map((city) => ({
            value: city.id,
            label: city.name,
        }));
    } catch (error) {
        console.error("Error fetching cities:", error);
    } finally {
        isLoadingCities.value = false;
    }
};

const handleCityChange = (cityId) => {
    if (!cityId) {
        form.lokasi = "";
        return;
    }
    const province = provinces.value.find(
        (p) => p.value === selectedProvince.value
    );
    const city = cities.value.find((c) => c.value === cityId);
    if (province && city) {
        form.lokasi = `${province.label}, ${city.label}`; // Format: "Jawa Barat, Bandung"
    }
};

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

const form = useForm({
    cv_file: null,
    nama_lengkap: "",
    email: "",
    lokasi: "",
    IPK: "",
    degree: "",
    nomer_telepon: "",
    linkedin_url: "",
    salary_range_id: "",
    answers: {},
});

const salaryRangeOptions = computed(() => {
    return props.job.salary_ranges.map((range) => ({
        value: range.id,
        label: `Rp ${new Intl.NumberFormat("id-ID").format(
            range.min_salary
        )} - Rp ${new Intl.NumberFormat("id-ID").format(range.max_salary)}`,
    }));
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

// Function to validate the form before submission
const validateForm = () => {
    const requiredFields = {
        "CV File": form.cv_file,
        "Nama Lengkap": form.nama_lengkap,
        Email: form.email,
        Lokasi: form.lokasi,
        IPK: form.IPK,
        "Jenjang Pendidikan": form.degree,
        "Nomor Telepon": form.nomer_telepon,
        "Range Gaji": form.salary_range_id,
    };

    // Check for required answers to job questions
    const missingAnswers = props.job.questions.filter(
        (question) => !form.answers[question.id]
    );

    // Check for missing required fields
    const missingFields = Object.entries(requiredFields)
        .filter(([_, value]) => !value)
        .map(([field]) => field);

    if (missingFields.length > 0 || missingAnswers.length > 0) {
        let errorMessage = "Harap lengkapi kolom berikut:";

        if (missingFields.length > 0) {
            errorMessage += `<br>- ${missingFields.join("<br>- ")}`;
        }

        if (missingAnswers.length > 0) {
            if (missingFields.length > 0) errorMessage += "<br>";
            errorMessage += `<br>- ${missingAnswers.length} pertanyaan belum dijawab`;
        }

        Swal.fire({
            title: "Form belum lengkap!",
            html: errorMessage,
            icon: "error",
            confirmButtonText: "Mengerti",
        });

        return false;
    }

    return true;
};

const submit = () => {
    // Validate form before submission
    if (!validateForm()) return;

    form.post(route("cv.upload", { id: props.job.id }), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                title: "Berhasil!",
                text: "Formulir Anda telah berhasil terkirim. Kami akan menghubungi Anda jika ada perkembangan lebih lanjut.",
                icon: "success",
                confirmButtonText: "OK",
            });

            form.reset();
            fileName.value = "";
            selectedProvince.value = null;
            selectedCity.value = null;
        },
        onError: (errors) => {
            console.error(errors);

            // Show validation errors from server
            const errorMessages = Object.values(errors).join("<br>");

            Swal.fire({
                title: "Terjadi Kesalahan!",
                html: errorMessages,
                icon: "error",
                confirmButtonText: "Coba Lagi",
            });
        },
    });
};

const menuItems = [
    { text: "Tentang Kami", href: "/companyprofile" },
    { text: "Legalitas", href: "/legalitas" },
    { text: "Misi", href: "/missions" },
    { text: "Layanan", href: "/services" },
];

// Add new reactive refs for IPK validation
const ipkError = ref(false);
const ipkErrorMessage = ref("");

// Add IPK validation function
const validateIPK = () => {
    // Replace any dot with comma
    form.IPK = form.IPK.replace(".", ",");

    // Only allow numbers and one comma
    form.IPK = form.IPK.replace(/[^0-9,]/g, "");

    // Ensure only one comma
    const commaCount = (form.IPK.match(/,/g) || []).length;
    if (commaCount > 1) {
        form.IPK = form.IPK.replace(/,/g, (match, index) =>
            index === form.IPK.indexOf(",") ? "," : ""
        );
    }

    // Ensure max one decimal place
    const parts = form.IPK.split(",");
    if (parts[1] && parts[1].length > 1) {
        parts[1] = parts[1].slice(0, 1);
        form.IPK = parts.join(",");
    }

    // Validate the numeric value
    const numericValue = parseFloat(form.IPK.replace(",", "."));

    if (numericValue > 4) {
        form.IPK = "4,0";
        ipkError.value = true;
        ipkErrorMessage.value = "IPK maksimal adalah 4,0";
    } else if (numericValue < 0) {
        ipkError.value = true;
        ipkErrorMessage.value = "IPK minimal adalah 0,0";
    } else {
        ipkError.value = false;
        ipkErrorMessage.value = "";
    }
};
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
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.nama_lengkap"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="nama-lengkap"
                            type="text"
                            placeholder="Masukkan nama lengkap anda"
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
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.email"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="email"
                            type="email"
                            placeholder="Masukkan Email anda"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="lokasi"
                        >
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <Multiselect
                                v-model="selectedProvince"
                                :options="provinces"
                                track-by="value"
                                label="label"
                                :searchable="true"
                                placeholder="Pilih Provinsi"
                                @change="handleProvinceChange"
                                class="appearance-none block w-full text-gray-700 rounded"
                            />
                            <Multiselect
                                v-model="selectedCity"
                                :options="cities"
                                track-by="value"
                                label="label"
                                :searchable="true"
                                placeholder="Pilih Kota/Kabupaten"
                                :disabled="!selectedProvince || isLoadingCities"
                                @change="handleCityChange"
                                class="appearance-none block w-full text-gray-700 rounded"
                            />
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="ipk"
                        >
                            IPK <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                v-model="form.IPK"
                                @input="validateIPK"
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-1 leading-tight focus:outline-none focus:bg-white"
                                :class="{ 'border-red-500': ipkError }"
                                id="ipk"
                                type="text"
                                placeholder="Masukkan IPK"
                            />
                            <div
                                class="absolute right-3 top-3 text-sm text-gray-500"
                            >
                                Format: x,x
                            </div>
                        </div>
                        <div
                            v-if="ipkError"
                            class="text-red-500 text-xs italic"
                        >
                            {{ ipkErrorMessage }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="nomer-telepon"
                        >
                            Jenjang pendidikan terakhir
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.degree"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="degree"
                            type="text"
                            placeholder="Masukkan Jenjang pendidikan terakhir anda"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="nomer-telepon"
                        >
                            Nomer Telepon <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.nomer_telepon"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="nomer-telepon"
                            type="text"
                            placeholder="Masukkan Nomer Telepon anda"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="linkedin"
                        >
                            Linkedin URL
                        </label>
                        <input
                            v-model="form.linkedin_url"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="linkedin"
                            type="text"
                            placeholder="Masukkan LinkedIn URL anda"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="salary_range"
                        >
                            Pilih Range Gaji <span class="text-red-500">*</span>
                        </label>
                        <Multiselect
                            v-model="form.salary_range_id"
                            :options="salaryRangeOptions"
                            track-by="value"
                            label="label"
                            :searchable="true"
                            placeholder="Pilih range gaji yang diinginkan"
                            class="appearance-none block w-full text-gray-700 rounded"
                        />
                        <div
                            v-if="form.errors.salary_range_id"
                            class="text-red-500 text-xs italic mt-1"
                        >
                            {{ form.errors.salary_range_id }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div
                        class="w-full px-3"
                        v-for="question in props.job.questions"
                        :key="question.id"
                    >
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        >
                            {{ question.question_text }}
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="form.answers[question.id]"
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            rows="3"
                            :placeholder="`Masukkan Jawaban anda`"
                        ></textarea>
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
