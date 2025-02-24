<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    minValue: {
        type: [Number, String],
        required: true
    },
    maxValue: {
        type: [Number, String],
        required: true
    },
    currentMin: {
        type: [Number, String],
        default: null
    },
    currentMax: {
        type: [Number, String],
        default: null
    },
    step: {
        type: [Number, String],
        default: 1
    },
    formatValue: {
        type: Function,
        default: (value) => value
    }
})

const emit = defineEmits(['update'])

const showModal = ref(false)
const localMin = ref(props.currentMin !== null ? props.currentMin : props.minValue)
const localMax = ref(props.currentMax !== null ? props.currentMax : props.maxValue)

// Update local values when props change
watch(() => props.currentMin, (newVal) => {
    if (newVal !== null) localMin.value = newVal
})

watch(() => props.currentMax, (newVal) => {
    if (newVal !== null) localMax.value = newVal
})

const handleClick = () => {
    showModal.value = !showModal.value
}

const applyRange = () => {
    emit('update', {
        min: parseFloat(localMin.value),
        max: parseFloat(localMax.value)
    })
    showModal.value = false
}

const clearRange = () => {
    localMin.value = props.minValue
    localMax.value = props.maxValue
    emit('update', { min: null, max: null })
    showModal.value = false
}

const isRangeActive = () => {
    return props.currentMin !== null || props.currentMax !== null
}
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
            
            <!-- Selected Range Display -->
            <div class="flex flex-wrap gap-2">
                <span
                    v-if="isRangeActive()"
                    class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium"
                >
                    {{ formatValue(currentMin) }} - {{ formatValue(currentMax) }}
                </span>
                <span v-else class="text-gray-500 text-sm">
                    Any range
                </span>
            </div>
        </button>

        <!-- Filter Modal -->
        <div
            v-if="showModal"
            class="absolute z-10 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 p-4"
        >
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div class="text-gray-600 text-sm">Min: {{ formatValue(localMin) }}</div>
                    <div class="text-gray-600 text-sm">Max: {{ formatValue(localMax) }}</div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Minimum</label>
                        <input 
                            type="number" 
                            v-model="localMin"
                            :min="minValue"
                            :max="localMax"
                            :step="step"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Maximum</label>
                        <input 
                            type="number" 
                            v-model="localMax"
                            :min="localMin"
                            :max="maxValue"
                            :step="step"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                </div>
                
                <div class="flex justify-between pt-2">
                    <button 
                        @click="clearRange"
                        class="text-gray-600 hover:text-gray-800 text-sm"
                    >
                        Clear
                    </button>
                    <button 
                        @click="applyRange"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700"
                    >
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.transform {
    transition: transform 0.2s ease;
}
</style>