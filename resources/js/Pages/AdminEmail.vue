# EmailDashboard.vue
<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Head, Link } from "@inertiajs/vue3";

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
        <!-- Sidebar Section -->
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
                                    class="text-gray-600 hover:text-gray-900"
                                >
                                    {{ item.name }}
                                </Link>
                            </li>
                        </ul>
                    </li>
                    <li class="mb-4">
                        <Link
                            href="/email"
                            class="flex items-center text-purple-600 hover:text-gray-900"
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
