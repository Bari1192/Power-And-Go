<template>
  <div class="card-body sm:min-h-full lg:h-full border-2 border-lime-400 rounded-md overflow-hidden ">
    <div class="card-title md:min-h-18 lg:min-h-16 lg:max-h-20 bg-emerald-400/65 border-b-2 border-emerald-300 text-xl px-2
     text-white font-bold flex items-center">
      {{ title }}
    </div>
    <div class="card-text sm:min-h-18 lg:min-h-full bg-green-500/35 font-semibold text-xl px-6 text-white/90">
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
