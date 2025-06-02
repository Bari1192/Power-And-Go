<template>
  <BaseLayout>
    <div class="main">
      <div class="min-h-screen bg-slate-900/45 py-20">
        <!-- Fejléc -->
        <div class="max-w-7xl mx-auto mb-8">
          <div class="bg-gradient-to-b from-indigo-950 to-gray-950 p-6 rounded-2xl border border-emerald-600 shadow-2xl">
            <h1 class="text-4xl font-extrabold text-emerald-400 mb-4">Járműparkkezelés áttekintése</h1>
            <p class="text-slate-300 font-medium">
              Itt tekintheted meg a flotta teljes autókészletének állapotát és adatait.
            </p>
          </div>
        </div>

        <!-- Új autó hozzáadása -->
        <div>
          <NewFleetCar />
        </div>

        <!-- Flotta Katalógus -->
        <div class="container mx-auto max-w-7xl ">
          <div class="bg-gradient-to-b from-slate-700 to-slate-900 p-8 rounded-xl border border-emerald-600 shadow-lg">
            <h2
              class="text-3xl font-bold bg-gradient-to-b from-indigo-950 to-gray-900 py-8 rounded-lg text-center text-white">
              Járműkezelő - Flotta adatok</h2>

            <div
              class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-indigo-300/80 to-transparent my-8 rounded-full">
            </div>

            <div class="grid grid-cols-1 gap-8 max-w-6xl mx-auto">
              <BaseFleet v-for="fleet in fleets" :key="fleet.id" :id="fleet.id" :manufacturer="fleet.manufacturer"
                :carmodel="fleet.carmodel" :motor_power="fleet.motor_power" :top_speed="fleet.top_speed"
                :driving_range="fleet.driving_range" :tire_size="fleet.tire_size" @delete="torles" @update="frissites">
                <template #default>
                  <p class="text-slate-400"><b>Teljesítmény:</b> {{ fleet.motor_power }} kW</p>
                  <p class="text-slate-400"><b>Végsebesség:</b> {{ fleet.top_speed }} km/h</p>
                  <p class="text-slate-400"><b>Hatótáv:</b> {{ fleet.driving_range }} km</p>
                  <p class="text-slate-400"><b>Gumiméret:</b> {{ fleet.tire_size }}</p>
                </template>
              </BaseFleet>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<script setup>
import BaseLayout from '@layouts/BaseLayout.vue';
import NewFleetCar from '@pages/fleets/fleetcomponents/newFleetCar.vue';
import BaseFleet from '@pages/fleets/fleetcomponents/BaseFleetCards.vue';

import { onMounted, ref } from 'vue';
import { useFleetStore } from '@stores/FleetStore';
import { storeToRefs } from 'pinia';

const fleetStore = useFleetStore();
const { fleets } = storeToRefs(fleetStore);
const isLoading = ref(true);


onMounted(async () => {
  await fleetStore.getFleets();
  isLoading.value = false;
});

const frissites = (id, newData) => {
  isLoading.value = true;
  try {
    fleetStore.updateFleet(id, newData);
  } catch (error) {
    console.error('Hiba a frissítés során:', error);
  } finally {
    isLoading.value = false;
  }
};
const torles = (id) => {
  if (confirm("Biztosan törölni szeretné?")) {
    fleetStore.deleteFleet(id);
  } else {
    alert('A törlés visszavonva.');
  }
};
</script>

<style>
@import url('@assets/styles/MainBackgroundStyle.css');
</style>
