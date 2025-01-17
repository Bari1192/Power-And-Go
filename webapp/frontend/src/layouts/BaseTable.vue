<template>
  <div class=" mx-auto w-4/5 border border-sky-500 rounded-3xl overflow-hidden">
    <table class="w-full">
      <thead>
        <tr class="text-white bg-sky-500 ">
          <th class="px-4 py-2 text-center">Rendszám</th>
          <th class="p-0 m-0 w-1 text-center">Autó ID (segédoszlop)</th>
          <th class="px-4 py-2 text-center">Futásteljesítménye</th>
          <th class="px-4 py-2 text-center">Töltöttségi szint</th>
          <th class="px-4 py-2 text-center">Töltési hatótáv</th>
          <th class="px-4 py-2 text-center">Állapotjelölése</th>
          <th class="px-4 py-2 text-center">Felszereltség</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="car in cars" :key="car.car_id" class="odd:bg-sky-800 even:bg-sky-950 text-center text-lg">
          <td class="py-2 text-lime-500 font-semibold ">
            <RouterLink class="hover:text-lime-300" :to="{ name: '/cars/[id]/', params: { id: car.car_id } }">
              {{ car.plate }}
            </RouterLink>
          </td>
          <td class="py-2 text-sky-300 font-semibold">{{ car.car_id }}</td>
          <td class="p-0 m-0 text-sky-300 font-semibold">{{ car.odometer }} km</td>
          <td class="p-0 m-0 text-sky-300 font-semibold">{{ car.power_percent }} %</td>
          <td class="p-0 m-0 text-sky-300 font-semibold">{{ car.estimated_range }}</td>
          <td class="p-0 m-0 text-sky-300 font-semibold">{{ car.status }}</td>
          <td class="p-0 m-0 text-sky-300 font-semibold">{{ car.equipment_class }} szintű</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    cars: Array,
  },
};
</script>
