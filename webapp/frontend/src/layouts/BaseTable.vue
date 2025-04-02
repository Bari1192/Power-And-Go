<template>
  <div class="mx-auto w-4/5 border border-sky-500 rounded-3xl overflow-hidden">
    <table class="w-full table-auto">
      <thead>
        <tr class="text-white bg-sky-500">
          <th class="px-4 py-2 text-center whitespace-nowrap">Rendszám</th>
          <th class="px-4 py-2 text-center">ID (segéd)</th>
          <th class="px-4 py-2 text-center whitespace-nowrap">Model</th>
          <th class="px-4 py-2 text-center whitespace-nowrap">Kilométeróra</th>
          <th class="px-4 py-2 text-center whitespace-nowrap">Töltöttség</th>
          <th class="px-4 py-2 text-center whitespace-nowrap">Hatótáv (km)</th>
          <th class="px-4 py-2 text-center whitespace-nowrap">Státusz</th>
          <th class="px-4 py-2 text-center whitespace-nowrap">Felszereltség</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="car in cars" :key="car.car_id" class="odd:bg-sky-800 even:bg-sky-950 text-center text-lg ">
          <td
            class="py-2 text-lime-500 hover:text-xl font-semibold min-w-max cursor-pointer transition-all duration-200 ease-in-out"
            @click="goToCar(car.car_id)">
            {{ car.plate }}
          </td>
          <td class="text-sky-300 font-semibold min-w-max">{{ car.car_id }}</td>
          <td class="text-sky-300 font-semibold min-w-max">{{ car.manufacturer + ' ' + car.carmodel }}</td>
          <td class="text-sky-300 font-semibold min-w-max">{{ car.odometer }} km</td>
          <td class="text-sky-300 font-semibold min-w-max">{{ car.power_percent }} %</td>
          <td class="text-sky-300 font-semibold min-w-max">{{ car.estimated_range }} km</td>

          <td :class="{
            'text-lime-400 font-semibold min-w-max': car.status_name === 'Szabad',
            'text-orange-800 font-semibold min-w-max': car.status_name === 'Kritikus töltés',
            'text-yellow-400 font-semibold min-w-max': car.status_name === 'Bérlés alatt',
            'text-sky-300 font-semibold min-w-max': car.status_name != 'Bérlés alatt' && car.status_name != 'Kritikus töltés' && car.status_name != 'Szabad'
          }">{{ car.status_name }}</td>
          <td class="text-sky-300 font-semibold min-w-max">{{ car.equipment_class }} szintű</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>


<script setup>
import { useRouter } from 'vue-router';

const props = defineProps({
  cars: {
    type: Array,
    required: true
  }
});

const router = useRouter();

const goToCar = (id) => {
  if (!id) {
    console.error('nem jó a car "id" értéke!');
    return;
  } else {
    router.push({ name: 'CarDetails', params: { id } });
    return id;
  }
};
</script>