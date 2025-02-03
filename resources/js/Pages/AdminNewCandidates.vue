# CandidatesDashboard.vue
<script setup>
import { ref } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";

const showJobTitleModal = ref(false);
const showDegreeModal = ref(false);
const selectedJobTitles = ref([]);
const selectedDegrees = ref([]);
const searchQuery = ref("");

const props = defineProps({
    candidates: {
        type: Array,
        required: true,
    },
});

console.log(usePage().props.candidates);

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

const goToDetail = (id) => {
    router.visit(route("adminDetailNewCandidates", id));
};

// Handle Job Title Filter Click
const handleJobTitleClick = () => {
    showJobTitleModal.value = !showJobTitleModal.value;
    // Close other modal if open
    if (showDegreeModal.value) showDegreeModal.value = false;
};

// Handle Degree Filter Click
const handleDegreeClick = () => {
    showDegreeModal.value = !showDegreeModal.value;
    // Close other modal if open
    if (showJobTitleModal.value) showJobTitleModal.value = false;
};

// Toggle Job Title Selection
const toggleJobTitle = (jobId) => {
    const index = selectedJobTitles.value.indexOf(jobId);
    if (index === -1) {
        selectedJobTitles.value.push(jobId);
    } else {
        selectedJobTitles.value.splice(index, 1);
    }
    applyFilters();
};

// Toggle Degree Selection
const toggleDegree = (degreeId) => {
    const index = selectedDegrees.value.indexOf(degreeId);
    if (index === -1) {
        selectedDegrees.value.push(degreeId);
    } else {
        selectedDegrees.value.splice(index, 1);
    }
    applyFilters();
};

// Apply Filters
const applyFilters = () => {
    // Here you would typically make an API call or filter the data
    router.visit(route("adminNewCandidates"), {
        data: {
            jobTitles: selectedJobTitles.value,
            degrees: selectedDegrees.value,
            search: searchQuery.value,
        },
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Candidates Dashboard" />
    <div class="flex h-screen bg-white">
        <!-- Added bg-gray-100 for better contrast -->
        <!-- Sidebar -->
        <div class="bg-blue-100 w-64 p-4 flex flex-col shadow-lg">
            <!-- Added shadow-lg -->
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
                            class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200"
                        >
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </Link>
                    </li>
                    <li class="mb-4">
                        <button
                            @click="toggleSubMenu"
                            class="flex items-center w-full text-gray-600 hover:text-gray-900 transition-colors duration-200"
                        >
                            <i class="fas fa-users mr-2"></i>
                            Candidates
                            <i
                                :class="[
                                    'fas ml-auto transition-transform duration-200',
                                    isSubMenuOpen
                                        ? 'fa-chevron-down'
                                        : 'fa-chevron-right',
                                ]"
                            ></i>
                        </button>
                        <ul v-if="isSubMenuOpen" class="ml-6 mt-2 space-y-2">
                            <li v-for="item in candidateItems" :key="item.name">
                                <Link
                                    :href="item.path"
                                    class="block py-1 transition-colors duration-200"
                                    :class="{
                                        'text-purple-600 font-medium':
                                            item.name === 'New',
                                        'text-gray-600 hover:text-gray-900':
                                            item.name !== 'New',
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
                            class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200"
                        >
                            <i class="fas fa-envelope mr-2"></i>
                            E-mail
                        </Link>
                    </li>
                </ul>
            </nav>

            <div
                class="mt-auto flex items-center p-3 bg-white rounded-lg shadow"
            >
                <img
                    src="/images/profile.png"
                    alt="User Avatar"
                    class="w-10 h-10 rounded-full mr-3"
                />
                <div>
                    <p class="text-gray-900 font-medium">John Doe</p>
                    <p class="text-gray-500 text-sm">Admin</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-hidden">
            <div class="p-6 h-full flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">
                        New Candidates
                        <span class="text-gray-500 ml-2">{{
                            candidates.length
                        }}</span>
                    </h2>
                    <div class="relative">
                        <input
                            v-model="searchQuery"
                            type="text"
                            autocomplete="off"
                            placeholder="Search candidates..."
                            class="border rounded-lg p-2 pl-8 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow duration-200"
                        />
                        <i
                            class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                        ></i>
                    </div>
                </div>

                <div class="flex flex-1 gap-6 min-h-0">
                    <!-- Added min-h-0 to allow flex child to scroll -->

                    <!-- Filters Panel -->
                    <div
                        class="w-72 flex flex-col bg-white rounded-lg shadow overflow-hidden"
                    >
                        <div class="p-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Filters
                            </h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Job Title Filter Button -->
                            <div class="relative">
                                <button
                                    class="w-full text-left bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm hover:bg-gray-100 transition-colors duration-200 group"
                                    @click="handleJobTitleClick"
                                >
                                    <div
                                        class="flex justify-between items-center mb-3"
                                    >
                                        <h4 class="font-medium text-gray-900">
                                            Job Title
                                        </h4>
                                        <i
                                            class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 transition-transform duration-200"
                                            :class="{
                                                'transform rotate-90':
                                                    showJobTitleModal,
                                            }"
                                        ></i>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            v-for="job in filters.jobTitles"
                                            :key="job.id"
                                            :class="`bg-${job.color}-100 text-${
                                                job.color
                                            }-800 px-3 py-1 rounded-full text-sm font-medium ${
                                                selectedJobTitles.includes(
                                                    job.id
                                                )
                                                    ? 'ring-2 ring-' +
                                                      job.color +
                                                      '-400'
                                                    : ''
                                            }`"
                                        >
                                            {{ job.name }}
                                        </span>
                                    </div>
                                </button>

                                <!-- Job Title Modal -->
                                <div
                                    v-if="showJobTitleModal"
                                    class="absolute z-10 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 p-4"
                                >
                                    <div class="space-y-2">
                                        <label
                                            v-for="job in filters.jobTitles"
                                            :key="job.id"
                                            class="flex items-center space-x-2 cursor-pointer p-2 hover:bg-gray-50 rounded"
                                        >
                                            <input
                                                type="checkbox"
                                                :checked="
                                                    selectedJobTitles.includes(
                                                        job.id
                                                    )
                                                "
                                                @change="toggleJobTitle(job.id)"
                                                class="rounded text-blue-600"
                                            />
                                            <span>{{ job.name }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Degree Filter Button -->
                            <div class="relative">
                                <button
                                    class="w-full text-left bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm hover:bg-gray-100 transition-colors duration-200 group"
                                    @click="handleDegreeClick"
                                >
                                    <div
                                        class="flex justify-between items-center mb-3"
                                    >
                                        <h4 class="font-medium text-gray-900">
                                            Degree
                                        </h4>
                                        <i
                                            class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 transition-transform duration-200"
                                            :class="{
                                                'transform rotate-90':
                                                    showDegreeModal,
                                            }"
                                        ></i>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            v-for="degree in filters.degrees"
                                            :key="degree.id"
                                            :class="`bg-${
                                                degree.color
                                            }-100 text-${
                                                degree.color
                                            }-800 px-3 py-1 rounded-full text-sm font-medium ${
                                                selectedDegrees.includes(
                                                    degree.id
                                                )
                                                    ? 'ring-2 ring-' +
                                                      degree.color +
                                                      '-400'
                                                    : ''
                                            }`"
                                        >
                                            {{ degree.name }}
                                        </span>
                                    </div>
                                </button>

                                <!-- Degree Modal -->
                                <div
                                    v-if="showDegreeModal"
                                    class="absolute z-10 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 p-4"
                                >
                                    <div class="space-y-2">
                                        <label
                                            v-for="degree in filters.degrees"
                                            :key="degree.id"
                                            class="flex items-center space-x-2 cursor-pointer p-2 hover:bg-gray-50 rounded"
                                        >
                                            <input
                                                type="checkbox"
                                                :checked="
                                                    selectedDegrees.includes(
                                                        degree.id
                                                    )
                                                "
                                                @change="
                                                    toggleDegree(degree.id)
                                                "
                                                class="rounded text-blue-600"
                                            />
                                            <span>{{ degree.name }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Candidates Table -->
                    <div
                        class="flex-1 bg-white rounded-lg shadow overflow-hidden flex flex-col"
                    >
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            ID
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Degree
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Job Title
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Applied At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="candidate in candidates.data"
                                        :key="candidate.id"
                                        @click="goToDetail(candidate.id)"
                                        class="hover:bg-gray-50 cursor-pointer transition-colors duration-200"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ candidate.id }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                        >
                                            {{ candidate.user }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ candidate.degree }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ candidate.job_vacancy }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{
                                                new Date(
                                                    candidate.created_at
                                                ).toLocaleDateString()
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div
                            class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 mt-auto"
                        >
                            <div
                                class="sm:flex sm:items-center sm:justify-between"
                            >
                                <div class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{
                                        candidates.from
                                    }}</span>
                                    to
                                    <span class="font-medium">{{
                                        candidates.to
                                    }}</span>
                                    of
                                    <span class="font-medium">{{
                                        candidates.total
                                    }}</span>
                                    results
                                </div>

                                <nav
                                    class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination"
                                >
                                    <button
                                        @click="
                                            $inertia.visit(
                                                candidates.prev_page_url
                                            )
                                        "
                                        :disabled="!candidates.prev_page_url"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border text-sm font-medium transition-colors duration-200"
                                        :class="[
                                            !candidates.prev_page_url
                                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                : 'bg-white text-gray-500 hover:bg-gray-50',
                                        ]"
                                    >
                                        <span class="sr-only">Previous</span>
                                        <i
                                            class="fas fa-chevron-left w-5 h-5"
                                        ></i>
                                    </button>

                                    <template
                                        v-for="link in candidates.links.slice(
                                            1,
                                            -1
                                        )"
                                        :key="link.label"
                                    >
                                        <button
                                            @click="$inertia.visit(link.url)"
                                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-200"
                                            :class="{
                                                'z-10 bg-blue-50 border-blue-500 text-blue-600':
                                                    link.active,
                                                'bg-white border-gray-300 text-gray-500 hover:bg-gray-50':
                                                    !link.active,
                                            }"
                                        >
                                            <span v-html="link.label"></span>
                                        </button>
                                    </template>

                                    <button
                                        @click="
                                            $inertia.visit(
                                                candidates.next_page_url
                                            )
                                        "
                                        :disabled="!candidates.next_page_url"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border text-sm font-medium transition-colors duration-200"
                                        :class="[
                                            !candidates.next_page_url
                                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                : 'bg-white text-gray-500 hover:bg-gray-50',
                                        ]"
                                    >
                                        <span class="sr-only">Next</span>
                                        <i
                                            class="fas fa-chevron-right w-5 h-5"
                                        ></i>
                                    </button>
                                </nav>
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
