<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";

// Add state for submenu
const isSubMenuOpen = ref(false);

// Add toggle function
const toggleSubMenu = () => {
    isSubMenuOpen.value = !isSubMenuOpen.value;
};

const candidateItems = [
    { name: "New", path: "/adminNewCandidates" },
    { name: "Screened", path: "/candidates/screened" },
    { name: "Interview", path: "/candidates/interview" },
    { name: "Rejected", path: "/candidates/rejected" },
];
</script>

<template>
    <Head title="Admin Dashboard" />
    <div class="bg-white">
        <div class="flex min-h-screen">
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
                                        :class="{
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
                                href="/email"
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

            <div class="flex-1 p-6">
                <!-- Header with Actions -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Dashboard</h1>
                    <div class="flex space-x-4">
                        <a href="/companyprofile">
                            <button
                                class="bg-gray-700 text-white px-4 py-2 rounded"
                            >
                                Visit Website
                            </button>
                        </a>

                        <button
                            class="bg-purple-600 text-white px-4 py-2 rounded"
                        >
                            + New job
                        </button>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="flex border-b mb-4">
                    <Link
                        href="/adminDashboard"
                        class="text-purple-500 mr-4 pb-2 border-b-2 border-purple-500"
                        >Overview</Link
                    >
                    <Link
                        href="/adminDashboardSchedule"
                        class="text-gray-700 mr-4 pb-2 border-b-2 border-transparent hover:border-purple-500"
                        >Schedule</Link
                    >
                </div>

                <!-- Analytics Report Section -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4">Analytics Report</h2>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex gap-40">
                            <!-- New Candidates Stats -->
                            <div>
                                <h3 class="text-gray-700">New candidates</h3>
                                <p class="text-3xl font-bold">100</p>
                                <p class="text-gray-500">In last 7 days</p>
                            </div>
                            <!-- Employees Stats -->
                            <div>
                                <h3 class="text-gray-700">Employees</h3>
                                <p class="text-3xl font-bold">100</p>
                                <p class="text-gray-500">In last 7 days</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Interviews Section -->
                <div>
                    <h2 class="text-xl font-bold mb-4">Upcoming Interviews</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Interview Card Component -->
                        <div
                            v-for="n in 6"
                            :key="n"
                            class="bg-white p-4 rounded-lg shadow flex items-center"
                        >
                            <!-- Date Display -->
                            <div class="text-center mr-4">
                                <p class="text-2xl font-bold">25</p>
                                <p class="text-gray-500">Tue</p>
                            </div>
                            <!-- Interview Details -->
                            <div>
                                <p class="font-bold">
                                    Ivan Serhin (10:30 - 12:00)
                                </p>
                                <p class="text-gray-500">
                                    Product Designer phone screening
                                </p>
                            </div>
                            <!-- Action Icons -->
                            <div class="ml-auto flex space-x-2">
                                <i class="fas fa-link text-gray-500"></i>
                                <i class="fas fa-ellipsis-h text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Menerapkan font ke seluruh komponen */
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
</style>
