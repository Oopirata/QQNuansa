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

const deleteJob = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus job ini?')) {
        router.delete(route('adminJobDelete', id), {
            onSuccess: () => {
                // Create and show alert dialog
                const alertDialog = document.createElement("div");
                alertDialog.className =
                    "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50";

                alertDialog.innerHTML = `
                    <div class="bg-white p-8 rounded-lg shadow-xl max-w-sm mx-4" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center mb-6">
                            <div class="bg-green-100 rounded-full p-4">
                                <svg class="h-10 w-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-center text-gray-800 mb-3">Berhasil!</h3>
                        <p class="text-center text-gray-700 text-base mb-6">Job telah berhasil dihapus</p>
                        <p class="text-center text-gray-600 text-sm">Klik di luar kotak untuk menutup</p>
                    </div>
                `;

                alertDialog.addEventListener("click", function (event) {
                    if (event.target === alertDialog) {
                        document.body.removeChild(alertDialog);
                    }
                });

                document.body.appendChild(alertDialog);
            },
            onError: (errors) => {
                console.error(errors);
            },
        });
    }
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
                                        <button @click="deleteJob(job.id)" class="text-red-600 hover:text-red-900">
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