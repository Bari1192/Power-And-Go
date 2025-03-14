<template>
  <div class="card-body sm:min-h-full border-2 border-lime-500 rounded-md my-2 overflow-hidden ">
    <div class="card-title md:min-h-18 lg:min-h-16 lg:max-h-20 bg-cyan-800 border-b-2 border-lime-600 py-1 text-xl px-2 text-white font-bold flex items-center">
      {{ title }}
    </div>
    <div class="card-text sm:min-h-18 lg:min-h-26 font-semibold text-xl p-3 text-white">
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
