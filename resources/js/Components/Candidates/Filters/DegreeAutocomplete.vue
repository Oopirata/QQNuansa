<script setup>
import { ref, watch, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    title: {
        type: String,
        default: 'Degree'
    },
    selectedDegrees: {
        type: Array,
        required: true
    },
    availableDegrees: {
        type: Array,
        required: true
    },
    initSearch: {
        type: String,
        default: ''
    },
    // Tambahkan prop untuk filter lain yang sudah ada
    selectedJobTitles: {
        type: Array,
        default: () => []
    },
    ipkRange: {
        type: Object,
        default: () => ({ min: null, max: null })
    },
    salaryRange: {
        type: Object,
        default: () => ({ min: null, max: null })
    },
    // Tambahkan prop untuk tags 
    tags: {
        type: Array,
        default: () => []
    }
})

const search = ref(props.initSearch)

// Handle global search dengan preserving filter lainnya
let timeout
watch(search, (newValue) => {
    clearTimeout(timeout)
    
    timeout = setTimeout(() => {
        // Buat objek data yang berisi semua filter aktif
        const data = {
            search: newValue,
            jobTitles: props.selectedJobTitles,
            degrees: props.selectedDegrees,
            tags: JSON.stringify(props.tags), // Pastikan stringified
            ipkMin: props.ipkRange.min,
            ipkMax: props.ipkRange.max,
            salaryMin: props.salaryRange.min,
            salaryMax: props.salaryRange.max
        }
        
        // Emit updateSearch event untuk parent
        emit('updateSearch', newValue)
    }, 300)
})

const emit = defineEmits(['update', 'addNewDegree', 'updateTags', 'updateSearch'])

const showModal = ref(false)

const handleClick = () => {
    showModal.value = !showModal.value
}

const removeDegree = (degreeId) => {
    // Hapus degree dari selectedDegrees
    const updatedDegrees = props.selectedDegrees.filter(id => id !== degreeId)
    emit('update', updatedDegrees)
    
    // Hapus juga dari tags jika ada
    const degree = props.availableDegrees.find(d => d.id === degreeId)
    if (degree) {
        const updatedTags = props.tags.filter(tag => 
            !(tag.type === 'Degree' && tag.value === degree.name)
        )
        emit('updateTags', updatedTags)
    }
}

// Fungsi add tag yang juga mempertahankan filter lain
const addNewTag = () => {
    if (!search.value.trim()) return
    
    const searchValue = search.value.trim()
    
    // Check if tag already exists in availableDegrees
    const existingDegree = props.availableDegrees.find(
        degree => degree.name.toLowerCase() === searchValue.toLowerCase()
    )
    
    let updatedDegrees = [...props.selectedDegrees]
    let updatedTags = [...props.tags]
    
    if (existingDegree) {
        // If it exists, add it to selected if not already there
        if (!updatedDegrees.includes(existingDegree.id)) {
            updatedDegrees.push(existingDegree.id)
            
            // Tambahkan juga ke tags dengan format yang konsisten
            const newTag = {
                type: 'Degree',
                value: existingDegree.name, // Nilai untuk filter
                text: existingDegree.name,  // Untuk UI display
                id: existingDegree.id
            }
            
            // Periksa apakah tag sudah ada di array tags
            const tagExists = updatedTags.some(tag => 
                tag.type === 'Degree' && tag.value === existingDegree.name
            )
            
            if (!tagExists) {
                updatedTags.push(newTag)
            }
        }
    } else {
        // Create a new degree/tag
        const newDegree = {
            id: `degree-new-${Date.now()}`,
            name: searchValue,
            color: 'blue',
            isNew: true
        }
        
        // Emit event to add the new degree
        emit('addNewDegree', newDegree)
        updatedDegrees.push(newDegree.id)
        
        // Tambahkan juga ke tags dengan format yang konsisten
        const newTag = {
            type: 'Degree',
            value: searchValue, // Nilai untuk filter
            text: searchValue,  // Untuk UI display
            id: newDegree.id
        }
        updatedTags.push(newTag)
    }
    
    // Update degrees dan tags via events ke parent
    emit('update', updatedDegrees)
    emit('updateTags', updatedTags)
    
    // Reset search setelah menambahkan tag
    search.value = ''
}

// Get names of selected degrees
const selectedDegreeNames = computed(() => {
    return props.selectedDegrees.map(id => {
        const degree = props.availableDegrees.find(d => d.id === id)
        return degree ? degree.name : ''
    }).filter(name => name)
})
</script>

<template>
    <div class="relative">
        <!-- Filter Button -->
        <button
            class="w-full text-left bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm hover:bg-gray-100 transition-colors duration-200 group"
            @click="handleClick"
        >
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-medium text-gray-900">{{ title }}</h4>
                <i
                    class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 transition-transform duration-200"
                    :class="{ 'transform rotate-90': showModal }"
                ></i>
            </div>
            
            <!-- Selected Degrees Tags -->
            <div class="flex flex-wrap gap-2">
                <span
                    v-for="degreeName in selectedDegreeNames"
                    :key="degreeName"
                    class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium"
                >
                    {{ degreeName }}
                </span>
                <span v-if="props.selectedDegrees.length === 0" class="text-gray-500 text-sm">
                    Any degree
                </span>
            </div>
        </button>

        <!-- Modal with combined search and add tag functionality -->
        <div
            v-if="showModal"
            class="absolute z-10 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 p-4"
        >
            <!-- Combined Search & Add -->
            <div class="mb-4">
                <div class="flex items-center gap-2">
                    <input
                        type="text"
                        v-model="search"
                        autocomplete="off"
                        placeholder="Search or add filter..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @keyup.enter="addNewTag"
                    />
                    <button 
                        @click="addNewTag"
                        class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Add
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Type to search candidates or press Enter/Add to create a filter tag
                </p>
            </div>
            
            <!-- Selected Degrees/Tags -->
            <div class="mt-4 pt-3 border-t border-gray-200">
                <h5 class="text-sm font-medium text-gray-700 mb-2">Selected Filter Tags</h5>
                <div class="flex flex-wrap gap-2">
                    <div
                        v-for="id in props.selectedDegrees"
                        :key="id"
                        class="flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm"
                    >
                        {{ props.availableDegrees.find(d => d.id === id)?.name || '' }}
                        <button 
                            @click.stop="removeDegree(id)" 
                            class="ml-1 text-blue-700 hover:text-blue-900"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <span v-if="props.selectedDegrees.length === 0" class="text-gray-500 text-sm">
                        No filter tags selected
                    </span>
                </div>
            </div>
            
            <!-- Close button -->
            <div class="mt-4 flex justify-end">
                <button
                    @click="showModal = false"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.transform {
    transition: transform 0.2s ease;
}
</style>