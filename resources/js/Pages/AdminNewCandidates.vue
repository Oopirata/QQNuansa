# CandidatesDashboard.vue
<script setup>
import { ref } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";

// Search functionality
const searchQuery = ref("");

const props = defineProps({
    candidates: {
        type: Array,
        required: true,
    },
});

console.log(usePage().props.candidates);

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

const goToDetail = (id) => {
    router.visit(route("adminDetailNewCandidates", id)); // Arahkan ke route detail kandidat
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
                        type="text"
                        autocomplete="off"
                        placeholder="Search"
                        id="search"
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
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">ID</th>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Degree</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Job Title</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Applied At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr
                                v-for="candidate in candidates.data"
                                :key="candidate.id"
                                @click="goToDetail(candidate.id)"
                                class="cursor-pointer hover:bg-gray-100 transition"
                            >
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ candidate.id }}
                                </td>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ candidate.user }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ candidate.degree }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ candidate.job_vacancy }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ new Date(candidate.created_at).toLocaleDateString() }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="max-w-7xl mx-auto py-6">
                        <div class="max-w-none mx-auto">
                            <div
                                class="bg-white overflow-hidden shadow sm:rounded-lg"
                            >
                                <div
                                    class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
                                >
                                    <div
                                        class="flex-1 flex justify-between sm:hidden"
                                    />
                                    <div
                                        class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
                                    >
                                        <!-- Info Showing Results -->
                                        <div>
                                            <p class="text-sm text-gray-700">
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
                                            </p>
                                        </div>

                                        <!-- Pagination Buttons -->
                                        <div>
                                            <nav
                                                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                aria-label="Pagination"
                                            >
                                                <!-- Previous Page Button -->
                                                <button
                                                    @click="
                                                        $inertia.visit(
                                                            candidates.prev_page_url
                                                        )
                                                    "
                                                    :disabled="
                                                        !candidates.prev_page_url
                                                    "
                                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border text-sm font-medium"
                                                    :class="[
                                                        !candidates.prev_page_url
                                                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                            : 'bg-white text-gray-500 hover:bg-gray-50',
                                                    ]"
                                                >
                                                    <span class="sr-only"
                                                        >Previous</span
                                                    >
                                                    <i
                                                        class="fas fa-chevron-left w-5 h-5"
                                                    ></i>
                                                </button>

                                                <!-- Page Numbers -->
                                                <template
                                                    v-for="link in candidates.links.slice(
                                                        1,
                                                        -1
                                                    )"
                                                    :key="link.label"
                                                >
                                                    <button
                                                        @click="
                                                            $inertia.visit(
                                                                link.url
                                                            )
                                                        "
                                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                        :class="{
                                                            'z-10 bg-indigo-50 border-indigo-500 text-indigo-600':
                                                                link.active,
                                                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50':
                                                                !link.active,
                                                        }"
                                                    >
                                                        <span
                                                            v-html="link.label"
                                                        ></span>
                                                    </button>
                                                </template>

                                                <!-- Next Page Button -->
                                                <button
                                                    @click="
                                                        $inertia.visit(
                                                            candidates.next_page_url
                                                        )
                                                    "
                                                    :disabled="
                                                        !candidates.next_page_url
                                                    "
                                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border text-sm font-medium"
                                                    :class="[
                                                        !candidates.next_page_url
                                                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                            : 'bg-white text-gray-500 hover:bg-gray-50',
                                                    ]"
                                                >
                                                    <span class="sr-only"
                                                        >Next</span
                                                    >
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
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
</style>
