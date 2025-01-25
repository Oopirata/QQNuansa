<script setup>
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />
    <div class="flex min-h-screen">
        <!-- Fixed Sidebar -->
        <aside class="fixed inset-y-0 left-0 w-64 bg-blue-100">
            <div class="h-full p-6 flex flex-col justify-between">
                <div>
                    <!-- Company Logo and Name -->
                    <div class="flex items-center mb-8">
                        <img
                            src="/images/QQ crop.png"
                            alt="Company Logo"
                            class="w-20 h-16 mr-2"
                        />
                        <img
                            src="/images/Nuansa crop.png"
                            alt="Company Logo"
                            class="w-24 h-8 mr-2 ml-3"
                        />
                    </div>

                    <!-- Navigation Menu -->
                    <nav>
                        <Link
                            href="/dashboard"
                            class="flex items-center mb-4 text-purple-600"
                        >
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            <span>Dashboard</span>
                        </Link>
                        <div class="mb-4">
                            <button
                                @click="toggleSubMenu"
                                class="flex items-center w-full text-gray-700"
                            >
                                <i class="fas fa-users mr-2"></i>
                                Candidates
                                <i
                                    :class="[
                                        'fas ml-auto',
                                        isSubMenuOpen
                                            ? 'fa-chevron-down'
                                            : 'fa-chevron-right',
                                    ]"
                                ></i>
                            </button>
                            <ul v-if="isSubMenuOpen" class="ml-6 mt-2">
                                <li
                                    v-for="item in [
                                        {
                                            name: 'New',
                                            route: 'AdminNewCandidates',
                                        },
                                        {
                                            name: 'Screened',
                                            route: 'candidates.screened',
                                        },
                                        {
                                            name: 'Interview',
                                            route: 'candidates.interview',
                                        },
                                        {
                                            name: 'Rejected',
                                            route: 'candidates.rejected',
                                        },
                                    ]"
                                    :key="item.name"
                                    class="mb-2"
                                >
                                    <Link
                                        :href="route(item.route)"
                                        class="text-gray-600 hover:text-gray-900"
                                    >
                                        {{ item.name }}
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <Link
                            href="/email"
                            class="flex items-center text-gray-700"
                        >
                            <i class="fas fa-envelope mr-2"></i>
                            <span>E-mail</span>
                        </Link>
                    </nav>
                </div>

                <!-- User Profile Section -->
                <div class="flex items-center">
                    <img
                        src="/images/profile.png"
                        alt="User Avatar"
                        class="w-10 h-10 rounded-full mr-2"
                    />
                    <span>Admin</span>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Dashboard</h1>
                    <div class="flex space-x-4">
                        <a href="/companyprofile">
                            <button
                                class="bg-gray-700 text-white px-4 py-2 rounded"
                            >
                                Visit Website
                            </button>
                        </a>
                        <a href="/adminNewJob">
                            <button
                                class="bg-purple-600 text-white px-4 py-2 rounded"
                            >
                                + New job
                            </button>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="flex border-b mb-4">
                        <Link
                            href="/adminDashboard"
                            class="text-gray-700 mr-4 pb-2 border-b-2 border-transparent hover:border-purple-500"
                            >Overview</Link
                        >
                        <Link
                            href="/adminDashboardSchedule"
                            class="text-purple-500 pb-2 border-b-2 border-purple-500"
                            >Schedule</Link
                        >
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">November 2020</h2>
                            <div class="flex items-center">
                                <button
                                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2"
                                >
                                    Week
                                </button>
                                <button
                                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2"
                                >
                                    Month
                                </button>
                                <button
                                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded"
                                >
                                    Year
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-8 gap-4 text-center text-sm">
                            <div>
                                <div class="h-16"></div>
                                <div
                                    v-for="hour in [
                                        '08 AM',
                                        '09 AM',
                                        '10 AM',
                                        '11 AM',
                                        '12 PM',
                                        '01 PM',
                                        '02 PM',
                                        '03 PM',
                                        '04 PM',
                                        '05 PM',
                                        '06 PM',
                                        '07 PM',
                                    ]"
                                    :key="hour"
                                    class="h-16"
                                >
                                    {{ hour }}
                                </div>
                            </div>
                            <template
                                v-for="(day, index) in [
                                    'Monday',
                                    'Tuesday',
                                    'Wednesday',
                                    'Thursday',
                                    'Friday',
                                    'Saturday',
                                    'Sunday',
                                ]"
                                :key="day"
                            >
                                <div :class="{ relative: day === 'Friday' }">
                                    {{ day }}<br />
                                    <template v-if="day === 'Friday'">
                                        <span
                                            class="bg-blue-500 text-white rounded-full px-2 py-1 mt-4"
                                            >27</span
                                        >
                                    </template>
                                    <template v-else>
                                        {{ 23 + index }}
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div class="grid grid-cols-8 gap-4 mt-4 text-sm">
                            <template v-for="col in 8" :key="col">
                                <div class="col-span-1">
                                    <template v-for="row in 12" :key="row">
                                        <div class="h-16 border-b"></div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div class="mt-4">
                            <div class="bg-white p-4 rounded shadow">
                                <h3 class="text-lg font-semibold">
                                    Courtney Henry - 1st interview
                                </h3>
                                <p class="text-gray-600">
                                    Friday, November 27 11:00 - 12:00
                                </p>
                                <a
                                    href="https://room.us/j/9302274319?pwd"
                                    class="text-blue-500"
                                    >https://room.us/j/9302274319?pwd</a
                                >
                                <div class="mt-4">
                                    <p class="text-gray-600">2 Guests</p>
                                    <div class="flex items-center mt-2">
                                        <img
                                            src="/api/placeholder/40/40"
                                            alt="Guest 1"
                                            class="w-8 h-8 rounded-full mr-2"
                                        />
                                        <p>Marvin McKinney</p>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <img
                                            src="/api/placeholder/40/40"
                                            alt="Guest 2"
                                            class="w-8 h-8 rounded-full mr-2"
                                        />
                                        <p>Annette Black</p>
                                    </div>
                                </div>
                                <div class="mt-4 text-gray-600">
                                    <i class="fas fa-bell mr-2"></i>
                                    10 minutes before
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
</style>
