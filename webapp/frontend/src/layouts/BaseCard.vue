<template>
  <div
    class="card-container bg-slate-700 rounded-xl border border-slate-500/20 overflow-hidden" style="font-family: 'Nunito','Arial';">
    <div
      class="bg-gradient-to-r from-purple-900 to-purple-700/85 px-4 py-1 text-lg font-bold text-slate-300 flex items-center">
      <span>{{ title }}</span>
      <span class="text-slate-100 text-sm opacity-80">{{ subtitle }}</span>
    </div>
    <div class="bg-gray-700 p-5 text-slate-200">
      <div class="text-xl font-semibold text-emerald-100 flex items-center">
        {{ text }}
      </div>
      <slot />
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: [String, Number],
    required: true
  },
  text: {
    type: [String, Number],
    default: ''
  },
  subtitle: {
    type: [String, Number],
    default: ''
  }
});
</script>
