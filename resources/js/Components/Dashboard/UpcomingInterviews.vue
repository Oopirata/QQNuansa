<script setup>
import { ref } from "vue";

// Accept the interviews prop from parent
const props = defineProps({
    interviews: {
        type: Array,
        default: () => []
    }
});

// Format day number (e.g., "25")
const getDayNumber = (dateString) => {
    return new Date(dateString).getDate();
};

// Format day name (e.g., "Tue")
const getDayName = (dateString) => {
    return new Date(dateString).toLocaleDateString("en-US", { weekday: "short" });
};
</script>

<template>
    <div>
        <h2 class="text-xl font-bold mb-4">Upcoming Interviews</h2>
        
        <!-- No interviews state -->
        <div v-if="!interviews || interviews.length === 0" class="text-center py-8 bg-white rounded-lg shadow">
            <p class="text-gray-500">No upcoming interviews scheduled</p>
        </div>
        
        <!-- Interviews grid -->
        <div v-else class="grid grid-cols-2 gap-4">
            <div
                v-for="interview in interviews"
                :key="interview.id"
                class="bg-white p-4 rounded-lg shadow flex items-center"
            >
                <div class="text-center mr-4">
                    <p class="text-2xl font-bold">{{ getDayNumber(interview.tanggal) }}</p>
                    <p class="text-gray-500">{{ getDayName(interview.tanggal) }}</p>
                </div>
                <div>
                    <p class="font-bold">
                        {{ interview.judul }} ({{ interview.time }})
                    </p>
                    <p class="text-gray-500">
                        {{ interview.deskripsi }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>