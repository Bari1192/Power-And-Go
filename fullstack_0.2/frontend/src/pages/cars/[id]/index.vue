<template>
  <BaseLayout>
    <div class="container mt-20 mb-20 w-2/3 mx-auto">

      <div
        class="m-auto py-6 d-flex justify-center my-10 w-3/4 border-4 rounded-2xl border-sky-300 dark:font-semibold shadow-md shadow-sky-400">
        <div class="text-center grid grid-cols-3">
          <h1 class="text-5xl text-sky-100 border-r-4 border-sky-500 italic"> {{ car.gyarto }}</h1>
          <h1 class="text-5xl text-lime-300 border-x-4 border-sky-500"> {{ car.rendszam }}</h1>
          <h1 class="text-5xl text-sky-100 border-l-4 border-sky-500 italic"> {{ car.tipus }}</h1>
        </div>
      </div>
      <h1 class="text-5xl font-bold text-sky-100 italic mt-20 mb-4">Új Bejelentés</h1>
      <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>
      <div class="flex justify-center my-8 text-center text-xl space-x-8">
        <button label="hiba"
          class="bg-teal-500 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-teal-400 text-white font-semibold">
          Tisztaság 🚬
        </button>
        <button label="hiba"
          class="bg-orange-500 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-orange-600 hove:text-white text-white font-semibold">
          Meghibásodás 🔧
        </button>
        <button label="hiba"
          class="bg-rose-600 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-rose-700 text-sky-100 font-semibold">
          Baleset ⛐
        </button>
        <button label="hiba"
          class="bg-indigo-500 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-indigo-400 text-sky-100 font-semibold">
          Rongálás 🔨
        </button>
        <button label="hiba"
          class="bg-red-700 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-red-600 text-sky-100 font-semibold">
          Büntetés 📃
        </button>
      </div>

      <h1 class="text-5xl font-bold text-sky-100 italic mt-20 mb-4"> Jármű adatai
        <button @click="cardetails"
          class="flex items-center justify-center bg-indigo-500 text-white font-bold rounded-full hover:bg-indigo-700"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;"
          :style="{ transform: carOpen ? 'rotate(90deg)' : 'rotate(-90deg)' }">
          +
        </button>
      </h1>
      <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>
      <transition name="fade-slide">
        <div class="grid grid-cols-3 gap-6" v-if="carOpen">
          <BaseCard :title="'Állapota'" :text="car.status" />
          <BaseCard :title="'Becsült megtehető távolság'" :text="car.becs_tav + ' km'" />
          <BaseCard :title="'Akkumulátor töltöttsége'" :text="car.toltes_kw + ' kW'" />
          <BaseCard :title="'Töltöttségi állapota'" :text="car.toltes_szaz + ' %'" />
          <BaseCard :title="'Akkumulátor kapacitása'" :text="car.fleet.teljesitmeny + ' kW'" />
          <BaseCard :title="'Végsebesség'" :text="car.fleet.vegsebesseg + ' km/h'" />
          <BaseCard :title="'Maximális hatótáv egy töltéssel'" :text="car.fleet.hatotav + ' km'" />
          <BaseCard :title="'Aktuális futásteljesítménye'" :text="car.kilometerora + ' km'" />
          <BaseCard :title="'Gyártási éve'" :text="car.gyartasi_ev" />
        </div>
      </transition>
      <div ref="adatokAlja"></div>


      <h1 class="text-5xl font-bold text-sky-100 italic mt-10 mb-4"> Bejegyzések
        <button @click="noteDetails"
          class="flex items-center justify-center bg-indigo-500 text-white font-bold rounded-full hover:bg-indigo-700"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;"
          :style="{ transform: noteOpen ? 'rotate(90deg)' : 'rotate(-90deg)' }">
          +
        </button>
      </h1>
      <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>

      <transition name="fade-slide">
        <div v-if="noteOpen">
          <BaseCard class="h-40 text-2xl" :title="'Megjegyzések / Részletek'" :text="car.status.status_descrip" />
        </div>
      </transition>
      <div ref="noteBottom"></div>

      <h1 class="text-5xl font-bold text-sky-100 italic mt-10 mb-4"> Bérlési előzmények
        <button @click="rentHistoryDetails"
          class="flex items-center justify-center bg-indigo-500 text-white font-bold rounded-full hover:bg-indigo-700"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;"
          :style="{ transform: rentHistoryOpen ? 'rotate(90deg)' : 'rotate(-90deg)' }">
          +
        </button>
      </h1>
      <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>

      <transition name="fade-slide">
        <div v-if="rentHistoryOpen">
          <div class=" mx-auto border-4 border-indigo-600 rounded-3xl overflow-hidden">
            <table class="w-full">
              <thead>
                <tr class="text-lime-600 font-semibold bg-amber-50 border-b-8 border-lime-600">
                  <th class="py-2 text-center">Bérlő</th>
                  <th class="py-2 text-center">Jelszó azonosító</th>
                  <th class="py-2 text-center">Bérlő telefonszáma</th>
                  <th class="py-2 text-center">Bérlés összege</th>
                  <th class="py-2 text-center">Megtett távolság</th>
                  <th class="py-2 text-center">Parkolási idő</th>
                  <th class="py-2 text-center">Bérlés kezdete</th>
                  <th class="py-2 text-center">Bérlés vége</th>
                  <th class="py-2 text-center">Számla kiállítva</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="berlo in car.berlok" :key="berlo.index"
                  class="odd:bg-amber-50 even:bg-yellow-50 even:border-b-4 even:border-t-4 even:border-lime-400 text-center text-lg font-semibold text-sky-900">
                  <td class="pl-2 mx-auto">{{ berlo.username }}</td>
                  <td class="pl-2 mx-auto">{{ berlo.jelszo_2_4 }}</td>
                  <td class="pl-2 mx-auto">{{ berlo.telefon }}</td>
                  <td class="pl-2 mx-auto">{{ berlo.renthistory.berles_osszeg }} Ft</td>
                  <td class="pl-2 mx-auto">{{ berlo.renthistory.megtett_tavolsag }} km</td>
                  <td class="pl-2 mx-auto">{{ berlo.renthistory.parkolas }} p</td>
                  <td class="pl-2 mx-auto">{{ berlo.renthistory.berles_kezd_datum }} {{ berlo.renthistory.berles_kezd_ido }}</td>
                  <td class="pl-2 mx-auto">{{ berlo.renthistory.berles_veg_datum }} {{ berlo.renthistory.berles_veg_ido }}</td>
                  <td class="pl-2 mx-auto">{{ berlo.renthistory.szamla_kelt }} </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </transition>
      <div ref="rentHistoryBottom"></div>




      <h1 class="text-5xl font-bold text-sky-100 italic mt-10 mb-4">
        Büntetések
        <button @click="rentBillDetails" :disabled="!carBills.length"
          class="flex items-center justify-center font-bold rounded-full"
          :class="carBills.length ? 'bg-indigo-500 hover:bg-indigo-700 text-white' : 'bg-gray-500 text-gray-400 cursor-not-allowed'"
          :style="{
            transform: rentBillOpen ? 'rotate(90deg)' : 'rotate(-90deg)',
            backgroundColor: carBills.length ? '#4F46E5' : '#6B7280',
            color: carBills.length ? 'white' : '#a3a3a3',
            cursor: carBills.length ? 'pointer' : 'default'
          }"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;">
          +
        </button>
      </h1>
      <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>
      <div v-if="!carBills.length">
        <p class="text-gray-200 font-semibold italic px-2 text-lg">Ehhez az autóhoz nem tartozik egyetlen bírság sem</p>
      </div>
      <transition name="fade-slide">
        <div v-if="rentBillOpen">
          <div v-for="fine in carBills" :key="fine.szamla_azon">
            <BaseCard class="cursor-pointer" :class="rentBillDetailsOpenStates[fine.szamla_azon] ? 'h-44' : 'h-10'"
              :title="fine.szamla_tipus === 'toltes_buntetes' ? 'Akkumulátor lemerítési & szállítási pótdíj - ' + fine.osszeg : fine.szamla_tipus"
              @click="toggleBillDetails(fine.szamla_azon)">
              <!-- Részletek megjelenítése kattintásra -->
              <div class="cursor-default grid grid-cols-3 gap-2" v-if="rentBillDetailsOpenStates[fine.szamla_azon]">
                <p><b>Számla sorszáma:</b> {{ fine.szamla_azon }}</p>
                <p><b>Összege:</b> {{ fine.osszeg }} Ft</p>
                <p><b>Levezetett út:</b> {{ fine.megtett_tavolsag }} km</p>
                <p><b>Parkolási idő: </b>{{ fine.parkolasi_perc }} perc</p>
                <p><b>Vezetési idő:</b> {{ fine.vezetesi_perc }} perc</p>
                <p><b>Bérlés kezdete:</b> {{ fine.berles_kezd_datum }} {{ fine.berles_kezd_ido }}</p>
                <p><b>Bérlés vége: </b> {{ fine.berles_veg_datum }} {{ fine.berles_veg_ido }}</p>
                <p> <b>Kiállítva: </b>{{ fine.szamla_kelt }}</p>
                <p>
                  <b>Számla állapota: </b>
                  <span
                    :style="fine.szamla_status === 'pending' ? 'color:orange; font-weight:bold;font-style:italic;' : ''">
                    {{ fine.szamla_status }}
                  </span>
                </p>
              </div>
            </BaseCard>
          </div>
        </div>
      </transition>

      <div ref="rentBillsBottom"></div>
    </div>
  </BaseLayout>
</template>

<script>
import { http } from '@utils/http'
import BaseCard from '@layouts/BaseCard.vue'
import BaseLayout from "@layouts/BaseLayout.vue";
import { nextTick } from 'vue';

export default {
  components: {
    BaseCard,
    BaseLayout,
  },
  data() {
    return {
      car: {},
      carBills: [],
      carRentBills: [],
      carOpen: false,
      noteOpen: false,
      rentHistoryOpen: false,
      rentBillOpen: false,
      rentBillDetailsOpenStates: {},
    }
  },
  async mounted() {
    const response = await http.get(`/cars/${this.$route.params.id}`);
    this.car = response.data.data;

    const billsresponse = await http.get(`/renthistories/filterCarHistory/toltes_buntetes/${this.$route.params.id}`);
    this.carBills = billsresponse.data.data;

    const rentresponse = await http.get(`/renthistories/filterCarHistory/berles/${this.$route.params.id}`);
    this.carRentBills = rentresponse.data.data;

  },
  methods: {
    async cardetails() {
      this.carOpen = !this.carOpen;
      if (this.carOpen) {
        await nextTick();
        this.$refs.adatokAlja.scrollIntoView({ behavior: 'smooth' });
      }
    },
    async noteDetails() {
      this.noteOpen = !this.noteOpen;
      if (this.noteOpen) {
        await nextTick();
        this.$refs.noteBottom.scrollIntoView({ behavior: 'smooth' });
      }
    },
    async rentHistoryDetails() {
      this.rentHistoryOpen = !this.rentHistoryOpen;
      if (this.rentHistoryOpen) {
        await nextTick();
        this.$refs.rentHistoryBottom.scrollIntoView({ behavior: 'smooth' });
      }
    },
    async rentBillDetails() {
      this.rentBillOpen = !this.rentBillOpen;

      // Inicializáljuk az állapotokat minden számlához
      if (this.rentBillOpen) {
        this.carBills.forEach((bill) => {
          this.$set(this.rentBillDetailsOpenStates, bill.szamla_azon, false);
        });
      }
    },
    toggleBillDetails(szamla_azon) {
      // Az adott számla nyitási állapotának váltása
      this.rentBillDetailsOpenStates[szamla_azon] =
        !this.rentBillDetailsOpenStates[szamla_azon];
    },
  }
}

</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 3s ease-in-out;
}

.fade-slide-enter,
.fade-slide-leave-to {
  transform: translateY(-30px);
  opacity: 0;
  transition: all 1s ease-in-out;
}
</style>