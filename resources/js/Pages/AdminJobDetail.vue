<script setup>
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import dayjs from "dayjs";

const props = defineProps({
    jobs: {
        type: Object,
        required: true,
    },
});

console.log(props.jobs);

const formatSalary = (value) => {
    return new Intl.NumberFormat("id-ID").format(value);
};

const getStatusLabel = (status) => {
    switch (status) {
        case 1:
            return "Active";
        case 0:
            return "Inactive";
        default:
            return "Unknown";
    }
};

const getStatusColor = (status) => {
    switch (status) {
        case 1:
            return "bg-green-100 text-green-800";
        case 0:
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};

const goToEdit = (id) => {
    router.visit(route("jobs.edit", id));
};

const getApplicantStatusLabel = (status) => {
    switch (status) {
        case 0:
            return "New";
        case 1:
            return "Screened";
        case 2:
            return "Interview";
        case 3:
            return "Rejected";
        default:
            return "Unknown";
    }
};
</script>

<template>
    <Head title="Job Detail" />
    <div class="flex h-screen ml-64">
        <Sidebar :user="$page.props.auth.user" />

        <!-- Main Content -->
        <div class="w-4/5 p-8">
            <div class="flex justify-between items-center mb-8">
                <a href="/adminJobList" class="text-xl">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-xl font-bold">Job Details</h2>
                <div class="flex gap-4">
                    <button
                        @click="goToEdit(jobs.id)"
                        class="bg-blue-500 text-white px-4 py-2 rounded"
                    >
                        Edit Job
                    </button>
                </div>
            </div>

            <div class="flex">
                <!-- Left Column -->
                <div class="w-1/2 pr-4">
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <label class="font-bold">Job Title</label>
                            <span
                                :class="`px-2 py-1 rounded-full text-sm ${getStatusColor(
                                    jobs?.status
                                )}`"
                            >
                                {{ getStatusLabel(jobs?.status) }}
                            </span>
                        </div>
                        <div class="p-2 bg-gray-50 rounded">
                            {{ jobs?.title || "No title available" }}
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 font-bold"
                            >Job Description</label
                        >
                        <div
                            class="p-2 bg-gray-50 rounded min-h-[8rem] whitespace-pre-wrap"
                        >
                            {{
                                jobs?.description || "No description available"
                            }}
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        <div>
                            Created:
                            {{
                                dayjs(jobs?.created_at).format(
                                    "DD MMMM YYYY HH:mm"
                                )
                            }}
                        </div>
                        <div>
                            Last Updated:
                            {{
                                dayjs(jobs?.updated_at).format(
                                    "DD MMMM YYYY HH:mm"
                                )
                            }}
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="w-1/2 pl-4">
                    <div class="mb-8">
                        <h3 class="font-bold mb-4">Additional Questions</h3>
                        <div v-if="jobs?.questions?.length" class="space-y-2">
                            <div
                                v-for="(question, index) in jobs.questions"
                                :key="question.id"
                                class="p-2 bg-gray-50 rounded"
                            >
                                <span class="font-medium"
                                    >Question {{ index + 1 }}:</span
                                >
                                {{ question.question_text }}
                            </div>
                        </div>
                        <div v-else class="p-2 bg-gray-50 rounded">
                            No additional questions
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="font-bold mb-4">Salary Ranges</h3>
                        <div
                            v-if="jobs?.salary_ranges?.length"
                            class="space-y-2"
                        >
                            <div
                                v-for="range in jobs.salary_ranges"
                                :key="range.id"
                                class="p-2 bg-gray-50 rounded flex items-center gap-4"
                            >
                                <div class="flex-1">
                                    <span class="text-gray-500">Rp</span>
                                    {{ formatSalary(range.min_salary) }}
                                </div>
                                <span class="text-gray-500">-</span>
                                <div class="flex-1">
                                    <span class="text-gray-500">Rp</span>
                                    {{ formatSalary(range.max_salary) }}
                                </div>
                            </div>
                        </div>
                        <div v-else class="p-2 bg-gray-50 rounded">
                            No salary ranges specified
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold">Applicants</h3>
                            <span
                                class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-sm"
                            >
                                Total: {{ jobs?.applicants?.length || 0 }}
                            </span>
                        </div>
                        <div
                            v-if="jobs?.applicants?.length"
                            class="space-y-2 max-h-[300px] overflow-y-auto"
                        >
                            <div
                                v-for="applicant in jobs.applicants"
                                :key="applicant.id"
                                class="p-2 bg-gray-50 rounded flex justify-between items-center"
                            >
                                <div>
                                    <div class="font-medium">
                                        {{ applicant.name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ applicant.email }}
                                    </div>
                                </div>
                                <div class="text-sm">
                                    <span
                                        :class="`px-2 py-1 rounded-full ${
                                            applicant.status === 0
                                                ? 'bg-blue-100 text-blue-800'
                                                : applicant.status === 1
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : applicant.status === 2
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800'
                                        }`"
                                    >
                                        {{
                                            getApplicantStatusLabel(
                                                applicant.status
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div v-else class="p-2 bg-gray-50 rounded">
                            No applicants yet
                        </div>
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
