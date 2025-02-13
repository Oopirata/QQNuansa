<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import { router } from "@inertiajs/vue3";

const dropdownOpen = ref(false);
const dropdownRef = ref(null);

const showNotification = ref(false);

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

// Menutup dropdown ketika klik di luar
const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        dropdownOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", closeDropdown);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", closeDropdown);
});

const form = useForm({
    title: "",
    description: "",
    additional_questions: [""],
    salary_ranges: [{ min_salary: "", max_salary: "" }],
});

// Method untuk pertanyaan tambahan
const addQuestion = () => {
    form.additional_questions.push("");
};

const removeQuestion = (index) => {
    if (form.additional_questions.length > 1) {
        form.additional_questions.splice(index, 1);
    }
};

// Method untuk salary range
const addSalaryRange = () => {
    form.salary_ranges.push({ min_salary: "", max_salary: "" });
};

const formatSalary = (value) => {
    // Hapus semua karakter non-digit
    const numericValue = value.replace(/\D/g, "");
    // Format dengan ribuan separator
    return new Intl.NumberFormat("id-ID").format(numericValue);
};

const updateSalary = (index, type, event) => {
    let value = event.target.value;
    // Hapus format sebelum menyimpan ke form
    const numericValue = value.replace(/\D/g, "");

    if (type === "min") {
        form.salary_ranges[index].min_salary = numericValue;
    } else {
        form.salary_ranges[index].max_salary = numericValue;
    }
};

const submitJob = () => {
    form.post(route("jobs.store"), {
        onSuccess: () => {
            // Create and show alert dialog
            const alertDialog = document.createElement("div");
            alertDialog.className =
                "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50";

            // HTML untuk dialog
            alertDialog.innerHTML = `
                <div class="bg-white p-8 rounded-lg shadow-xl max-w-sm mx-4" onclick="event.stopPropagation()">
                    <div class="flex items-center justify-center mb-6">
                        <div class="bg-green-100 rounded-full p-4">
                            <svg class="h-10 w-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-center text-gray-800 mb-3">Berhasil!</h3>
                    <p class="text-center text-gray-700 text-base mb-6">Posisi baru telah berhasil ditambahkan</p>
                    <p class="text-center text-gray-600 text-sm">Klik di luar kotak untuk menutup</p>
                </div>
            `;

            // Event handler untuk menutup dialog ketika mengklik background
            alertDialog.addEventListener("click", function (event) {
                if (event.target === alertDialog) {
                    document.body.removeChild(alertDialog);
                    window.location.href = route("admin.dashboard");
                }
            });

            document.body.appendChild(alertDialog);
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};

const isSubMenuOpen = ref(false);

const candidateItems = [
    { name: "New", path: "/adminNewCandidates" },
    { name: "Screened", path: "/adminScreenedCandidates" },
    { name: "Interview", path: "/adminInterviewCandidates" },
    { name: "Rejected", path: "/adminRejectedCandidates" },
];

const toggleSubMenu = () => {
    isSubMenuOpen.value = !isSubMenuOpen.value;
};
</script>

<template>
    <Head title="New Job" />
    <div class="flex h-screen">
        <Sidebar :user="$page.props.auth.user" />

        <!-- Main Content -->
        <div class="w-4/5 p-8">
            <div class="flex justify-between items-center mb-8">
                <a href="/adminDashboard" class="text-xl">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-xl font-bold">New job</h2>
                <button
                    @click="submitJob"
                    :disabled="form.processing"
                    class="bg-purple-500 text-white px-4 py-2 rounded disabled:opacity-75 disabled:cursor-not-allowed"
                >
                    {{ form.processing ? "Processing..." : "Post job" }}
                </button>
            </div>
            <div class="flex">
                <!-- Left Column -->
                <div class="w-1/2 pr-4">
                    <div class="mb-4">
                        <label class="block mb-2">Job Title</label>
                        <input
                            v-model="form.title"
                            type="text"
                            class="w-full p-2 border rounded"
                            :class="{ 'border-red-500': form.errors.title }"
                        />
                        <div
                            v-if="form.errors.title"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ form.errors.title }}
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2">Job Description</label>
                        <textarea
                            v-model="form.description"
                            class="w-full p-2 border rounded h-32"
                            :class="{
                                'border-red-500': form.errors.description,
                            }"
                        ></textarea>
                        <div
                            v-if="form.errors.description"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ form.errors.description }}
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="w-1/2 pl-4">
                    <div class="mb-8">
                        <h3 class="font-bold mb-4">Additional Question</h3>
                        <div
                            v-for="(
                                question, index
                            ) in form.additional_questions"
                            :key="index"
                            class="mb-4"
                        >
                            <div class="flex items-center gap-4">
                                <input
                                    v-model="form.additional_questions[index]"
                                    type="text"
                                    placeholder="Masukkan pertanyaan tambahan"
                                    class="w-full p-2 border rounded"
                                />
                                <!-- Tombol hapus jika lebih dari satu pertanyaan -->
                                <button
                                    v-if="form.additional_questions.length > 1"
                                    type="button"
                                    @click="removeQuestion(index)"
                                    class="text-red-500 hover:text-red-700"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <!-- Pesan error jika ada -->
                            <div
                                v-if="
                                    form.errors[`additional_questions.${index}`]
                                "
                                class="text-red-500 text-sm mt-1"
                            >
                                {{
                                    form.errors[`additional_questions.${index}`]
                                }}
                            </div>
                        </div>

                        <!-- Tombol Tambah Pertanyaan -->
                        <button
                            @click="addQuestion"
                            class="bg-purple-500 text-white px-4 py-2 rounded w-full"
                        >
                            + Tambah Pertanyaan
                        </button>
                    </div>
                    <div>
                        <h3 class="font-bold mb-4">Salary Range</h3>
                        <div
                            v-for="(range, index) in form.salary_ranges"
                            :key="index"
                            class="mb-4"
                        >
                            <div class="flex items-center gap-4">
                                <div class="relative flex-1">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"
                                        >Rp</span
                                    >
                                    <input
                                        :value="formatSalary(range.min_salary)"
                                        @keypress="
                                            (e) => {
                                                if (!/[0-9]/.test(e.key)) {
                                                    e.preventDefault();
                                                }
                                            }
                                        "
                                        @input="
                                            (e) => updateSalary(index, 'min', e)
                                        "
                                        type="text"
                                        placeholder="5.000.000"
                                        class="w-full p-2 pl-12 border rounded"
                                    />
                                </div>
                                <span class="text-gray-500">-</span>
                                <div class="relative flex-1">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"
                                        >Rp</span
                                    >
                                    <input
                                        :value="formatSalary(range.max_salary)"
                                        @input="
                                            (e) => updateSalary(index, 'max', e)
                                        "
                                        type="text"
                                        placeholder="8.000.000"
                                        class="w-full p-2 pl-12 border rounded"
                                    />
                                </div>
                                <button
                                    v-if="form.salary_ranges.length > 1"
                                    type="button"
                                    @click="form.salary_ranges.splice(index, 1)"
                                    class="text-red-500 hover:text-red-700"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div
                                v-if="
                                    form.errors[
                                        `salary_ranges.${index}.min_salary`
                                    ]
                                "
                                class="text-red-500 text-sm mt-1"
                            >
                                {{
                                    form.errors[
                                        `salary_ranges.${index}.min_salary`
                                    ]
                                }}
                            </div>
                            <div
                                v-if="
                                    form.errors[
                                        `salary_ranges.${index}.max_salary`
                                    ]
                                "
                                class="text-red-500 text-sm mt-1"
                            >
                                {{
                                    form.errors[
                                        `salary_ranges.${index}.max_salary`
                                    ]
                                }}
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="addSalaryRange"
                            class="bg-purple-500 text-white px-4 py-2 rounded w-full hover:bg-purple-600 transition-colors"
                        >
                            + Add Salary Range
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
</style>
