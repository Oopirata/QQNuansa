<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Dashboard" />
  <div class="flex h-screen">
    <!-- Sidebar -->
    <div class="bg-blue-200 w-64 p-4 flex flex-col">
      <div class="flex items-center mb-8">
        <img src="/api/placeholder/40/40" alt="Company Logo" class="w-10 h-10 mr-2"/>
        <div>
          <h1 class="text-lg font-semibold">Nuansa</h1>
          <p class="text-sm">CONSULTANT</p>
        </div>
      </div>
      <nav class="flex-1">
        <ul>
          <li class="mb-4">
            <Link href="/dashboard" class="flex items-center text-white bg-blue-500 p-2 rounded">
              <i class="fas fa-tachometer-alt mr-2"></i>
              Dashboard
            </Link>
          </li>
          <li class="mb-4">
            <Link href="/candidates" class="flex items-center text-gray-700 p-2 rounded">
              <i class="fas fa-users mr-2"></i>
              Candidates
            </Link>
          </li>
          <li>
            <Link href="/email" class="flex items-center text-gray-700 p-2 rounded">
              <i class="fas fa-envelope mr-2"></i>
              E-mail
            </Link>
          </li>
        </ul>
      </nav>
      <div class="mt-auto flex items-center">
        <img src="/api/placeholder/40/40" alt="User Avatar" class="w-10 h-10 rounded-full mr-2"/>
        <p>John Doe</p>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-semibold">Dashboard</h1>
        <div>
          <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2">Visit Website</button>
          <button class="bg-purple-500 text-white px-4 py-2 rounded">+ New job</button>
        </div>
      </div>
      <div>
        <div class="flex border-b mb-4">
          <Link href="#" class="text-gray-700 mr-4 pb-2 border-b-2 border-transparent hover:border-purple-500">Overview</Link>
          <Link href="#" class="text-purple-500 pb-2 border-b-2 border-purple-500">Schedule</Link>
        </div>
        <div class="bg-white p-4 rounded shadow">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">November 2020</h2>
            <div class="flex items-center">
              <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2">Week</button>
              <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2">Month</button>
              <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Year</button>
            </div>
          </div>
          <div class="grid grid-cols-8 gap-4 text-center text-sm">
            <div>
              <div class="h-16"></div>
              <div v-for="hour in ['08 AM', '09 AM', '10 AM', '11 AM', '12 PM', '01 PM', '02 PM', '03 PM', '04 PM', '05 PM', '06 PM', '07 PM']" 
                   :key="hour" 
                   class="h-16">
                {{ hour }}
              </div>
            </div>
            <template v-for="(day, index) in ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']" 
                      :key="day">
              <div :class="{'relative': day === 'Friday'}">
                {{ day }}<br/>
                <template v-if="day === 'Friday'">
                  <span class="bg-blue-500 text-white rounded-full px-2 py-1">27</span>
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
              <h3 class="text-lg font-semibold">Courtney Henry - 1st interview</h3>
              <p class="text-gray-600">Friday, November 27 11:00 - 12:00</p>
              <a href="https://room.us/j/9302274319?pwd" class="text-blue-500">https://room.us/j/9302274319?pwd</a>
              <div class="mt-4">
                <p class="text-gray-600">2 Guests</p>
                <div class="flex items-center mt-2">
                  <img src="/api/placeholder/40/40" alt="Guest 1" class="w-8 h-8 rounded-full mr-2"/>
                  <p>Marvin McKinney</p>
                </div>
                <div class="flex items-center mt-2">
                  <img src="/api/placeholder/40/40" alt="Guest 2" class="w-8 h-8 rounded-full mr-2"/>
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
  </div>
</template>

<style scoped>
/* Apply font to entire component */
:deep(*) {
    font-family: 'Kaisei Opti', sans-serif;
}
</style>