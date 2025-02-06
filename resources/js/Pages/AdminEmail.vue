# EmailDashboard.vue
<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

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

// Sample email data - in a real app this would likely come from props or store
const emails = {
    sent: [
        {
            id: 1,
            avatar: "/api/placeholder/40/40",
            name: "Kathryn Murphy",
            subject: "Interview Results",
            time: "1 day ago",
            content: {
                greeting: "Dear Cody,",
                body: "I am delighted to inform you that we would like to arrange an interview. Please let us know when you are available to come to our offices.",
                signature: "Sincerely,\nKathryn Murphy",
            },
        },
        {
            id: 2,
            avatar: "/api/placeholder/40/40",
            name: "Kathryn Murphy",
            subject: "Interview Results",
            time: "1 day ago",
            content: {
                greeting: "Dear Cody,",
                body: "Please let us know when you are available to come to our offices.",
            },
        },
    ],
    inbox: [
        {
            id: 3,
            avatar: "/api/placeholder/40/40",
            name: "Kathryn Murphy",
            subject: "Interview Results",
            time: "1 day ago",
            content: {
                greeting: "Dear Cody,",
                body: "I am delighted to inform you that we would like to arrange an interview. Please let us know when you are available to come to our offices.",
                signature: "Sincerely,\nKathryn Murphy",
            },
        },
        {
            id: 4,
            avatar: "/api/placeholder/40/40",
            name: "Kathryn Murphy",
            subject: "Interview Results",
            time: "1 day ago",
            content: {
                greeting: "Dear Cody,",
                body: "Please let us know when you are available to come to our offices.",
            },
        },
    ],
};

// Tambahkan state untuk dropdown
const isSubMenuOpen = ref(false);

// Tambahkan fungsi toggle
const toggleSubMenu = () => {
    isSubMenuOpen.value = !isSubMenuOpen.value;
};
const candidateItems = [
    { name: "New", path: "/adminNewCandidates" },
    { name: "Screened", path: "/adminScreenedCandidates" },
    { name: "Interview", path: "/adminInterviewCandidates" },
    { name: "Rejected", path: "/adminRejectedCandidates" },
];
</script>

<template>
    <Head title="Email Dashboard" />
    <div class="flex h-screen">
        <Sidebar :user="page.props.auth.user" />

        <!-- Main Content -->
        <div class="w-3/4 p-8">
            <div class="flex items-center mb-8">
                <button class="text-2xl">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h2 class="text-2xl font-semibold ml-4">Email</h2>
            </div>
            <div class="flex">
                <!-- Sent Section -->
                <div class="w-1/2 pr-4">
                    <h3 class="text-xl font-semibold mb-4">Sent</h3>
                    <div
                        v-for="email in emails.sent"
                        :key="email.id"
                        class="bg-white p-4 rounded shadow mb-4 last:mb-0"
                    >
                        <div class="flex items-center mb-2">
                            <img
                                :src="email.avatar"
                                alt="User Avatar"
                                class="mr-2 rounded-full"
                            />
                            <div>
                                <p class="font-semibold">{{ email.name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ email.subject }}
                                </p>
                            </div>
                            <span class="ml-auto text-sm text-gray-500">{{
                                email.time
                            }}</span>
                        </div>
                        <p class="mb-2">{{ email.content.greeting }}</p>
                        <p class="mb-2">{{ email.content.body }}</p>
                        <template v-if="email.content.signature">
                            <p>{{ email.content.signature }}</p>
                        </template>
                    </div>
                </div>

                <!-- Inbox Section -->
                <div class="w-1/2 pl-4">
                    <h3 class="text-xl font-semibold mb-4">Inbox</h3>
                    <div
                        v-for="email in emails.inbox"
                        :key="email.id"
                        class="bg-white p-4 rounded shadow mb-4 last:mb-0"
                    >
                        <div class="flex items-center mb-2">
                            <img
                                :src="email.avatar"
                                alt="User Avatar"
                                class="mr-2 rounded-full"
                            />
                            <div>
                                <p class="font-semibold">{{ email.name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ email.subject }}
                                </p>
                            </div>
                            <span class="ml-auto text-sm text-gray-500">{{
                                email.time
                            }}</span>
                        </div>
                        <p class="mb-2">{{ email.content.greeting }}</p>
                        <p class="mb-2">{{ email.content.body }}</p>
                        <template v-if="email.content.signature">
                            <p>{{ email.content.signature }}</p>
                        </template>
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
