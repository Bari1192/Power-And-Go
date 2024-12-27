<template>
    <div class="card-body border-2 border-lime-500 rounded-md my-2 text-lg">
      <div class="card-title bg-amber-50 py-1 px-2 text-lime-600 font-semibold flex items-center">
        {{ title }}
        <div class="relative">
          <div
            v-if="isTooltipVisible"
            class="absolute top-16 -right-36 transform translate-x-1/2 -translate-y-1/2 bg-gray-600 bg-opacity-80 text-gray-50 text-sm rounded-lg shadow-lg p-2 w-48"
          >
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
              <li>Az autó meghibásodott.</li>
            </ul>
            <ul v-else-if="text === 5">
              <li>Tisztításra kivonva a forgalomból.</li>
            </ul>
            <ul v-else-if="text === 6">
              <li>Kritikus töltöttségi szint.</li>
            </ul>
            <ul v-else>
              <li><strong>{{ text }}</strong> - Ismeretlen státusz.</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="card-text p-3 h-16 text-lime-50">
        <button
            @click="toggleTooltip"
            class="font-semibold rounded-full mt-1 w-6 h-6 flex items-center align-middle justify-center"
            :class="text === 6 ? 'bg-red-500 text-white hover:text-white hover:bg-red-600' : 'text-lime-900 bg-amber-100  hover:bg-amber-200 hover:font-bold'"
            >
          {{ text }}
          </button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      title: String,
      text: Number, 
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
  