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
    },
    selectedIPK: {
        type: Array,
        required: true
    },
    selectedSalary: {
        type: Array,
        required: true
    }
})

const emit = defineEmits(['updateJobTitles', 'updateDegrees', 'updateIPK', 'updateSalary'])

const filters = {
    jobTitles: [
        { id: 1, name: "Psychologist Assistant", color: "green" },
        { id: 2, name: "HRD", color: "blue" },
    ],
    degrees: [
        { id: 1, name: "Bachelor", color: "pink" },
        { id: 2, name: "Master", color: "purple" },
    ],
    ipk: [
        { id: 1, name: "3.0 - 3.5", color: "yellow" },
        { id: 2, name: "3.6 - 4.0", color: "orange" },
    ],
    salary: [
        { id: 1, name: "< 5M", color: "red" },
        { id: 2, name: "5M - 10M", color: "blue" },
        { id: 3, name: "> 10M", color: "green" },
    ]
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
            <FilterButton
                title="IPK"
                :items="filters.ipk"
                :selectedItems="selectedIPK"
                @updateSelection="$emit('updateIPK', $event)"
            />
            <FilterButton
                title="Salary"
                :items="filters.salary"
                :selectedItems="selectedSalary"
                @updateSelection="$emit('updateSalary', $event)"
            />
        </div>
    </div>
</template>