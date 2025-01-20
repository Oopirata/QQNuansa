# CandidatesDashboard.vue
<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net';
 
DataTable.use(DataTablesCore);

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

// Candidates data structure yang lebih representatif
const candidates = ref([
    {
        id: 1,
        name: "John Smith",
        email: "john.smith@email.com",
        jobTitle: "Psychologist Assistant",
        degree: "Bachelor",
        status: "New",
        appliedDate: "2025-01-15"
    },
    {
        id: 2,
        name: "Sarah Johnson",
        email: "sarah.j@email.com",
        jobTitle: "HRD",
        degree: "Master",
        status: "New",
        appliedDate: "2025-01-16"
    }
]);

// Data untuk DataTable
const tableData = computed(() => {
    return candidates.value.map(candidate => [
        candidate.name,
        candidate.email,
        candidate.jobTitle,
        candidate.degree,
        candidate.appliedDate,
        // Tambahkan kolom action jika diperlukan
        <button class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
    ]);
});

// DataTable options
const tableOptions = {
    responsive: true,
    searching: true,
    ordering: true,
    pageLength: 10,
    columns: [
        { title: "Name" },
        { title: "Email" },
        { title: "Job Title" },
        { title: "Degree" },
        { title: "Applied Date" },
        { 
            title: "Actions",
            orderable: false,
            searchable: false
        }
    ],
    language: {
        search: "Search in table:",
        lengthMenu: "Show _MENU_ entries per page",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        paginate: {
            first: "First",
            last: "Last",
            next: "Next",
            previous: "Previous"
        }
    }
};

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
            <div class="mt-auto flex items-center">
                <img
                    src="/images/profile.png"
                    alt="User Avatar"
                    class="w-10 h-10 rounded-full mr-2"
                />
                <div>
                    <p class="text-gray-600">John Doe</p>
                </div>
            </div>
        </div>
                
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">
                    New Candidates
                    <span class="text-gray-500">{{ candidates.length }}</span>
                </h2>
                <div class="relative">
                    <input
                        v-model="searchQuery"
                        autocomplete="off"
                        type="text"
                        placeholder="Search"
                        class="border rounded p-2 pl-8"
                        id="search"
                    />
                    <i class="fas fa-search absolute left-2 top-3 text-gray-500"></i>
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
                <!-- Candidates List with improved DataTable -->
                <div class="w-3/4 bg-white rounded-lg shadow p-4">
                    <DataTable 
                        :data="tableData" 
                        :options="tableOptions"
                        class="display w-full"
                    >
                    </DataTable>
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