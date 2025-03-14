<script setup>
import { computed, onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const storedCandidates = ref(0);

onMounted(() => {
    // Retrieve from localStorage on component mount
    const saved = localStorage.getItem("newCandidates");
    storedCandidates.value = saved ? parseInt(saved) : 0;
});

// Mendapatkan jumlah kandidat baru (status 0)
const newCandidates = computed(() => {
    // Jika data tersedia dari props, gunakan itu
    if (
        page.props.analyticsData &&
        page.props.analyticsData.newCandidatesLast7Days !== undefined
    ) {
        // Save to localStorage when updated
        const value = page.props.analyticsData.newCandidatesLast7Days;
        localStorage.setItem("newCandidates", value);
        return value;
    }
    // Return the value from localStorage if props aren't available
    return storedCandidates.value;
});

// Mendapatkan jumlah karyawan
const employees = computed(() => {
    // Jika data tersedia dari props, gunakan itu
    if (
        page.props.analyticsData &&
        page.props.analyticsData.totalEmployees !== undefined
    ) {
        return page.props.analyticsData.totalEmployees;
    }
    // Sebagai fallback, gunakan 0
    return 0;
});
</script>

<template>
    <div class="mb-6">
        <h2 class="text-xl font-bold mb-4">Analytics Report</h2>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex gap-40">
                <div>
                    <h3 class="text-gray-700">New candidates</h3>
                    <p class="text-3xl font-bold">{{ newCandidates }}</p>
                    <p class="text-gray-500">In last 7 days</p>
                </div>
                <div>
                    <h3 class="text-gray-700">Employees</h3>
                    <p class="text-3xl font-bold">{{ employees }}</p>
                    <p class="text-gray-500">In last 7 days</p>
                </div>
            </div>
        </div>
    </div>
</template>
