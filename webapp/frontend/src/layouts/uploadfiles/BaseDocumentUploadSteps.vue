<template>
    <div class="flex justify-between mb-6">
      <div
        v-for="(step, index) in documents"
        :key="index"
        :class="[
          'step-indicator',
          { active: currentStep === index },
          { required: step.required },
          { uploaded: step.uploaded }
        ]"
        class="text-sm cursor-not-allowed"
      >
        {{ step.name }} <!-- A doksi neve -->
      </div>
    </div>
  </template>
  
  <script setup>
  const props = defineProps({
    documents: {
      type: Array,
      required: true
    },
    currentStep: {
      type: Number,
      required: true
    }
  })
  </script>
  
  <style>
  .step-indicator {
    @apply flex items-center justify-center px-3 py-2 rounded-full bg-lime-800/25 border-2 border-lime-700/25 text-center mx-2;
  }
  
  .step-indicator.active {
    @apply bg-amber-50/30 border-2 text-white border-lime-700/65 font-medium p-3 text-center tracking-wider cursor-pointer;
  }
  </style>
  