<script setup>
import { ref } from 'vue'

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    items: {
        type: Array,
        required: true,
        // Each item should have: { id, name, color }
    },
    selectedItems: {
        type: Array,
        required: true
        // Array of selected item IDs
    }
})

const emit = defineEmits(['updateSelection'])

const showModal = ref(false)

const handleClick = () => {
    showModal.value = !showModal.value
}

const toggleItem = (itemId) => {
    let newSelection = [...props.selectedItems]
    const index = newSelection.indexOf(itemId)
    
    if (index === -1) {
        newSelection.push(itemId)
    } else {
        newSelection.splice(index, 1)
    }
    
    emit('updateSelection', newSelection)
}

// Cek apakah ada item yang dipilih
const isSelectionActive = () => {
    return props.selectedItems.length > 0
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
            
            <!-- Selected Items Display -->
            <div class="flex flex-wrap gap-2">
                <template v-if="isSelectionActive()">
                    <span
                        v-for="item in items.filter(item => selectedItems.includes(item.id))"
                        :key="item.id"
                        :class="`bg-${item.color}-100 text-${item.color}-800 px-3 py-1 rounded-full text-sm font-medium`"
                    >
                        {{ item.name }}
                    </span>
                </template>
                <span v-else class="text-gray-500 text-sm">
                    Any job title
                </span>
            </div>
        </button>

        <!-- Filter Modal -->
        <div
            v-if="showModal"
            class="absolute z-10 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 p-4"
        >
            <div class="space-y-2">
                <label
                    v-for="item in items"
                    :key="item.id"
                    class="flex items-center space-x-2 cursor-pointer p-2 hover:bg-gray-50 rounded"
                >
                    <input
                        type="checkbox"
                        :checked="selectedItems.includes(item.id)"
                        @change="toggleItem(item.id)"
                        class="rounded text-blue-600"
                    />
                    <span>{{ item.name }}</span>
                </label>
            </div>
        </div>
    </div>
</template>

<style scoped>
.transform {
    transition: transform 0.2s ease;
}
</style>