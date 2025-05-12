<template>
  <BaseLayout>
    <NewFleetCar />
    <div class="mx-auto  bg-lime-100/90 border-4 rounded-2xl border-green-500/50 lg:max-w-[1100px] px-4 mb-20">
      <p style="font-family: Montserrat, sans-serif;"
        class="text-center tracking-wide text-5xl font-semibold text-green-600 mt-8 mb-4 capitalize">
        A flotta jelenlegi katalógusa
      </p>
      <div class="m-auto d-flex justify-center border-b-4 border-sky-300 w-2/3"></div>
      <div class="flex flex-wrap my-12">
        <div v-for="fleet in fleets" :key="fleet.id" class="w-1/2 px-3 mb-3 cursor-pointer">
          <BaseFleet :id="fleet.id" :manufacturer="fleet.manufacturer" :carmodel="fleet.carmodel"
            :motor_power="fleet.motor_power" :top_speed="fleet.top_speed" :driving_range="fleet.driving_range"
            :tire_size="fleet.tire_size" @delete="torles" @update="frissites">
            <p><b>Teljesítmény:</b> <b>{{ fleet.motor_power }}</b> kW</p>
            <p><b>Végsebesség:</b> {{ fleet.top_speed }}</p>
            <p><b>Hatótáv:</b> {{ fleet.driving_range }}</p>
            <p><b>Gumiméret: </b>{{ fleet.tire_size }}</p>
          </BaseFleet>
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
