# CandidatesDashboard.vue
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

// Search functionality
const searchQuery = ref("");

// Filters data
const filters = {
    jobTitles: ref([
        { id: 1, name: "Psychologist Assistant", color: "green" },
        { id: 2, name: "HRD", color: "blue" },
    ]),
    degrees: ref([
        { id: 1, name: "Bachelor", color: "pink" },
        { id: 2, name: "Master", color: "purple" },
    ]),
};

// Candidates data
const candidates = ref([
    {
        id: 1,
        name: "Muhammad Mirza Faiz Rabbani",
        location: "Semarang",
        jobTitle: "HRD",
    },
    {
        id: 2,
        name: "Bintang Syafrian Rizal",
        location: "Pekalongan",
        jobTitle: "Psychologist Assistant",
    },
    { id: 3, name: "Hanif Herofa", location: "Jakarta", jobTitle: "HRD" },
    { id: 4, name: "Raka Maulana Yusuf", location: "Rembang", jobTitle: "HRD" },
    {
        id: 5,
        name: "Awang Pratama Putra Mulya",
        location: "Pekalongan",
        jobTitle: "HRD",
    },
    { id: 6, name: "Dul Samsi", location: "Pekalongan", jobTitle: "HRD" },
]);

// Navigation state
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
</script>

<template>
    <Head title="Candidates Dashboard" />
    <div class="flex h-screen">
        <!-- Sidebar -->
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
                                        'text-purple-600':
                                            item.name === 'Screened', // Menjadi ungu jika item bernama New
                                        'text-gray-600 hover:text-gray-900':
                                            item.name !== 'Screened', // Tetap abu-abu untuk yang lain
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
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">
                    Screened Candidates
                    <span class="text-gray-500">{{ candidates.length }}</span>
                </h2>
                <div class="relative">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search"
                        class="border rounded p-2 pl-8"
                    />
                    <i
                        class="fas fa-search absolute left-2 top-3 text-gray-500"
                    ></i>
                </div>
            </div>

            <div class="flex">
                <!-- Filters -->
                <div class="w-1/4 pr-4 bg-white rounded-lg shadow p-4 mr-20">
                    <div class="">
                        <h3 class="text-xl font-semibold mb-4">Filters</h3>
                    </div>

                    <!-- Job Title Filters -->

                    <div class="mb-6">
                        <div
                            class="w-3/4 bg-white rounded-lg shadow p-4 -mr-20"
                        >
                            <h4 class="font-semibold mb-2">Job Title</h4>
                            <div class="flex items-center mb-2">
                                <span
                                    v-for="job in filters.jobTitles"
                                    :key="job.id"
                                    :class="`bg-${job.color}-200 text-${job.color}-800 px-2 py-1 rounded-full text-sm mr-2`"
                                >
                                    {{ job.name }}
                                </span>
                            </div>
                            <button class="text-gray-500">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Degree Filters -->
                    <div
                        class="mb-6 w-3/4 bg-white rounded-lg shadow p-4 -mr-20"
                    >
                        <h4 class="font-semibold mb-2">Degree</h4>
                        <div class="flex items-center mb-2">
                            <span
                                v-for="degree in filters.degrees"
                                :key="degree.id"
                                :class="`bg-${degree.color}-200 text-${degree.color}-800 px-2 py-1 rounded-full text-sm mr-2`"
                            >
                                {{ degree.name }}
                            </span>
                        </div>
                        <button class="text-gray-500">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Candidates List -->
                <div class="w-3/4 bg-white rounded-lg shadow p-4">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2">Name</th>
                                <th class="py-2">Location</th>
                                <th class="py-2">Job Title</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="candidate in candidates"
                                :key="candidate.id"
                                class="border-b"
                            >
                                <td class="py-2">{{ candidate.name }}</td>
                                <td class="py-2">{{ candidate.location }}</td>
                                <td
                                    class="py-2"
                                    :class="{
                                        'font-semibold':
                                            candidate.jobTitle ===
                                            'Psychologist Assistant',
                                    }"
                                >
                                    {{ candidate.jobTitle }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
