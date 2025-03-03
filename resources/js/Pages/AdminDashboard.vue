<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import DashboardHeader from "@/Components/Dashboard/DashboardHeader.vue";
import AnalyticsReport from "@/Components/Dashboard/AnalyticsReport.vue";
import UpcomingInterviews from "@/Components/Dashboard/UpcomingInterviews.vue";
</script>

<template>
    <Head title="Admin Dashboard" />
    <div class="bg-white ml-64">
        <div class="flex min-h-screen">
            <Sidebar :user="$page.props.auth.user" />

            <div class="flex-1 p-6">
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
                <UpcomingInterviews />
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
</style>
