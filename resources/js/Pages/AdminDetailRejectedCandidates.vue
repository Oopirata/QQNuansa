# CandidateDetail.vue
<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import DetailHeader from "@/Components/Candidates/DetailHeader.vue";
import EmailForm from '@/Components/Candidates/EmailForm.vue';
import axios from "axios";

const dropdownOpen = ref(false);
const dropdownRef = ref(null);

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

const props = defineProps({
    candidates: {
        type: Object,
        required: true,
    },
});

const showPreview = ref(false);
const previewContent = ref("");

// Candidate data
const candidate = ref({
    id: 1,
    name: "Cain Chana",
    location: "Semarang",
    jobTitle: "Psychologist Assistant",
    email: "john.doe@gmail.com",
    phone: "+ 380 66 779 71 00",
    portfolio: "dribbble.com/johndoe",
    status: "New",
});

console.log(usePage().props.candidates);

const isSubMenuOpen = ref(true);

const candidateItems = [
    { name: "New", path: "/adminNewCandidates" },
    { name: "Screened", path: "/adminScreenedCandidates" },
    { name: "Interview", path: "/adminInterviewCandidates" },
    { name: "Rejected", path: "/adminRejectedCandidates" },
];
const toggleSubMenu = () => {
    isSubMenuOpen.value = !isSubMenuOpen.value;
};
// Screening questions and answers
const screeningQuestions = ref([
    {
        question: "What is your expected monthly compensation?",
        answer: "32.000 hrn per month",
    },
    {
        question: "What is your current proficiency in German?",
        answer: "A1",
    },
    {
        question: "What's an interface?",
        answer: "To kick off the employee onboarding checklist, you need to prepare the relevant paperwork and information prior to the employee's first day. Start by recording the employee's basic information in the form fields below.",
    },
]);

// Email data
const emails = ref([
    {
        sender: "Kathryn Murphy",
        subject: "Interview Results",
        time: "1 day ago",
        content:
            "Dear Cody,\n\nI am delighted to inform you that we would like to arrange an interview. Please let us know when you are available to come to our offices.\n\nSincerely,\nKathryn Murphy",
    },
    {
        sender: "Kathryn Murphy",
        subject: "Interview Results",
        time: "1 day ago",
        content:
            "Dear Cody,\n\nPlease let us know when you are available to come to our offices.",
    },
]);

const currentPage = ref(1);
const totalPages = ref(6);

// Action methods
const disqualifyCandidate = (user_id) => {
    // Implementation for disqualifying candidate
    router.post(route("disqualify", props.candidates.user_id))
};

const moveToScreened = (user_id) => {
    router.visit(route("adminDetailNewCandidates", id));
};
</script>

<template>
    <Head title="Candidate Detail" />
    <div class="flex">
        <Sidebar :user="$page.props.auth.user" />

        <!-- Main Content -->
        <div class="w-full p-6 ml-64">
            <DetailHeader
                :jobVacancy="candidates.job_vacancy"
                status="Rejected"
                :currentPage="currentPage"
                :totalPages="totalPages"
                :showDisqualify="false"
                :showMoveToScreened="false"
                :showMoveToInterview="false"
                :showMoveToHired="false"
                @disqualify="disqualifyCandidate"
                @moveToScreened="moveToScreened"
                @prevPage="handlePrevPage"
                @nextPage="handleNextPage"
            />

            <!-- Candidate Profile -->
            <div class="flex mb-6">
                <div class="w-1/2 pr-4">
                    <!-- Profile Info -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-16 h-16 bg-blue-200 rounded-full mr-4"
                            ></div>
                            <div>
                                <h2 class="text-xl font-semibold">
                                    {{ candidates.user }}
                                </h2>
                                <p class="text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ candidate.location }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i
                                    class="fas fa-envelope mr-2 text-gray-400"
                                ></i>
                                <span>{{ candidates.email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>
                                <span>{{ candidate.phone }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-link mr-2 text-gray-400"></i>
                                <a href="#" class="text-blue-500">{{
                                    candidate.portfolio
                                }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Screening Questions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">
                            Screening questions
                        </h3>
                        <div class="space-y-4">
                            <div
                                v-for="(qa, index) in screeningQuestions"
                                :key="index"
                            >
                                <p class="text-gray-600 mb-1">
                                    {{ qa.question }}
                                </p>
                                <p>{{ qa.answer }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Form Section -->
                <EmailForm 
                    :candidates="candidates"
                />
                <div class="w-1/2 pl-4">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Emails</h3>
                        <div class="mb-4">
                            <input
                                type="text"
                                placeholder="Click to start composing a new email..."
                                class="w-full border rounded p-2 text-gray-400"
                            />
                        </div>
                        <div class="space-y-4">
                            <div
                                v-for="(email, index) in emails"
                                :key="index"
                                class="border-b pb-4"
                            >
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-gray-200 rounded-full mr-2 flex items-center justify-center"
                                        >
                                            CF
                                        </div>
                                        <div>
                                            <p class="font-semibold">
                                                {{ email.sender }}
                                            </p>
                                            <p class="text-gray-600">
                                                {{ email.subject }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="text-gray-400">{{
                                        email.time
                                    }}</span>
                                </div>
                                <p class="text-gray-600">{{ email.content }}</p>
                            </div>
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