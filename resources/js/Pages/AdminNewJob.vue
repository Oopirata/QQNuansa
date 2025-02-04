# NewJobForm.vue
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

// Form data
const jobTitle = ref("");
const jobDescription = ref("");
const additionalQuestions = ref(["", ""]);
const salaryMin = ref("");
const salaryMax = ref("");
const salaryRanges = ref([{ min: "", max: "" }]);

// Methods
const addQuestion = () => {
    additionalQuestions.value.push("");
};

const addSalaryRange = () => {
    salaryRanges.value.push({ min: "", max: "" });
};

const submitJob = () => {
    // Handle job submission
    console.log({
        jobTitle: jobTitle.value,
        jobDescription: jobDescription.value,
        additionalQuestions: additionalQuestions.value,
        salaryRanges: salaryRanges.value,
    });
};

const isSubMenuOpen = ref(false);

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
    <Head title="New Job" />
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
                            class="flex items-center text-purple-600 hover:text-gray-900"
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
        <div class="w-4/5 p-8">
            <div class="flex justify-between items-center mb-8">
                <a href="/adminDashboard" class="text-xl">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-xl font-bold">New job</h2>
                <button
                    @click="submitJob"
                    class="bg-purple-500 text-white px-4 py-2 rounded"
                >
                    Post job
                </button>
            </div>
            <div class="flex">
                <!-- Left Column -->
                <div class="w-1/2 pr-4">
                    <div class="mb-4">
                        <label class="block mb-2">Job Title</label>
                        <input
                            v-model="jobTitle"
                            type="text"
                            class="w-full p-2 border rounded"
                        />
                    </div>
                    <div>
                        <label class="block mb-2">Job Description</label>
                        <textarea
                            v-model="jobDescription"
                            class="w-full p-2 border rounded h-32"
                        ></textarea>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="w-1/2 pl-4">
                    <div class="mb-8">
                        <h3 class="font-bold mb-4">Additional Question</h3>
                        <div
                            v-for="(question, index) in additionalQuestions"
                            :key="index"
                        >
                            <input
                                v-model="additionalQuestions[index]"
                                type="text"
                                placeholder="Lorem Ipsum"
                                class="w-full p-2 border rounded mb-2"
                            />
                        </div>
                        <button
                            @click="addQuestion"
                            class="bg-purple-500 text-white px-4 py-2 rounded w-full"
                        >
                            + Add Question
                        </button>
                    </div>
                    <div>
                        <h3 class="font-bold mb-4">Salary Range</h3>
                        <div
                            v-for="(range, index) in salaryRanges"
                            :key="index"
                        >
                            <div class="flex items-center mb-2">
                                <input
                                    v-model="range.min"
                                    type="text"
                                    placeholder="500.000"
                                    class="w-full p-2 border rounded mr-2"
                                />
                                <input
                                    v-model="range.max"
                                    type="text"
                                    placeholder="1.000.000"
                                    class="w-full p-2 border rounded"
                                />
                            </div>
                        </div>
                        <button
                            @click="addSalaryRange"
                            class="bg-purple-500 text-white px-4 py-2 rounded w-full"
                        >
                            + Add Salary
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
