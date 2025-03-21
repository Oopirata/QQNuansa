# CandidateDetail.vue
<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import DetailHeader from "@/Components/Candidates/DetailHeader.vue";
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

const emailForm = ref({
    applicant_id: props.candidates.id,
    subject: "",
    messages: "",
    status: String(props.candidates.status),
    interviewSchedule: null,
    email: props.candidates.email, // pastikan ini ada
});

const showPreview = ref(false);
const previewContent = ref("");

const previewEmail = async () => {
    try {
        console.log("Sending data:", emailForm.value); // untuk debugging
        const response = await axios.post(
            route("preview.email"),
            emailForm.value
        );
        previewContent.value = response.data;
        showPreview.value = true;
    } catch (error) {
        console.log("Error detail:", error.response?.data);
        alert("Failed to generate preview: " + error.message);
    }
};

const sendEmail = async () => {
    try {
        const response = await axios.post(route("send.email"), emailForm.value);
        if (response.data.success) {
            alert("Email sent successfully!");
            emailForm.value = {
                candidate_id: props.candidates.id,
                subject: "",
                messages: "",
                status: props.candidates.status,
                interviewSchedule: null,
            };
        }
    } catch (error) {
        alert("Failed to send email: " + error.message);
    }
};

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
const disqualifyCandidate = () => {
    // Implementation for disqualifying candidate
    console.log("Disqualify candidate");
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
                :showDisqualify="true"
                :showMoveToScreened="true"
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
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Send Email</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="subject" class="block text-gray-600"
                                >Subject</label
                            >
                            <input
                                v-model="emailForm.subject"
                                id="subject"
                                type="text"
                                placeholder="Email Subject"
                                class="w-full border rounded p-2"
                            />
                        </div>
                        <div>
                            <label for="messages" class="block text-gray-600"
                                >Message</label
                            >
                            <textarea
                                v-model="emailForm.messages"
                                id="messages"
                                placeholder="Type your message..."
                                rows="4"
                                class="w-full border rounded p-2"
                            ></textarea>
                        </div>
                        <div>
                            <label
                                for="interviewSchedule"
                                class="block text-gray-600"
                                >Interview Schedule (optional)</label
                            >
                            <input
                                v-model="emailForm.interviewSchedule"
                                id="interviewSchedule"
                                type="datetime-local"
                                class="w-full border rounded p-2"
                            />
                        </div>
                        <div class="flex space-x-4">
                            <button
                                @click="previewEmail"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
                            >
                                Preview
                            </button>
                            <button
                                @click="sendEmail"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                            >
                                Send Email
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Preview Modal -->
                <div
                    v-if="showPreview"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                >
                    <div
                        class="bg-white rounded-lg w-3/4 max-h-[80vh] shadow-xl overflow-hidden"
                    >
                        <!-- Modal Header -->
                        <div
                            class="bg-gray-50 px-6 py-4 border-b border-gray-200"
                        >
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-100 rounded-lg p-2">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-blue-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3
                                            class="text-lg font-semibold text-gray-800"
                                        >
                                            Email Preview
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Preview your email before sending
                                        </p>
                                    </div>
                                </div>
                                <button
                                    @click="showPreview = false"
                                    class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-gray-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Content -->
                        <div class="p-6 space-y-4">
                            <!-- Email Details -->
                            <div
                                class="bg-white rounded-lg border border-gray-200"
                            >
                                <div class="p-4 border-b border-gray-200">
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <span
                                                class="text-sm font-medium text-gray-500 w-20"
                                                >To:</span
                                            >
                                            <span
                                                class="text-sm text-gray-800"
                                                >{{ emailForm.email }}</span
                                            >
                                        </div>
                                        <div class="flex items-center">
                                            <span
                                                class="text-sm font-medium text-gray-500 w-20"
                                                >Subject:</span
                                            >
                                            <span
                                                class="text-sm text-gray-800"
                                                >{{ emailForm.subject }}</span
                                            >
                                        </div>
                                        <div
                                            v-if="emailForm.interviewSchedule"
                                            class="flex items-center"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-500 w-20"
                                                >Schedule:</span
                                            >
                                            <span
                                                class="text-sm text-gray-800"
                                                >{{
                                                    new Date(
                                                        emailForm.interviewSchedule
                                                    ).toLocaleString()
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Email Content -->
                                <div class="p-4">
                                    <div
                                        class="prose max-w-none"
                                        v-html="previewContent"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div
                            class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3"
                        >
                            <button
                                @click="showPreview = false"
                                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                Close Preview
                            </button>
                            <button
                                @click="sendEmail"
                                class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                Send Email
                            </button>
                        </div>
                    </div>
                </div>
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