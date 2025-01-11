# NewJobForm.vue
<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

// Form data
const jobTitle = ref('')
const jobDescription = ref('')
const additionalQuestions = ref(['', ''])
const salaryMin = ref('')
const salaryMax = ref('')
const salaryRanges = ref([{ min: '', max: '' }])

// Methods
const addQuestion = () => {
  additionalQuestions.value.push('')
}

const addSalaryRange = () => {
  salaryRanges.value.push({ min: '', max: '' })
}

const submitJob = () => {
  // Handle job submission
  console.log({
    jobTitle: jobTitle.value,
    jobDescription: jobDescription.value,
    additionalQuestions: additionalQuestions.value,
    salaryRanges: salaryRanges.value
  })
}
</script>

<template>
  <Head title="New Job" />
  <div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-1/5 bg-blue-100 p-4 flex flex-col justify-between">
      <div>
        <div class="flex items-center mb-8">
          <img src="/api/placeholder/40/40" alt="Company Logo" class="mr-2" />
          <div>
            <h1 class="text-lg font-bold">Nuansa</h1>
            <p class="text-sm">CONSULTANT</p>
          </div>
        </div>
        <nav>
          <ul>
            <li class="mb-4">
              <Link href="/dashboard" class="flex items-center text-white bg-purple-500 p-2 rounded">
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
      </div>
      <div class="flex items-center">
        <img src="/api/placeholder/40/40" alt="User Avatar" class="mr-2 rounded-full" />
        <p>John Doe</p>
      </div>
    </div>

    <!-- Main Content -->
    <div class="w-4/5 p-8">
      <div class="flex justify-between items-center mb-8">
        <button class="text-xl">
          <i class="fas fa-arrow-left"></i>
        </button>
        <h2 class="text-xl font-bold">New job</h2>
        <button @click="submitJob" class="bg-purple-500 text-white px-4 py-2 rounded">
          Post job
        </button>
      </div>
      <div class="flex">
        <!-- Left Column -->
        <div class="w-1/2 pr-4">
          <div class="mb-4">
            <label class="block mb-2">Job Title</label>
            <input 
              v-model="jobTitle"
              type="text" 
              class="w-full p-2 border rounded"
            />
          </div>
          <div>
            <label class="block mb-2">Job Description</label>
            <textarea 
              v-model="jobDescription"
              class="w-full p-2 border rounded h-32"
            ></textarea>
          </div>
        </div>

        <!-- Right Column -->
        <div class="w-1/2 pl-4">
          <div class="mb-8">
            <h3 class="font-bold mb-4">Additional Question</h3>
            <div v-for="(question, index) in additionalQuestions" :key="index">
              <input 
                v-model="additionalQuestions[index]"
                type="text"
                placeholder="Lorem Ipsum"
                class="w-full p-2 border rounded mb-2"
              />
            </div>
            <button 
              @click="addQuestion"
              class="bg-purple-500 text-white px-4 py-2 rounded w-full"
            >
              + Add Question
            </button>
          </div>
          <div>
            <h3 class="font-bold mb-4">Salary Range</h3>
            <div v-for="(range, index) in salaryRanges" :key="index">
              <div class="flex items-center mb-2">
                <input 
                  v-model="range.min"
                  type="text"
                  placeholder="500.000"
                  class="w-full p-2 border rounded mr-2"
                />
                <input 
                  v-model="range.max"
                  type="text"
                  placeholder="1.000.000"
                  class="w-full p-2 border rounded"
                />
              </div>
            </div>
            <button 
              @click="addSalaryRange"
              class="bg-purple-500 text-white px-4 py-2 rounded w-full"
            >
              + Add Salary
            </button>
          </div>
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