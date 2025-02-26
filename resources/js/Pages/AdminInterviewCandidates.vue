<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import axios from "axios";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

// Props definition dengan default value
const props = defineProps({
    candidates: {
        type: Object,
        required: true,
        default: () => ({
            id: null,
            job_vacancy: "",
            user: "",
            email: "",
            status: "",
            location: "",
        }),
    },
});

// Watch untuk debug props
watch(
    () => props.candidates,
    (newVal) => {
        console.log("Candidates updated:", newVal);
    },
    { immediate: true }
);

const emailForm = ref({
    applicant_id: props.candidates?.id,
    subject: "",
    messages: "",
    status: String(props.candidates?.status || ""),
    interviewSchedule: null,
    email: props.candidates?.email,
});

const showPreview = ref(false);
const previewContent = ref("");

const previewEmail = async () => {
    try {
        console.log("Sending data:", emailForm.value);
        const response = await axios.post(
            route("preview.email"),
            emailForm.value
        );
        previewContent.value = response.data;
        showPreview.value = true;
    } catch (error) {
        console.error("Error detail:", error.response?.data);
        alert("Failed to generate preview: " + error.message);
    }
};

const sendEmail = async () => {
    try {
        const response = await axios.post(route("send.email"), emailForm.value);
        if (response.data.success) {
            alert("Email sent successfully!");
            emailForm.value = {
                applicant_id: props.candidates?.id,
                subject: "",
                messages: "",
                status: props.candidates?.status || "",
                interviewSchedule: null,
            };
        }
    } catch (error) {
        alert("Failed to send email: " + error.message);
    }
};

const dropdownOpen = ref(false);
const dropdownRef = ref(null);

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        dropdownOpen.value = false;
    }
};

onMounted(() => {
    try {
        console.log("Component mounted");
        console.log("Props:", props);
        console.log("Page:", usePage()?.props);
        document.addEventListener("click", closeDropdown);
    } catch (error) {
        console.error("Mounting error:", error);
    }
});

onBeforeUnmount(() => {
    document.removeEventListener("click", closeDropdown);
});

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

const disqualifyCandidate = () => {
    console.log("Disqualify candidate");
};

const moveToScreened = (user_id) => {
    router.visit(route("adminDetailNewCandidates", user_id));
};
</script>

<template>
    <Head title="Candidate Detail" />
    <div class="flex h-screen ml-64">
        <Sidebar :user="$page?.props?.auth?.user || {}" />

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <button class="mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-semibold">
                            {{
                                props.candidates?.job_vacancy || "No Job Title"
                            }}
                        </h1>
                        <p class="text-gray-600">
                            {{ props.candidates?.status || "No Status" }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="mr-4"
                        >{{ currentPage }} of {{ totalPages }}</span
                    >
                    <button class="mr-2">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

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
                                    {{ props.candidates?.user || "No Name" }}
                                </h2>
                                <p class="text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{
                                        props.candidates?.location ||
                                        "No Location"
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i
                                    class="fas fa-envelope mr-2 text-gray-400"
                                ></i>
                                <span>{{
                                    props.candidates?.email || "No Email"
                                }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>
                                <span>{{
                                    props.candidates?.phone || "No Phone"
                                }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-link mr-2 text-gray-400"></i>
                                <a href="#" class="text-blue-500">{{
                                    props.candidates?.portfolio ||
                                    "No Portfolio"
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
                <div class="w-1/2 pl-4">
                    <div class="bg-white rounded-lg shadow p-6">
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
                                <label
                                    for="messages"
                                    class="block text-gray-600"
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
                                >
                                    Interview Schedule (optional)
                                </label>
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

                    <!-- Email History -->
                    <div class="bg-white rounded-lg shadow p-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">
                            Email History
                        </h3>
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

            <!-- Preview Modal -->
            <div
                v-if="showPreview"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
            >
                <div
                    class="bg-white p-6 rounded-lg w-3/4 max-h-[80vh] overflow-y-auto"
                >
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-semibold">Email Preview</h3>
                        <button
                            @click="showPreview = false"
                            class="text-gray-500"
                        >
                            &times;
                        </button>
                    </div>
                    <div v-html="previewContent"></div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="container pb-2">
                <div class="flex justify-end space-x-4 mb-5">
                    <button
                        @click="disqualifyCandidate"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                    >
                        Disqualify
                    </button>
                    <button
                        @click="moveToScreened"
                        class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600"
                    >
                        Move to screened
                    </button>
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
