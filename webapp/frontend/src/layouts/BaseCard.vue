<template>
  <div
    class="card-container bg-slate-900 rounded-xl shadow-md border border-emerald-500/20 overflow-hidden hover:shadow-cyan-400/30 transition-shadow duration-300">
    <!-- Card Header -->
    <div
      class="bg-gradient-to-r from-emerald-700/75 to-emerald-500/40 px-4 py-3 text-lg font-bold text-white flex items-center justify-between">
      <span>{{ title }}</span>
      <span class="text-slate-100 text-sm opacity-80">{{ subtitle }}</span>
    </div>

    <!-- Card Body -->
    <div class="bg-gray-800 p-5 text-slate-200">
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
    required: true
  },
  subtitle: {
    type: [String, Number],
    default: ''
  }
});
</script>

<style>
.card-container:hover {
  transform: translateY(-3px);
}

.card-container {
  transition: transform 0.25s;
}
</style>