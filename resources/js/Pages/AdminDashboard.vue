<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
    <Head title="Admin Dashboard" />
    <div class="bg-white">
      <div class="flex h-screen">
        <!-- Sidebar Section -->
        <div class="bg-blue-100 w-64 p-6 flex flex-col justify-between">
          <div>
            <!-- Company Logo and Name -->
            <div class="flex items-center mb-8">
              <img src="/images/logo.png" alt="Company Logo" class="w-10 h-10 mr-2">
              <span class="text-xl font-bold">Nuansa Consultant</span>
            </div>
            
            <!-- Navigation Menu -->
            <nav>
              <Link href="/dashboard" class="flex items-center mb-4 text-purple-600">
                <i class="fas fa-tachometer-alt mr-2"></i>
                <span>Dashboard</span>
              </Link>
              <Link href="/candidates" class="flex items-center mb-4 text-gray-700">
                <i class="fas fa-users mr-2"></i>
                <span>Candidates</span>
              </Link>
              <Link href="/email" class="flex items-center text-gray-700">
                <i class="fas fa-envelope mr-2"></i>
                <span>E-mail</span>
              </Link>
            </nav>
          </div>
          
          <!-- User Profile Section -->
          <div class="flex items-center">
            <img src="/images/default-avatar.png" alt="User Avatar" class="w-10 h-10 rounded-full mr-2">
            <span>Admin</span>
          </div>
        </div>

        <!-- Main Content Section -->
        <div class="flex-1 p-6">
          <!-- Header with Actions -->
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Dashboard</h1>
            <div class="flex space-x-4">
              <button class="bg-gray-700 text-white px-4 py-2 rounded">
                Visit Website
              </button>
              <button class="bg-purple-600 text-white px-4 py-2 rounded">
                + New job
              </button>
            </div>
          </div>

          <!-- Navigation Tabs -->
          <div class="border-b mb-6">
            <ul class="flex space-x-8">
              <li class="pb-2 border-b-2 border-purple-600">
                <a class="text-purple-600" href="#">Overview</a>
              </li>
              <li class="pb-2">
                <a class="text-gray-700" href="#">Schedule</a>
              </li>
            </ul>
          </div>

          <!-- Analytics Report Section -->
          <div class="mb-6">
            <h2 class="text-xl font-bold mb-4">Analytics Report</h2>
            <div class="bg-white p-6 rounded-lg shadow flex justify-between">
              <!-- New Candidates Stats -->
              <div>
                <h3 class="text-gray-700">New candidates</h3>
                <p class="text-3xl font-bold">100</p>
                <p class="text-gray-500">In last 7 days</p>
              </div>
              <!-- Employees Stats -->
              <div>
                <h3 class="text-gray-700">Employees</h3>
                <p class="text-3xl font-bold">100</p>
                <p class="text-gray-500">In last 7 days</p>
              </div>
            </div>
          </div>

          <!-- Upcoming Interviews Section -->
          <div>
            <h2 class="text-xl font-bold mb-4">Upcoming Interviews</h2>
            <div class="grid grid-cols-2 gap-4">
              <!-- Interview Card Component -->
              <div v-for="n in 6" :key="n" class="bg-white p-4 rounded-lg shadow flex items-center">
                <!-- Date Display -->
                <div class="text-center mr-4">
                  <p class="text-2xl font-bold">25</p>
                  <p class="text-gray-500">Tue</p>
                </div>
                <!-- Interview Details -->
                <div>
                  <p class="font-bold">Ivan Serhin (10:30 - 12:00)</p>
                  <p class="text-gray-500">Product Designer phone screening</p>
                </div>
                <!-- Action Icons -->
                <div class="ml-auto flex space-x-2">
                  <i class="fas fa-link text-gray-500"></i>
                  <i class="fas fa-ellipsis-h text-gray-500"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<style scoped>
/* Menerapkan font ke seluruh komponen */
:deep(*) {
    font-family: 'Kaisei Opti', sans-serif;
}
</style>