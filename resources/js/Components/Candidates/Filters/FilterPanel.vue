<script setup>
import FilterButton from './FilterButton.vue'
import RangeFilter from './RangeFilter.vue'
import DegreeAutocomplete from './DegreeAutocomplete.vue'
import { computed, defineProps, defineEmits } from 'vue'
import { ref, onMounted } from 'vue'

const props = defineProps({
    selectedJobTitles: {
        type: Array,
        required: true
    },
    selectedDegrees: {
        type: Array,
        required: true
    },
    ipkRange: {
        type: Object,
        default: () => ({ min: null, max: null })
    },
    salaryRange: {
        type: Object,
        default: () => ({ min: null, max: null })
    },
    jobTitles: {
        type: Array,
        required: true
    },
    // Data degree dari controller
    allDegrees: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits([
    'updateJobTitles', 
    'updateDegrees', 
    'updateIPKRange',
    'updateSalaryRange',
    'addNewDegree'
])

const customDegrees = ref([])

// Menggabungkan degree dari API dengan custom degree
const availableDegrees = computed(() => {
    return [...props.allDegrees, ...customDegrees.value]
})

// Format salary to display in millions
const formatSalary = (value) => {
    if (value === null) return 'Any'
    
    const millions = value / 1000000
    if (millions >= 1) {
        return `Rp ${millions.toFixed(1)}M`
    } else {
        return `Rp ${(value / 1000).toFixed(0)}K`
    }
}

// Handler untuk degree baru
const handleAddNewDegree = (degree) => {
    customDegrees.value.push(degree)
    emit('addNewDegree', degree)
}
</script>

<template>
    <div class="w-72 flex flex-col bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
        </div>
        <div class="p-4 space-y-4 overflow-y-auto">
            <FilterButton
                title="Job Title"
                :items="jobTitles"
                :selectedItems="selectedJobTitles"
                @updateSelection="$emit('updateJobTitles', $event)"
            />
            
            <!-- DegreeAutocomplete dengan data lokal -->
            <DegreeAutocomplete
                title="Degree"
                :selectedDegrees="selectedDegrees"
                :availableDegrees="availableDegrees"
                @update="$emit('updateDegrees', $event)"
                @addNewDegree="handleAddNewDegree"
            />
            
            <RangeFilter
                title="IPK"
                :minValue="0"
                :maxValue="4.0"
                :currentMin="ipkRange.min"
                :currentMax="ipkRange.max"
                :step="0.1"
                :formatValue="(val) => val.toFixed(1)"
                @update="$emit('updateIPKRange', $event)"
            />
            <RangeFilter
                title="Salary"
                :minValue="1000000"
                :maxValue="50000000"
                :currentMin="salaryRange.min"
                :currentMax="salaryRange.max"
                :step="500000"
                :formatValue="formatSalary"
                @update="$emit('updateSalaryRange', $event)"
            />
        </div>
    </div>
</template>