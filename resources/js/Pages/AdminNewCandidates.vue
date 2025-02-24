<script setup>
import { ref, onMounted, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
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
            search: '',
            jobTitles: [],
            degrees: [],
            tags: [],
            ipkRange: { min: null, max: null },
            salaryRange: { min: null, max: null }
        })
    },
    jobTitles: {
        type: Array,
        required: true
    },
    allDegrees: {
        type: Array,
        default: () => []
    }
})

// Initialize state with values from backend
const searchQuery = ref(props.filters.search || '')
const selectedJobTitles = ref(props.filters.jobTitles || [])
const selectedDegrees = ref(props.filters.degrees || [])
const ipkRange = ref(props.filters.ipkRange || { min: null, max: null })
const salaryRange = ref(props.filters.salaryRange || { min: null, max: null })
const activeTags = ref(props.filters.tags || [])
const customDegrees = ref([])

// Combine backend degrees with any custom degrees
const combinedDegrees = computed(() => {
    return [...props.allDegrees, ...customDegrees.value]
})

// Sync tags with other filters
const syncTagsWithFilters = () => {
    // Check for degree tags
    const degreeTags = activeTags.value.filter(tag => tag.type === 'Degree')
    
    // Sync with degree filter
    degreeTags.forEach(tag => {
        // Find degree ID that corresponds to the tag text
        const degree = combinedDegrees.value.find(d => d.name === tag.text)
        
        if (degree && !selectedDegrees.value.includes(degree.id)) {
            selectedDegrees.value.push(degree.id)
        }
    })
    
    // Check for job title tags
    const jobTags = activeTags.value.filter(tag => tag.type === 'Job Title')
    
    // Sync with job titles filter
    jobTags.forEach(tag => {
        if (typeof tag.value === 'number' && !selectedJobTitles.value.includes(tag.value)) {
            selectedJobTitles.value.push(tag.value)
        }
    })
}

// Handle adding a new degree
const handleAddNewDegree = (degree) => {
    customDegrees.value.push(degree)
    
    // Also add to selected degrees if not already there
    if (!selectedDegrees.value.includes(degree.id)) {
        selectedDegrees.value.push(degree.id)
    }
}

// Handle search queries
const handleSearch = (query) => {
    searchQuery.value = query
    applyFilters()
}

// Handle adding a tag from search suggestions
const handleAddTag = (tag) => {
    // Check if tag already exists
    const tagExists = activeTags.value.some(t => 
        t.type === tag.type && t.text === tag.text
    )
    
    if (!tagExists) {
        activeTags.value = [...activeTags.value, tag]
        
        // If it's a search tag, update search query
        if (tag.type === 'Search') {
            searchQuery.value = tag.text
        }
        
        // Sync with other filters
        syncTagsWithFilters()
        applyFilters()
    }
}

// Handle removing a tag
const handleRemoveTag = (tag) => {
    activeTags.value = activeTags.value.filter(t => 
        !(t.type === tag.type && t.text === tag.text)
    )
    
    // If it's a search tag being removed, clear search
    if (tag.type === 'Search') {
        searchQuery.value = ''
    }
    
    applyFilters()
}

// Standard filter handlers
const handleJobTitlesUpdate = (jobTitles) => {
    selectedJobTitles.value = jobTitles
    applyFilters()
}

const handleDegreesUpdate = (degrees) => {
    selectedDegrees.value = degrees
    applyFilters()
}

const handleIPKRangeUpdate = (range) => {
    ipkRange.value = range
    applyFilters()
}

const handleSalaryRangeUpdate = (range) => {
    salaryRange.value = range
    applyFilters()
}

// Apply all filters
const applyFilters = () => {
    router.get(
        route('adminNewCandidates'), 
        {
            jobTitles: selectedJobTitles.value,
            degrees: selectedDegrees.value,
            ipkMin: ipkRange.value.min,
            ipkMax: ipkRange.value.max,
            salaryMin: salaryRange.value.min,
            salaryMax: salaryRange.value.max,
            search: searchQuery.value,
            tags: JSON.stringify(activeTags.value)
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    )
}

const goToDetail = (id) => {
    router.visit(route('adminDetailNewCandidates', id))
}

// Ensure tags and filters are synced on mount
onMounted(() => {
    syncTagsWithFilters()
})
</script>

<template>
    <Head title="Candidates Dashboard" />
    <div class="flex h-screen bg-white">
        <Sidebar :user="$page.props.auth.user" />
        
        <div class="flex-1 overflow-hidden">
            <div class="p-6 h-full flex flex-col">
                <SearchBar
                    :totalCandidates="candidates.total"
                    :initialSearch="searchQuery"
                    :activeTags="activeTags"
                    :jobTitles="jobTitles"
                    :allDegrees="combinedDegrees"
                    @update:searchQuery="handleSearch"
                    @addTag="handleAddTag"
                    @removeTag="handleRemoveTag"
                />
                
                <div class="flex flex-1 gap-6 min-h-0">
                    <FilterPanel
                        :selectedJobTitles="selectedJobTitles"
                        :selectedDegrees="selectedDegrees"
                        :ipkRange="ipkRange"
                        :salaryRange="salaryRange"
                        :jobTitles="jobTitles"
                        :allDegrees="combinedDegrees"
                        @updateJobTitles="handleJobTitlesUpdate"
                        @updateDegrees="handleDegreesUpdate"
                        @updateIPKRange="handleIPKRangeUpdate"
                        @updateSalaryRange="handleSalaryRangeUpdate"
                        @addNewDegree="handleAddNewDegree"
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