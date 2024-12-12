<template>
  <div class="card-body border border-sky-500 rounded-md my-2 overflow-hidden">
    <div class="card-title bg-sky-500 px-2 text-white">
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
    title: String,
    text: [String, Number], // Elfogad string és szám típusokat is
  },
}
</script>
