<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

// Data jobs (dalam kasus nyata ini akan berasal dari backend)
const jobs = ref([
    {
        id: 1,
        position: "Frontend Developer",
        department: "Engineering",
        location: "Jakarta",
        type: "Full-time",
        posted_date: "2024-02-13",
        status: "Active",
    },
    {
        id: 2,
        position: "UI/UX Designer",
        department: "Design",
        location: "Bandung",
        type: "Full-time",
        posted_date: "2024-02-13",
        status: "Active",
    },
]);
</script>

<template>
    <Head title="Admin Job List" />
    <div class="bg-white">
        <div class="flex min-h-screen">
            <Sidebar :user="$page.props.auth.user" />
            <main class="flex-1">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold">Job List</h1>
                        <div class="flex space-x-4">
                            <a href="/companyprofile">
                                <button
                                    class="bg-gray-700 text-white px-4 py-2 rounded"
                                >
                                    Visit Website
                                </button>
                            </a>
                            <a href="/adminNewJob">
                                <button
                                    class="bg-purple-600 text-white px-4 py-2 rounded"
                                >
                                    + New job
                                </button>
                            </a>
                        </div>
                    </div>

                    <!-- Job List Table -->
                    <!-- <h1 class="text-3xl font-bold">Job List Table</h1> -->
                    <div
                        class="bg-white rounded-lg shadow overflow-hidden mt-20"
                    >
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Position
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Department
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Location
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Type
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Posted Date
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="job in jobs" :key="job.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ job.position }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ job.department }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ job.location }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ job.type }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ job.posted_date }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                                        >
                                            {{ job.status }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                    >
                                        <button
                                            class="text-indigo-600 hover:text-indigo-900 mr-3"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
</style>
