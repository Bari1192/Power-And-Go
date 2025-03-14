<template>
  <div class="card-body border-2 border-lime-500 rounded-md my-2 text-xl">
    <div class=" card-title bg-amber-50 py-1 px-2 text-lime-900 font-semibold flex items-center">
      {{ title }}
      <div class=" relative">
        <div v-if="isTooltipVisible"
          class="  absolute top-16 -right-36 transform translate-x-1/2 -translate-y-1/2 border-2 border-sky-700 bg-white text-sky-950 text-base rounded-lg shadow-lg px-2 py-1 w-48">
          <ul v-if="text === 1">
            <li>Elérhető, bérlésre kész.</li>
          </ul>
          <ul v-else-if="text === 2">
            <li>Az autót foglalás alatt</li>
          </ul>
          <ul v-else-if="text === 3">
            <li>Bérlés alatt</li>
          </ul>
          <ul v-else-if="text === 4">
            <li>accident miatt kivonva.</li>
          </ul>
          <ul v-else-if="text === 5">
            <li>Szervízre vár.</li>
          </ul>
          <ul v-else-if="text === 6">
            <li>Tisztításra kivonva a forgalomból.</li>
          </ul>
          <ul v-else-if="text === 7">
            <li>Kritikus töltöttségi szint.</li>
          </ul>
          <ul v-else>
            <li><strong>{{ text }}</strong> - Ismeretlen státusz.</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="italic card-text p-3 h-16 text-lime-50">
      <button @click="toggleTooltip"
        class="font-semibold rounded-full mt-1 w-6 h-6 flex items-center align-middle justify-center"
        :class="text === 7 ? ' bg-red-500 text-white font-semibold hover:text-white hover:bg-orange-500' : 'text-lime-900 bg-amber-50  hover:bg-orange-500 hover:text-white hover:font-bold'">
        {{ text }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    text: [Number, String],
  },
  data() {
    return {
      isTooltipVisible: false,
    };
  },
  methods: {
    toggleTooltip() {
      this.isTooltipVisible = !this.isTooltipVisible;
    },
  },
};
</script>

<style scoped>
.card-body {
  position: relative;
}
</style>