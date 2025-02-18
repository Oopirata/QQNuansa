<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import dayjs from 'dayjs';

const props = defineProps({
    jobs: {
        type: Array,
        required: true,
    },
});
console.log(props.jobs);

const truncateText = (text, length = 20) => {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
};

const goToDetail = (id) => {
    router.visit(route('adminJobDetail', id));
};
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
                                <button class="bg-gray-700 text-white px-4 py-2 rounded">
                                    Visit Website
                                </button>
                            </a>
                            <a href="/adminNewJob">
                                <button class="bg-purple-600 text-white px-4 py-2 rounded">
                                    + New job
                                </button>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow overflow-hidden mt-20">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Position
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Applicant
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Posted Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="job in jobs" :key="job?.id || Math.random()">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ job?.title || 'Untitled Position' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ job?.applicants?.length ?? 0 }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ truncateText(job?.description) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ job?.created_at ? dayjs(job.created_at).format('YYYY-MM-DD') : 'No date' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="job.status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        >
                                            {{ job.status == 1 ? 'Open' : 'Closed' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button @click="goToDetail(job.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </button>
                                        <button class="text-red-600 hover:text-red-900">
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