<template>
  <div class="card-body border-2 border-lime-500 rounded-md my-2 overflow-hidden text-lg">
    <div class="card-title bg-lime-100 px-2 text-lime-600 font-semibold">
      {{ title }}
    </div>
    <div class="card-text p-3 text-sky-100">
      {{ text }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: [String, Number],
    text: [String, Number], // Elfogad string és szám típusokat is
  },
}
</script>
