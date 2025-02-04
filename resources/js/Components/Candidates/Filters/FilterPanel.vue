<script setup>
import FilterButton from './FilterButton.vue'
import { ref } from 'vue'

const props = defineProps({
    selectedJobTitles: {
        type: Array,
        required: true
    },
    selectedDegrees: {
        type: Array,
        required: true
    }
})

const emit = defineEmits(['updateJobTitles', 'updateDegrees'])

const filters = {
    jobTitles: [
        { id: 1, name: "Psychologist Assistant", color: "green" },
        { id: 2, name: "HRD", color: "blue" },
    ],
    degrees: [
        { id: 1, name: "Bachelor", color: "pink" },
        { id: 2, name: "Master", color: "purple" },
    ],
}
</script>

<template>
    <div class="w-72 flex flex-col bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
        </div>
        <div class="p-4 space-y-4">
            <FilterButton
                title="Job Title"
                :items="filters.jobTitles"
                :selectedItems="selectedJobTitles"
                @updateSelection="$emit('updateJobTitles', $event)"
            />
            <FilterButton
                title="Degree"
                :items="filters.degrees"
                :selectedItems="selectedDegrees"
                @updateSelection="$emit('updateDegrees', $event)"
            />
        </div>
    </div>
</template>