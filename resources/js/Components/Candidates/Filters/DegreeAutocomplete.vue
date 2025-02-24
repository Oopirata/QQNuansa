<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
    title: {
        type: String,
        default: 'Degree'
    },
    selectedDegrees: {
        type: Array,
        required: true
    },
    // Data degree akan dipassing langsung dari parent
    availableDegrees: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['update'])

const showModal = ref(false)
const searchQuery = ref('')
const allDegrees = computed(() => {
    // Menggabungkan degrees dari props dengan yang sudah dipilih
    const degrees = [...props.availableDegrees]
    
    // Tambahkan degree yang dipilih tapi belum ada di available degrees
    props.selectedDegrees.forEach(degreeId => {
        if (!degrees.some(d => d.id === degreeId)) {
            // Ini hanya fallback, idealnya degree ID selalu ada di availableDegrees
            degrees.push({
                id: degreeId,
                name: `Degree ${degreeId}`,
                color: 'blue'
            })
        }
    })
    
    return degrees
})

const filteredDegrees = computed(() => {
    if (!searchQuery.value.trim()) {
        return allDegrees.value
    }
    
    const lowerQuery = searchQuery.value.toLowerCase()
    return allDegrees.value.filter(degree => 
        degree.name.toLowerCase().includes(lowerQuery)
    )
})

const handleClick = () => {
    showModal.value = !showModal.value
}

const addDegree = (degree) => {
    if (!props.selectedDegrees.includes(degree.id)) {
        emit('update', [...props.selectedDegrees, degree.id])
    }
}

const removeDegree = (degreeId) => {
    emit('update', props.selectedDegrees.filter(id => id !== degreeId))
}

const createTag = () => {
    if (!searchQuery.value.trim()) return
    
    // Check if the degree already exists
    const exists = allDegrees.value.find(d => 
        d.name.toLowerCase() === searchQuery.value.toLowerCase()
    )
    
    if (exists) {
        addDegree(exists)
    } else {
        // Untuk implementasi tanpa API, kita generate ID lokal
        const newDegree = {
            id: `new-${Date.now()}`,
            name: searchQuery.value.trim(),
            color: 'green',
            isNew: true
        }
        
        // Emit event dengan degree baru
        emit('addNewDegree', newDegree)
        addDegree(newDegree)
    }
    
    searchQuery.value = ''
}

const getSelectedDegreeNames = computed(() => {
    return props.selectedDegrees.map(id => {
        const degree = allDegrees.value.find(d => d.id === id)
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
                    v-for="degreeName in getSelectedDegreeNames"
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

        <!-- Degree Selection Modal -->
        <div
            v-if="showModal"
            class="absolute z-10 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 p-4"
        >
            <div class="mb-4">
                <div class="flex items-center gap-2">
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Search or add degree..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @keyup.enter="createTag"
                    />
                    <button 
                        @click="createTag"
                        class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Add
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Type a degree and press Enter or click Add to create a new filter
                </p>
            </div>
            
            <div class="max-h-60 overflow-y-auto">
                <div class="space-y-1">
                    <div
                        v-for="degree in filteredDegrees"
                        :key="degree.id"
                        class="flex items-center justify-between p-2 rounded hover:bg-gray-50 cursor-pointer"
                        @click="addDegree(degree)"
                    >
                        <span :class="{'font-semibold': props.selectedDegrees.includes(degree.id)}">
                            {{ degree.name }}
                        </span>
                        <span v-if="degree.isNew" class="text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                            New
                        </span>
                        <span 
                            v-if="props.selectedDegrees.includes(degree.id)"
                            class="text-blue-600"
                        >
                            <i class="fas fa-check"></i>
                        </span>
                    </div>
                    
                    <div v-if="filteredDegrees.length === 0" class="p-2 text-center text-gray-500">
                        No degrees found. Type to create a new one.
                    </div>
                </div>
            </div>
            
            <!-- Selected Degrees -->
            <div class="mt-4 pt-3 border-t border-gray-200">
                <h5 class="text-sm font-medium text-gray-700 mb-2">Selected Degrees</h5>
                <div class="flex flex-wrap gap-2">
                    <div
                        v-for="id in props.selectedDegrees"
                        :key="id"
                        class="flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm"
                    >
                        {{ allDegrees.find(d => d.id === id)?.name || '' }}
                        <button 
                            @click.stop="removeDegree(id)" 
                            class="ml-1 text-blue-700 hover:text-blue-900"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <span v-if="props.selectedDegrees.length === 0" class="text-gray-500 text-sm">
                        No degrees selected
                    </span>
                </div>
            </div>
            
            <!-- Apply button for multiple selections -->
            <div class="mt-4 flex justify-end">
                <button
                    @click="showModal = false"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                >
                    Apply Filters
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