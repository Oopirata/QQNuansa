<script setup>
defineProps({
  jobVacancy: {
    type: String,
    required: true
  },
  status: {
    type: String,
    default: 'New'
  },
  // Add props to control which buttons to show
  showDisqualify: {
    type: Boolean,
    default: true
  },
  showMoveToScreened: {
    type: Boolean,
    default: false
  },
  showMoveToInterview: {
    type: Boolean,
    default: false
  },
  showMoveToHired: {
    type: Boolean,
    default: false
  },
  // Custom button props
  customButton: {
    type: Object,
    default: () => ({
      show: false,
      label: '',
      color: 'bg-blue-500 hover:bg-blue-600',
    })
  },
  // Custom names for buttons
  disqualifyLabel: {
    type: String,
    default: 'Disqualify'
  },
  moveToScreenedLabel: {
    type: String,
    default: 'Move to Screened'
  },
  moveToInterviewLabel: {
    type: String,
    default: 'Move to Interview'
  },
  moveToHiredLabel: {
    type: String,
    default: 'Move to Hired'
  }
});

const emit = defineEmits([
  'disqualify', 
  'moveToScreened', 
  'moveToInterview', 
  'moveToHired', 
  'customAction',
  'prevPage', 
  'nextPage'
]);

const handleDisqualify = () => {
  emit('disqualify');
};

const handleMoveToScreened = () => {
  emit('moveToScreened');
};

const handleMoveToInterview = () => {
  emit('moveToInterview');
};

const handleMoveToHired = () => {
  emit('moveToHired');
};

const handleCustomAction = () => {
  emit('customAction');
};

const goToPrevPage = () => {
  emit('prevPage');
};

const goToNextPage = () => {
  emit('nextPage');
};
</script>

<template>
  <div class="flex justify-between items-center mb-6">
    <div class="flex items-center">
      <button class="mr-4">
        <i class="fas fa-arrow-left"></i>
      </button>
      <div>
        <h1 class="text-2xl font-semibold">
          {{ jobVacancy }}
        </h1>
        <p class="text-gray-600">{{ status }}</p>
      </div>
    </div>
    <div class="flex items-center">
      <div class="flex justify-end space-x-4 mr-10">
        <!-- Disqualify button (almost always shown) -->
        <button
          v-if="showDisqualify"
          @click="handleDisqualify"
          class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
        >
          {{ disqualifyLabel }}
        </button>
        
        <!-- Move to Screened button (shown on New status) -->
        <button
          v-if="showMoveToScreened"
          @click="handleMoveToScreened"
          class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600"
        >
          {{ moveToScreenedLabel }}
        </button>
        
        <!-- Move to Interview button (shown on Screened status) -->
        <button
          v-if="showMoveToInterview"
          @click="handleMoveToInterview"
          class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
        >
          {{ moveToInterviewLabel }}
        </button>
        
        <!-- Move to Hired button (shown on Interview status) -->
        <button
          v-if="showMoveToHired"
          @click="handleMoveToHired"
          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
          {{ moveToHiredLabel }}
        </button>
        
        <!-- Custom action button -->
        <button
          v-if="customButton.show"
          @click="handleCustomAction"
          class="px-4 py-2 text-white rounded"
          :class="customButton.color"
        >
          {{ customButton.label }}
        </button>
      </div>
    </div>
  </div>
</template>