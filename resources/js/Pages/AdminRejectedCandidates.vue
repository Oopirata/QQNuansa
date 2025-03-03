# CandidatesDashboard.vue
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
    document.addEventListener("click", closeDropdown);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", closeDropdown);
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
    <div class="flex h-screen ml-64">
        <Sidebar :user="$page.props.auth.user" />

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">
                    Rejected Candidates
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
