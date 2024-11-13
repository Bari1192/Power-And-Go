<template>
  <BaseLayout>
    <h1 class="text-cyan-300 text-center text-3xl mt-5">Admin Dashboard</h1>
    <div class="flex justify-center mx-auto mb-8">
      <div class="p-4 space-y-4 flex-row">
        <button
          @click="autokTablazat"
          class="bg-blue-500 hover:bg-blue-600 text-white px-4 p-2 mr-3 rounded-md transition-colors"
        >
          Autók listázása
        </button>
        <button
          @click="kategoriakTablazat"
          class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 mr-3 rounded-md transition-colors"
        >
          Kategóriák listázása
        </button>
        <button
          @click="szemelyekTablazat"
          class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 mr-3 rounded-md transition-colors"
        >
          Felszereltségek áttekintése
        </button>
        <button
          @click="szemelyekTablazat"
          class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 mr-3 rounded-md transition-colors"
        >
          Felhasználók
        </button>
        <button
          @click="szemelyesAdatokTablazat"
          class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 mr-3 rounded-md transition-colors"
        >
          Személyes adatok
        </button>
        <button
          @click="lezartBerlesekTablazat"
          class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 mr-3 rounded-md transition-colors"
        >
          Lezárt bérlések
        </button>
        <BaseTable
          v-if="kivalasztott"
          :rows="kivalasztott"
          :columns="kivalasztottOszlopAdatok"
          :itemsPerPage="50"
          :currentPage="currentPage"
          @updatePage="updatePage"
        />
      </div>
    </div>
  </BaseLayout>
</template>
<script>
import BaseLayout from "@layouts/BaseLayout.vue";
import BaseTable from "@components/layout/BaseTable.vue";
import { http } from "@utils/http.mjs";

export default {
  components: {
    BaseLayout,
    BaseTable,
  },

  data() {
    return {
      cars: [],
      carsCategory: [],
      kivalasztott: null,
      kivalasztottOszlopAdatok: [],
      currentPage: 1,
    };
  },
  methods: {
    async autokTablazat() {
      try {
        const response = await http.get("/cars");
        this.cars = response.data;
        this.kivalasztott = this.cars;
        this.kivalasztottOszlopAdatok = [
          { key: "Gyarto", ertek: "Gyártó" },
          { key: "Tipus", ertek: "Típus" },
          { key: "Teljesitmeny", ertek: "Teljesítmény (kW)" },
          { key: "Vegsebesseg", ertek: "Végsebesség (km/h)" },
          { key: "Gumimeret", ertek: "Gumiméret" },
          { key: "Hatótav", ertek: "Hatótáv (km)" },
          { key: "Rendszam", ertek: "Rendszám" },
          { key: "Gyartasi_ev", ertek: "Gyártási év" },
          { key: "Km_ora_allas", ertek: "Km óra állás" },
        ];
        this.currentPage = 1; // Visszaáll az első oldalra új adatok lekérésekor
      } catch (error) {
        alert("Hiba az adatok lekérése közben. Kérem próbálja újra később!");
      }
    },
    updatePage(page) {
      this.currentPage = page;
    },
    async kategoriakTablazat() {
      try {
        const response = await http.get("/car_category");
        this.carsCategory = response.data;
        this.kivalasztott = this.carsCategory;
        this.kivalasztottOszlopAdatok = [
          { key: "Rendszam", ertek: "Rendszám" },
          { key: "Tipus", ertek: "Típus" },
          { key: "Besorolas", ertek: "Besorolási kategória" },
        ];
        this.currentPage = 1;
      } catch (error) {
        alert("Hiba az adatok lekérése közben. Kérem próbálja újra később!");
      }
    },
    updatePage(page) {
      this.currentPage = page;
    },
  },
};
</script>
