# CandidateDetails.vue
<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

const newEmail = ref('')
const isSubMenuOpen = ref(true)

// Candidate data
const candidate = ref({
  name: 'Cain Chana',
  location: 'Semarang',
  email: 'john.doe@gmail.com',
  phone: '+380 66 779 71 00',
  portfolio: 'dribbble.com/johndoe',
  avatar: '/api/placeholder/80/80',
  status: 'New',
  position: 'Psychologist Assistant'
})

// Screening questions
const screeningQuestions = ref([
  {
    question: "What is your expected monthly compensation?",
    answer: "32.000 hrn per month"
  },
  {
    question: "What is your current proficiency in German?",
    answer: "A1"
  },
  {
    question: "What\'s an interface?",
    answer: "To kick off the employee onboarding checklist, you need to prepare the relevant paperwork and information prior to the employee\'s first day. Start by recording the employee\'s basic information in the form fields below."
  }
])

// Email history
const emails = ref([
  {
    id: 1,
    sender: 'Kathryn Murphy',
    subject: 'Interview Results',
    time: '1 day ago',
    avatar: '/api/placeholder/40/40',
    content: [
      'Dear Cody,',
      'I am delighted to inform you that we would like to arrange an interview. Please let us know when you are available to come to our offices.',
      'Sincerely,\nKathryn Murphy'
    ]
  },
  {
    id: 2,
    sender: 'Kathryn Murphy',
    subject: 'Interview Results',
    time: '1 day ago',
    avatar: '/api/placeholder/40/40',
    content: [
      'Dear Cody,',
      'Please let us know when you are available to come to our offices.'
    ]
  }
])

const toggleSubMenu = () => {
  isSubMenuOpen.value = !isSubMenuOpen.value
}
</script>

<template>
  <Head :title="candidate.position" />
  <div class="flex h-screen">
    <!-- Sidebar Section -->
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
                    <Link
                        href="/candidates"
                        class="flex items-center mb-4 text-gray-700"
                    >
                        <i class="fas fa-users mr-2"></i>
                        <span>Candidates</span>
                    </Link>
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
    <div class="flex-1 p-8 ml-64">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-2xl font-semibold">{{ candidate.position }}</h1>
          <span class="text-gray-500">{{ candidate.status }}</span>
        </div>
        <div class="flex items-center">
          <button class="bg-red-500 text-white px-4 py-2 rounded mr-2">
            Disqualify
          </button>
          <button class="bg-purple-500 text-white px-4 py-2 rounded">
            Move to screened
          </button>
        </div>
      </div>

      <div class="flex">
        <!-- Candidate Details -->
        <div class="w-1/2 pr-8">
          <div class="flex items-center mb-4">
            <img :src="candidate.avatar" alt="Candidate Avatar" class="w-20 h-20 rounded-full mr-4" />
            <div>
              <h2 class="text-xl font-semibold">{{ candidate.name }}</h2>
              <span class="text-gray-500">
                <i class="fas fa-map-marker-alt"></i>
                {{ candidate.location }}
              </span>
            </div>
          </div>

          <h3 class="text-lg font-semibold mb-4">Details</h3>
          <div class="mb-4">
            <div class="flex items-center mb-2">
              <i class="fas fa-envelope mr-2"></i>
              <span>{{ candidate.email }}</span>
            </div>
            <div class="flex items-center mb-2">
              <i class="fas fa-phone mr-2"></i>
              <span>{{ candidate.phone }}</span>
            </div>
            <div class="flex items-center mb-2">
              <i class="fas fa-link mr-2"></i>
              <span>{{ candidate.portfolio }}</span>
            </div>
          </div>

          <h3 class="text-lg font-semibold mb-4">Screening questions</h3>
          <div v-for="(qa, index) in screeningQuestions" 
               :key="index" 
               class="mb-4">
            <p class="font-semibold">{{ qa.question }}</p>
            <p>{{ qa.answer }}</p>
          </div>
        </div>

        <!-- Emails -->
        <div class="w-1/2 pl-8">
          <h3 class="text-lg font-semibold mb-4">Emails</h3>
          <div class="mb-4">
            <input 
              v-model="newEmail"
              type="text"
              placeholder="Click to start composing a new email."
              class="w-full p-2 border rounded"
            />
          </div>
          
          <div v-for="email in emails" 
               :key="email.id" 
               class="mb-4 p-4 border rounded">
            <div class="flex items-center mb-2">
              <img :src="email.avatar" alt="Sender Avatar" class="w-10 h-10 rounded-full mr-2" />
              <div>
                <p class="font-semibold">{{ email.sender }}</p>
                <p class="text-gray-500">{{ email.subject }}</p>
              </div>
              <span class="ml-auto text-gray-500">{{ email.time }}</span>
            </div>
            <p v-for="(line, index) in email.content" 
               :key="index" 
               class="mb-2 whitespace-pre-line">
              {{ line }}
            </p>
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