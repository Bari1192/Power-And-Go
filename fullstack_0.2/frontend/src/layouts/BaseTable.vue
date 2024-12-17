<template>
  <div class=" mx-auto w-4/5 border border-sky-500 rounded-3xl overflow-hidden">
    <table class="w-full">
      <thead>
        <tr class="text-white bg-sky-500 ">
          <th class="px-4 py-2 text-center">Rendszám</th>
          <th class="p-0 m-0 w-1 text-center">Autó ID (segédoszlop)</th>
          <th class="px-2 py-2 text-center">Futásteljesítmény</th>
          <th class="px-2 py-2 text-center">Gyártási év</th>
          <th class="px-2 py-2 text-center">Felsz. bes.</th>
          <th class="px-2 py-2 text-center">flotta. bes.</th>
          <th class="px-2 py-2 text-center">kategoria</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="car in cars" :key="car.autok_id" class="odd:bg-sky-800 even:bg-sky-950 text-center text-lg">
          <td class="py-2 text-lime-500 font-semibold ">
            <RouterLink class="hover:text-lime-300" :to="{ name: '/cars/[id]/', params: { id: car.auto_id } }">
              {{ car.rendszam }}
            </RouterLink>
          </td>
          <td class="py-2 text-sky-300 font-semibold">{{ car.auto_id }}</td>
          <td class="p-0 m-0 text-sky-300 font-semibold">{{ car.km_allas }}</td>
          <td class="py-2 text-sky-300 font-semibold">{{ car.gyart_ev }}</td>
          <td class="py-2 text-sky-300 font-semibold">{{ car.felsz_id }}</td>
          <td class="py-2 text-sky-300 font-semibold">{{ car.flotta.flotta_id }}</td>
          <td class="py-2 text-sky-300 font-semibold">{{ car.kategoria }}</td>
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
