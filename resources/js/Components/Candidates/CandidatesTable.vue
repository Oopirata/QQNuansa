<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
    candidates: {
        type: Object,
        required: true
    },
    detailRoute: {
        type: String,
        required: true
    }
})

const goToDetail = (id) => {
    router.visit(route(props.detailRoute, id))
}

// Format salary untuk tampilan
const formatSalary = (salaryRange) => {
    if (!salaryRange) return 'N/A';
    
    const formatValue = (value) => {
        const millions = value / 1000000;
        if (millions >= 1) {
            return `Rp ${millions.toFixed(1)}M`;
        } else {
            return `Rp ${(value / 1000).toFixed(0)}K`;
        }
    }
    
    return `${formatValue(salaryRange.min)} - ${formatValue(salaryRange.max)}`;
}
</script>

<template>
    <div class="flex-1 bg-white rounded-lg shadow overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Degree</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IPK</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salary Range</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template v-if="candidates.data && candidates.data.length > 0">
                        <tr v-for="candidate in candidates.data" 
                            :key="candidate.id"
                            @click="goToDetail(candidate.id)"
                            class="hover:bg-gray-50 cursor-pointer transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ candidate.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ candidate.user }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ candidate.degree }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ candidate.ipk }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatSalary(candidate.salary_range) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ candidate.job_vacancy }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ new Date(candidate.created_at).toLocaleDateString() }}
                            </td>
                        </tr>
                    </template>
                    <tr v-else>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No candidates found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination with preserveState -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 mt-auto">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ candidates.from }}</span> to 
                    <span class="font-medium">{{ candidates.to }}</span> of 
                    <span class="font-medium">{{ candidates.total }}</span> results
                </div>
                
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button
                        @click="router.visit(candidates.prev_page_url, { preserveState: true })"
                        :disabled="!candidates.prev_page_url"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border"
                        :class="[!candidates.prev_page_url ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-500 hover:bg-gray-50']"
                    >
                        <span class="sr-only">Previous</span>
                        <i class="fas fa-chevron-left w-5 h-5"></i>
                    </button>

                    <template v-for="link in candidates.links.slice(1, -1)" :key="link.label">
                        <button
                            @click="router.visit(link.url, { preserveState: true })"
                            class="relative inline-flex items-center px-4 py-2 border"
                            :class="{
                                'z-10 bg-blue-50 border-blue-500 text-blue-600': link.active,
                                'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active
                            }"
                        >
                            <span v-html="link.label"></span>
                        </button>
                    </template>

                    <button
                        @click="router.visit(candidates.next_page_url, { preserveState: true })"
                        :disabled="!candidates.next_page_url"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border"
                        :class="[!candidates.next_page_url ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-500 hover:bg-gray-50']"
                    >
                        <span class="sr-only">Next</span>
                        <i class="fas fa-chevron-right w-5 h-5"></i>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>