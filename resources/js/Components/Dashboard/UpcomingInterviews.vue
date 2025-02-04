<script setup>
</script>

<template>
    <div>
        <h2 class="text-xl font-bold mb-4">Upcoming Interviews</h2>
        <div class="grid grid-cols-2 gap-4">
            <div
                v-for="n in 6"
                :key="n"
                class="bg-white p-4 rounded-lg shadow flex items-center"
            >
                <div class="text-center mr-4">
                    <p class="text-2xl font-bold">25</p>
                    <p class="text-gray-500">Tue</p>
                </div>
                <div>
                    <p class="font-bold">
                        Ivan Serhin (10:30 - 12:00)
                    </p>
                    <p class="text-gray-500">
                        Product Designer phone screening
                    </p>
                </div>
                <div class="ml-auto flex space-x-2">
                    <i class="fas fa-link text-gray-500"></i>
                    <i class="fas fa-ellipsis-h text-gray-500"></i>
                </div>
            </div>
        </div>
    </div>
</template>