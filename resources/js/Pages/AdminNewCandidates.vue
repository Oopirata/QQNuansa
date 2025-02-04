<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar/Sidebar.vue'
import SearchBar from '@/Components/Candidates/SearchBar.vue'
import FilterPanel from '@/Components/Candidates/Filters/FilterPanel.vue'
import CandidatesTable from '@/Components/Candidates/CandidatesTable.vue'

const props = defineProps({
    candidates: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({
            search: ''
        })
    }
})

const searchQuery = ref('')
const selectedJobTitles = ref([])
const selectedDegrees = ref([])

const handleSearch = (query) => {
    searchQuery.value = query
    applyFilters()
}

const handleJobTitlesUpdate = (jobTitles) => {
    selectedJobTitles.value = jobTitles
    applyFilters()
}

const handleDegreesUpdate = (degrees) => {
    selectedDegrees.value = degrees
    applyFilters()
}

const applyFilters = () => {
    router.visit(route('adminNewCandidates'), {
        data: {
            jobTitles: selectedJobTitles.value,
            degrees: selectedDegrees.value,
            search: searchQuery.value,
        },
        preserveState: true,
        preserveScroll: true,
    })
}

const goToDetail = (id) => {
    router.visit(route('adminDetailNewCandidates', id))
}
</script>

<template>
    <Head title="Candidates Dashboard" />
    <div class="flex h-screen bg-white">
        <Sidebar :user="$page.props.auth.user" />
        
        <div class="flex-1 overflow-hidden">
            <div class="p-6 h-full flex flex-col">
                <SearchBar
                    :totalCandidates="candidates.total"
                    @update:searchQuery="handleSearch"
                />
                
                <div class="flex flex-1 gap-6 min-h-0">
                    <FilterPanel
                        :selectedJobTitles="selectedJobTitles"
                        :selectedDegrees="selectedDegrees"
                        @updateJobTitles="handleJobTitlesUpdate"
                        @updateDegrees="handleDegreesUpdate"
                    />
                    
                    <CandidatesTable
                        :candidates="candidates"
                        @rowClick="goToDetail"
                    />
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