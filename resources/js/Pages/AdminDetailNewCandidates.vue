# CandidateDetail.vue
<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";

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
    document.addEventListener('click', closeDropdown);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', closeDropdown);
});

// import axios from "axios";

// const emailForm = ref({
//     email: candidate.value.email,
//     subject: "",
//     message: "",
// });

// const sendEmail = async () => {
//     try {
//         const response = await axios.post("/api/send-candidate-email", emailForm.value);
//         alert(response.data.message); // Tampilkan pesan sukses
//     } catch (error) {
//         console.error("Error sending email:", error);
//         alert("Failed to send email.");
//     }
// };

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

const props = defineProps({
    candidates: {
        type: Object,
        required: true,
    },
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
    <div class="flex h-screen">
        <!-- Sidebar - Reused from previous component -->
        <div class="bg-blue-100 w-64 p-4 flex flex-col">
            <div class="flex items-center mb-8">
                <img
                    src="/images/QQ crop.png"
                    alt="Company Logo"
                    class="w-20 h-16 mr-2"
                />
                <img
                    src="/images/Nuansa crop.png"
                    alt="Company Logo"
                    class="w-24 h-8 mr-2 ml-3"
                />
            </div>

            <nav class="flex-1">
                <ul>
                    <li class="mb-4">
                        <Link
                            href="/adminDashboard"
                            class="flex items-center text-gray-600 hover:text-gray-900"
                        >
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </Link>
                    </li>
                    <li class="mb-4">
                        <button
                            @click="toggleSubMenu"
                            class="flex items-center w-full text-gray-600 hover:text-gray-900"
                        >
                            <i class="fas fa-users mr-2"></i>
                            Candidates
                            <i
                                :class="[
                                    'fas ml-auto',
                                    isSubMenuOpen
                                        ? 'fa-chevron-down'
                                        : 'fa-chevron-right',
                                ]"
                            ></i>
                        </button>
                        <ul v-if="isSubMenuOpen" class="ml-6 mt-2">
                            <li
                                v-for="item in candidateItems"
                                :key="item.name"
                                class="mb-2"
                            >
                                <Link
                                    :href="item.path"
                                    :class="{
                                        'text-purple-600': item.name === 'New', // Menjadi ungu jika item bernama New
                                        'text-gray-600 hover:text-gray-900':
                                            item.name !== 'New', // Tetap abu-abu untuk yang lain
                                    }"
                                >
                                    {{ item.name }}
                                </Link>
                            </li>
                        </ul>
                    </li>
                    <li class="mb-4">
                        <Link
                            href="/adminEmail"
                            class="flex items-center text-gray-600 hover:text-gray-900"
                        >
                            <i class="fas fa-envelope mr-2"></i>
                            E-mail
                        </Link>
                    </li>
                </ul>
            </nav>
            <template v-if="$page.props.auth.user">
                <div class="mt-auto relative" ref="dropdownRef">
                    <button
                        @click="toggleDropdown"
                        type="button"
                        class="flex items-center w-full p-2 hover:bg-gray-100 rounded-md">
                            <img
                                src="/images/profile.png"
                                alt="User Avatar"
                                class="w-10 h-10 rounded-full mr-2"
                            />
                            <div>
                                <p class="text-gray-600">
                                    {{ $page.props.auth.user.name }}
                                </p>
                            </div>
                    </button>
                    
                    <Transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 translate-y-2"
                        enter-to-class="transform opacity-100 translate-y-0"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 translate-y-0"
                        leave-to-class="transform opacity-0 translate-y-2"
                    >
                        <div
                            v-show="dropdownOpen"
                            class="absolute left-0 bottom-full mb-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50"
                        >
                            <Link
                                :href="route('profile.edit')"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                Profile
                            </Link>
                            <Link
                                v-if="$page.props.auth.user.roles.some((role) => role.role_name === 'admin')"
                                :href="route('landing.page')"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                Website
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                Log Out
                            </Link>
                        </div>
                    </Transition>
                </div>
            </template>
        </div>

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
                            {{ candidates.job_vacancy }}
                        </h1>
                        <p class="text-gray-600">New</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="mr-4"
                        >{{ currentPage }} of {{ totalPages }}</span
                    >
                    <button class="mr-2">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button><i class="fas fa-chevron-right"></i></button>
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

                <!-- Emails Section -->
                <!-- <div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-semibold mb-4">Send Email</h3>
    <div class="space-y-4">
        <div>
            <label for="subject" class="block text-gray-600">Subject</label>
            <input
                v-model="emailForm.subject"
                id="subject"
                type="text"
                placeholder="Email Subject"
                class="w-full border rounded p-2"
            />
        </div>
        <div>
            <label for="message" class="block text-gray-600">Message</label>
            <textarea
                v-model="emailForm.message"
                id="message"
                placeholder="Type your message..."
                rows="4"
                class="w-full border rounded p-2"
            ></textarea>
        </div>
        <button
            @click="sendEmail"
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
            Send Email
        </button>
    </div>
</div> -->

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

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 -mt-10">
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
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
</style>
