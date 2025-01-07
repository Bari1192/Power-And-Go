<template>
  <div class="card-body border-2 border-lime-500 rounded-md my-2 overflow-hidden text-xl">
    <div class="card-title bg-amber-50 py-1 px-2 text-lime-900 font-semibold flex items-center">
      {{ title }}
    </div>
    <div class="card-text p-3 h-16 text-white">
      {{ text }}
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: [String, Number],
    text: [String, Number], 
  },
}
</script>
