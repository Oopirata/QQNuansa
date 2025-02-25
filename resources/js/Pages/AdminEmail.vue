<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

const page = usePage();
const searchQuery = ref("");
const selectedEmails = ref(new Set());
const currentTab = ref("inbox");
const selectedEmail = ref(null);
const showPreview = ref(false);

const props = defineProps({
    emails: {
        type: Object,
        required: true,
    },
});

// Sample email data
const emails = {
    sent: [
        {
            id: 1,
            avatar: "/api/placeholder/40/40",
            name: "Beru",
            subject: "Interview Results",
            time: "1 day ago",
            content: {
                subject: "Interview Results - Next Steps",
                greeting: "Dear Cody,",
                body: "I am delighted to inform you that we would like to arrange an interview. Please let us know when you are available to come to our offices.",
                signature: "Sincerely,\nKathryn Murphy",
            },
        },
        {
            id: 2,
            avatar: "/api/placeholder/40/40",
            name: "Igris",
            subject: "Follow-up Meeting",
            time: "1 day ago",
            content: {
                subject: "Follow-up Meeting - Schedule Confirmation",
                greeting: "Dear Cody,",
                body: "Please let us know when you are available to come to our offices.",
                signature: "Best regards,\nKathryn Murphy",
            },
        },
    ],
    inbox: [
        {
            id: 3,
            avatar: "/api/placeholder/40/40",
            name: "Bellion",
            subject: "Project Update",
            time: "1 day ago",
            content: {
                subject: "Project Update - Q1 Progress",
                greeting: "Dear Cody,",
                body: "I am delighted to inform you that we would like to arrange an interview. Please let us know when you are available to come to our offices.",
                signature: "Sincerely,\nKathryn Murphy",
            },
        },
        {
            id: 4,
            avatar: "/api/placeholder/40/40",
            name: "Iron",
            subject: "Meeting Minutes",
            time: "1 day ago",
            content: {
                subject: "Meeting Minutes - Team Sync",
                greeting: "Dear Cody,",
                body: "Please let us know when you are available to come to our offices.",
                signature: "Regards,\nKathryn Murphy",
            },
        },
    ],
};

// Filter emails based on search query
const filteredEmails = computed(() => {
    const currentEmails = emails[currentTab.value];
    if (!searchQuery.value) return currentEmails;

    return currentEmails.filter(
        (email) =>
            email.name
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            email.subject
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            email.content.body
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase())
    );
});

const toggleEmailSelection = (emailId, event) => {
    // Prevent opening preview when clicking checkbox
    event.stopPropagation();
    if (selectedEmails.value.has(emailId)) {
        selectedEmails.value.delete(emailId);
    } else {
        selectedEmails.value.add(emailId);
    }
};

const selectAllEmails = () => {
    if (selectedEmails.value.size === filteredEmails.value.length) {
        selectedEmails.value.clear();
    } else {
        selectedEmails.value = new Set(
            filteredEmails.value.map((email) => email.id)
        );
    }
};

const openEmailPreview = (email) => {
    selectedEmail.value = email;
    showPreview.value = true;
};

const closeEmailPreview = () => {
    selectedEmail.value = null;
    showPreview.value = false;
};

// Close modal when clicking outside
const handleClickOutside = (event) => {
    const modal = document.querySelector(".email-preview-modal");
    if (modal && !modal.contains(event.target)) {
        closeEmailPreview();
    }
};

onMounted(() => {
    document.addEventListener("mousedown", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("mousedown", handleClickOutside);
});
</script>

<template>
    <Head title="Email Dashboard" />
    <div class="flex h-screen ml-64">
        <Sidebar :user="page.props.auth.user" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col bg-gray-50">
            <!-- Header -->
            <div class="bg-white border-b px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold">Email</h2>
                    <div class="flex items-center w-1/2">
                        <div class="relative flex-1">
                            <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line
                                        x1="21"
                                        y1="21"
                                        x2="16.65"
                                        y2="16.65"
                                    ></line>
                                </svg>
                            </span>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search emails"
                                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex border-b bg-white px-6">
                <button
                    @click="currentTab = 'inbox'"
                    :class="[
                        'px-4 py-2 font-medium',
                        currentTab === 'inbox'
                            ? 'text-blue-600 border-b-2 border-blue-600'
                            : 'text-gray-600',
                    ]"
                >
                    Inbox
                </button>
                <button
                    @click="currentTab = 'sent'"
                    :class="[
                        'px-4 py-2 font-medium',
                        currentTab === 'sent'
                            ? 'text-blue-600 border-b-2 border-blue-600'
                            : 'text-gray-600',
                    ]"
                >
                    Sent
                </button>
            </div>

            <!-- Email Table -->
            <div class="flex-1 overflow-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="border-b">
                            <th class="w-12 px-6 py-3">
                                <input
                                    type="checkbox"
                                    :checked="
                                        selectedEmails.size ===
                                        filteredEmails.length
                                    "
                                    @change="selectAllEmails"
                                    class="rounded border-gray-300"
                                />
                            </th>
                            <th
                                class="text-left px-6 py-3 text-sm font-medium text-gray-500"
                            >
                                From
                            </th>
                            <th
                                class="text-left px-6 py-3 text-sm font-medium text-gray-500"
                            >
                                Subject
                            </th>
                            <th
                                class="text-right px-6 py-3 text-sm font-medium text-gray-500"
                            >
                                Time
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="email in filteredEmails"
                            :key="email.id"
                            class="hover:bg-gray-50 border-b cursor-pointer"
                            @click="openEmailPreview(email)"
                        >
                            <td class="px-6 py-4">
                                <input
                                    type="checkbox"
                                    :checked="selectedEmails.has(email.id)"
                                    @click="
                                        toggleEmailSelection(email.id, $event)
                                    "
                                    class="rounded border-gray-300"
                                />
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="font-medium">{{
                                        email.name
                                    }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{
                                        email.subject
                                    }}</span>
                                    <span
                                        class="text-sm text-gray-500 truncate"
                                        >{{ email.content.body }}</span
                                    >
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 text-right text-sm text-gray-500"
                            >
                                {{ email.time }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Email Preview Modal -->
            <div
                v-if="showPreview && selectedEmail"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            >
                <div
                    class="email-preview-modal bg-white rounded-lg w-3/4 max-w-4xl max-h-[80vh] overflow-hidden flex flex-col"
                >
                    <!-- Modal Header with Subject -->
                    <div
                        class="px-6 py-4 border-b flex justify-between items-center bg-gray-50"
                    >
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold">
                                {{ selectedEmail.content.subject }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ selectedEmail.subject }}
                            </p>
                        </div>
                        <button
                            @click="closeEmailPreview"
                            class="text-gray-500 hover:text-gray-700 ml-4"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
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

                    <!-- Modal Content -->
                    <div class="p-6 overflow-y-auto">
                        <!-- Sender Information -->
                        <div class="flex items-center mb-6 border-b pb-4">
                            <img
                                :src="selectedEmail.avatar"
                                alt=""
                                class="w-10 h-10 rounded-full mr-4"
                            />
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-semibold">
                                        {{ selectedEmail.name }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ selectedEmail.time }}
                                    </p>
                                </div>
                                <p class="text-sm text-gray-600">
                                    To: Cody Fisher
                                </p>
                            </div>
                        </div>

                        <!-- Email Content -->
                        <div class="space-y-4">
                            <p>{{ selectedEmail.content.greeting }}</p>
                            <p class="whitespace-pre-line">
                                {{ selectedEmail.content.body }}
                            </p>
                            <p
                                v-if="selectedEmail.content.signature"
                                class="whitespace-pre-line text-gray-700 mt-6"
                            >
                                {{ selectedEmail.content.signature }}
                            </p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t flex justify-end bg-gray-50">
                        <button
                            @click="closeEmailPreview"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Close
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
