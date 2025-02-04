<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    totalCandidates: {
        type: Number,
        required: true
    },
    initSearch: {
        type: String,
        default: ''
    }
})

const search = ref(props.initSearch)
const isLoading = ref(false)

let timeout
watch(search, (newValue) => {
    clearTimeout(timeout)
    isLoading.value = true
    
    timeout = setTimeout(() => {
        router.visit(
            route('adminNewCandidates'),
            {
                data: { search: newValue },
                preserveState: true,
                preserveScroll: true,
                replace: true,
                onFinish: () => {
                    isLoading.value = false
                }
            }
        )
    }, 300)
})
</script>

<template>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">
            New Candidates
            <span class="text-gray-500 ml-2">{{ totalCandidates }}</span>
        </h2>
        <div class="relative">
            <input
                v-model="search"
                type="text"
                autocomplete="off"
                placeholder="Search candidates..."
                class="border rounded-lg p-2 pl-8 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow duration-200"
            />
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                <i v-if="!isLoading" class="fas fa-search text-gray-400"></i>
                <i v-else class="fas fa-spinner fa-spin text-gray-400"></i>
            </div>
        </div>
    </div>
</template>