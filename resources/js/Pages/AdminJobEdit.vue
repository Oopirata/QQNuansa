<script setup>
import { ref, onMounted } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

const props = defineProps({
    errors: Object,
    auth: Object,
    ziggy: Object,
    jobs: Object,
});
console.log(props.jobs);

// Initialize form with existing job data
const form = useForm({
    title: props.jobs.title || "",
    description: props.jobs.description || "",
    status: props.jobs.status === 1, // true for Open (1), false for Closed (0)
    additional_questions: props.jobs.questions?.length
        ? props.jobs.questions.map((q) => q.question_text)
        : [""],
    salary_ranges: props.jobs.salary_ranges?.length
        ? props.jobs.salary_ranges.map((range) => ({
              min_salary: range.min_salary.toString(),
              max_salary: range.max_salary.toString(),
          }))
        : [{ min_salary: "", max_salary: "" }],
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
    form.put(route("jobs.update", props.jobs.id), {
        onSuccess: () => {
            // Create and show alert dialog
            const alertDialog = document.createElement("div");
            alertDialog.className =
                "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50";

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
                    <p class="text-center text-gray-700 text-base mb-6">Job telah berhasil diperbarui</p>
                    <p class="text-center text-gray-600 text-sm">Klik di luar kotak untuk menutup</p>
                </div>
            `;

            alertDialog.addEventListener("click", function (event) {
                if (event.target === alertDialog) {
                    document.body.removeChild(alertDialog);
                }
            });

            document.body.appendChild(alertDialog);
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};

const goBack = (id) => {
    router.visit(route("adminJobDetail", id));
};
</script>

<template>
    <Head title="Edit Job" />
    <div class="flex h-screen ml-64">
        <Sidebar :user="$page.props.auth.user" />

        <!-- Main Content -->
        <div class="w-4/5 p-8">
            <div class="flex justify-between items-center mb-8">
                <button @click="goBack(jobs.id)" class="text-xl">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h2 class="text-xl font-bold">Edit Job</h2>
                <div class="flex gap-4">
                    <button
                        @click="goBack(jobs.id)"
                        class="bg-gray-500 text-white px-4 py-2 rounded"
                    >
                        Cancel
                    </button>
                    <button
                        @click="submitJob"
                        :disabled="form.processing"
                        class="bg-purple-500 text-white px-4 py-2 rounded disabled:opacity-75 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? "Processing..." : "Save Changes" }}
                    </button>
                </div>
            </div>

            <div class="flex">
                <!-- Left Column -->
                <div class="w-1/2 pr-4">
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block">Job Title</label>
                            <div class="flex items-center gap-2">
                                <span class="text-sm">Status:</span>
                                <select
                                    v-model="form.status"
                                    class="p-2 border rounded bg-white appearance-none pr-10"
                                >
                                    <option :value="true">Open</option>
                                    <option :value="false">Closed</option>
                                </select>
                            </div>
                        </div>
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
                                <button
                                    v-if="form.additional_questions.length > 1"
                                    type="button"
                                    @click="removeQuestion(index)"
                                    class="text-red-500 hover:text-red-700"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
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
                            class="bg-purple-500 text-white px-4 py-2 rounded w-full hover:bg-purple-600 transition-colors mb-5"
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
