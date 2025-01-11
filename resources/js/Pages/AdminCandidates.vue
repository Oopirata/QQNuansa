# CandidatesDashboard.vue
<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

// Search functionality
const searchQuery = ref('')

// Filters data
const filters = {
  jobTitles: ref([
    { id: 1, name: 'Psychologist Assistant', color: 'green' },
    { id: 2, name: 'HRD', color: 'blue' }
  ]),
  degrees: ref([
    { id: 1, name: 'Bachelor', color: 'pink' },
    { id: 2, name: 'Master', color: 'purple' }
  ])
}

// Candidates data
const candidates = ref([
  { id: 1, name: 'Muhammad Mirza Faiz Rabbani', location: 'Semarang', jobTitle: 'HRD' },
  { id: 2, name: 'Bintang Syafrian Rizal', location: 'Pekalongan', jobTitle: 'Psychologist Assistant' },
  { id: 3, name: 'Hanif Herofa', location: 'Jakarta', jobTitle: 'HRD' },
  { id: 4, name: 'Raka Maulana Yusuf', location: 'Rembang', jobTitle: 'HRD' },
  { id: 5, name: 'Awang Pratama Putra Mulya', location: 'Pekalongan', jobTitle: 'HRD' },
  { id: 6, name: 'Dul Samsi', location: 'Pekalongan', jobTitle: 'HRD' }
])

// Navigation state
const isSubMenuOpen = ref(true)

const toggleSubMenu = () => {
  isSubMenuOpen.value = !isSubMenuOpen.value
}
</script>

<template>
  <Head title="Candidates Dashboard" />
  <div class="flex h-screen">
    <!-- Sidebar -->
    <div class="bg-blue-100 w-64 p-4 flex flex-col">
      <div class="flex items-center mb-8">
        <img src="/api/placeholder/40/40" alt="Company Logo" class="w-10 h-10 mr-2" />
        <div>
          <h1 class="text-lg font-semibold">Nuansa</h1>
          <p class="text-sm text-gray-600">CONSULTANT</p>
        </div>
      </div>
      <nav class="flex-1">
        <ul>
          <li class="mb-4">
            <Link href="/dashboard" class="flex items-center text-gray-600 hover:text-gray-900">
              <i class="fas fa-tachometer-alt mr-2"></i>
              Dashboard
            </Link>
          </li>
          <li class="mb-4">
            <button 
              @click="toggleSubMenu"
              class="flex items-center w-full text-white bg-purple-500 p-2 rounded"
            >
              <i class="fas fa-users mr-2"></i>
              Candidates
              <i :class="['fas ml-auto', isSubMenuOpen ? 'fa-chevron-down' : 'fa-chevron-right']"></i>
            </button>
            <ul v-if="isSubMenuOpen" class="ml-6 mt-2">
              <li v-for="item in ['New', 'Screened', 'Interview', 'Rejected']"
                  :key="item"
                  class="mb-2">
                <Link :href="'/candidates/' + item.toLowerCase()"
                      class="text-gray-600 hover:text-gray-900">
                  {{ item }}
                </Link>
              </li>
            </ul>
          </li>
          <li class="mb-4">
            <Link href="/email" class="flex items-center text-gray-600 hover:text-gray-900">
              <i class="fas fa-envelope mr-2"></i>
              E-mail
            </Link>
          </li>
        </ul>
      </nav>
      <div class="mt-auto flex items-center">
        <img src="/api/placeholder/40/40" alt="User Avatar" class="w-10 h-10 rounded-full mr-2" />
        <div>
          <p class="text-gray-600">John Doe</p>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">
          Candidates
          <span class="text-gray-500">{{ candidates.length }}</span>
        </h2>
        <div class="relative">
          <input 
            v-model="searchQuery"
            type="text"
            placeholder="Search"
            class="border rounded p-2 pl-8"
          />
          <i class="fas fa-search absolute left-2 top-2 text-gray-500"></i>
        </div>
      </div>

      <div class="flex">
        <!-- Filters -->
        <div class="w-1/4 pr-4">
          <h3 class="text-xl font-semibold mb-4">Filters</h3>
          
          <!-- Job Title Filters -->
          <div class="mb-6">
            <h4 class="font-semibold mb-2">Job Title</h4>
            <div class="flex items-center mb-2">
              <span v-for="job in filters.jobTitles" 
                    :key="job.id"
                    :class="`bg-${job.color}-200 text-${job.color}-800 px-2 py-1 rounded-full text-sm mr-2`">
                {{ job.name }}
              </span>
            </div>
            <button class="text-gray-500">
              <i class="fas fa-plus"></i>
            </button>
          </div>

          <!-- Degree Filters -->
          <div class="mb-6">
            <h4 class="font-semibold mb-2">Degree</h4>
            <div class="flex items-center mb-2">
              <span v-for="degree in filters.degrees" 
                    :key="degree.id"
                    :class="`bg-${degree.color}-200 text-${degree.color}-800 px-2 py-1 rounded-full text-sm mr-2`">
                {{ degree.name }}
              </span>
            </div>
            <button class="text-gray-500">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>

        <!-- Candidates List -->
        <div class="w-3/4 bg-white rounded-lg shadow p-4">
          <table class="w-full">
            <thead>
              <tr class="text-left border-b">
                <th class="py-2">Name</th>
                <th class="py-2">Location</th>
                <th class="py-2">Job Title</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="candidate in candidates" 
                  :key="candidate.id"
                  class="border-b">
                <td class="py-2">{{ candidate.name }}</td>
                <td class="py-2">{{ candidate.location }}</td>
                <td class="py-2" :class="{ 'font-semibold': candidate.jobTitle === 'Psychologist Assistant' }">
                  {{ candidate.jobTitle }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
:deep(*) {
  font-family: 'Kaisei Opti', sans-serif;
}
</style>