<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import DashboardHeader from "@/Components/Dashboard/DashboardHeader.vue";
import AnalyticsReport from "@/Components/Dashboard/AnalyticsReport.vue";
import UpcomingInterviews from "@/Components/Dashboard/UpcomingInterviews.vue";

const props = defineProps({
    upcomingInterviews: Array,
});
</script>

<template>
    <Head title="Admin Dashboard" />
    <div class="app-container">
        <Sidebar :user="$page.props.auth.user" />
        <div class="main-content">
            <DashboardHeader />

            <!-- Navigation Tabs -->
            <div class="flex border-b mb-4">
                <Link
                    href="/adminDashboard"
                    class="text-purple-500 mr-4 pb-2 border-b-2 border-purple-500"
                    >Overview</Link
                >
                <Link
                    href="/adminDashboardSchedule"
                    class="text-gray-700 mr-4 pb-2 border-b-2 border-transparent hover:border-purple-500"
                    >Schedule</Link
                >
            </div>

            <AnalyticsReport />
            <UpcomingInterviews :interviews="upcomingInterviews" />
        </div>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
/* Container utama sebagai flex container */
.app-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar posisinya fixed dengan width 16rem, 
   jangan override di sini, biarkan di Sidebar.vue */

/* Konten utama */
.main-content {
    flex: 1;
    margin-left: 16rem; /* geser ke kanan sesuai sidebar lebar */
    background-color: white;
    padding: 1.5rem 2rem;
    overflow-x: auto;
}

/* Responsive kecil */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 1rem;
    }
}
</style>
