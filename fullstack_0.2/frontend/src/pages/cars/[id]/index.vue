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
        <button 
          @click="cleanreportOpen"
          label="hiba"
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
      <div v-if="cleanreport" 
      class="bg-sky-950 border-4 border-sky-700 rounded-lg mt-10 p-4">
      <BaseReportCard :carid="carRentHistory.car_id" :statusId="carRentHistory.status_id" :statusDescrip="carRentHistory.status_descrip"/>
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
          <BaseTooltipCard :title="'Állapota'" :text="car.status" />

          <BaseCard :title="'Becsült megtehető távolság'" :text="car.becs_tav + ' km'" />
          <BaseCard :title="'Akkumulátor töltöttsége'" :text="car.toltes_kw + ' kW'" />
          <BaseCard :title="'Töltöttségi állapota'" :text="car.toltes_szaz + ' %'" />
          <BaseCard :title="'Akkumulátor kapacitása'" :text="car.teljesitmeny + ' kW'" />
          <BaseCard :title="'Végsebesség'" :text="car.vegsebesseg + ' km/h'" />
          <BaseCard :title="'Maximális hatótáv egy töltéssel'" :text="car.hatotav + ' km'" />
          <BaseCard :title="'Aktuális futásteljesítménye'" :text="car.kilometerora + ' km'" />
          <BaseCard :title="'Gyártási éve'" :text="car.gyartasi_ev" />
        </div>
      </transition>
      <div ref="adatokAlja"></div>
      <h1 class="text-5xl font-bold text-sky-100 italic mt-10 mb-4"> Bejegyzések
        <button @click="noteDetails"
          class="flex items-center justify-centerfont-bold rounded-full"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;"
          :style="{ transform: noteOpen ? 'rotate(90deg)' : 'rotate(-90deg)' }"
          :class="rentFees.length ? 'bg-indigo-500 hover:bg-indigo-700 text-white' : 'bg-gray-500 text-gray-400'"

          >
          +
        </button>

      </h1>
      <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>
      <div v-if="!rentFees.length">
        <p class="text-gray-200 font-semibold italic px-2 text-lg">Ehhez az autóhoz nem tartozik bejegyzés.</p>
      </div>
      <transition name="fade-slide">
        <div v-if="noteOpen">
          <BaseCard v-for="ticket in rentFees" :key="ticket.id" class="h-64 text-2xl mb-4"
            :title="'Bejegyzés azonosítója: ' + ticket.id" :text="ticket.description">
            <div class="grid grid-cols-2 gap-4 my-4">
              <div class="w-2/3 text-white">
                <h2 class="font-semibold">Bejelentés kódja: </h2>
                <div class="w-2/5 border-b-4 border-lime-400 rounded-xl opacity-50"></div>
                <p class="mt-1 mb-3">{{ ticket.status_id }}</p>
                <h2 class="font-semibold">Bejelentés ideje</h2>
                <div class="w-2/5 border-b-4 border-lime-400 rounded-xl opacity-50"></div>
                <p class="mt-1 mb-3">{{ ticket.szamla_kelt }}</p>
              </div>
            </div>
          </BaseCard>
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
          <div class=" mx-auto border-4 border-indigo-900 rounded-3xl overflow-hidden">
            <table class="w-full">
              <thead>
                <tr class="text-lime-600 font-semibold bg-amber-50 border-b-8 border-lime-600">
                  <th class="py-2 text-center">Bérlő</th>
                  <th class="py-2 text-center">Nyitás</th>
                  <th class="py-2 text-center">Nyitás töltés</th>
                  <th class="py-2 text-center">Zárás</th>
                  <th class="py-2 text-center">Zárás töltés</th>
                  <th class="py-2 text-center">Megtett táv</th>
                  <th class="py-2 text-center">Végösszeg</th>
                  <th class="py-2 text-center">Kiállítva</th>

                </tr>
              </thead>
              <tbody class="p-8">
                <tr v-for="rent in carRentHistory.berlok" :key="rent.berles_id"
                  class="odd:bg-amber-50 even:bg-yellow-50 even:border-b-4 even:border-t-4 even:border-lime-400 text-center text-lg font-semibold text-sky-900">
                  <td class="mx-auto py-2"><router-link to=""> {{ rent.user }} </router-link></td>
                  <td class="mx-auto py-2">{{ rent.berles_kezd_datum + " " + rent.berles_kezd_ido }}</td>
                  <td class="mx-auto py-2">{{ rent.nyitas_szaz }} %</td>
                  <td class="mx-auto py-2">{{ rent.berles_veg_datum + " " + rent.berles_veg_ido }}</td>
                  <td class="mx-auto py-2">{{ rent.zaras_szaz }} %</td>
                  <td class="mx-auto py-2">{{ rent.megtett_tavolsag }} km</td>
                  <td class="mx-auto py-2">{{ rent.berles_osszeg }} Ft</td>
                  <td class="mx-auto py-2">{{ rent.szamla_kelt }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </transition>
      <div ref="rentHistoryBottom"></div>

      <h1 class="text-5xl font-bold text-sky-100 italic mt-10 mb-4">
        Büntetések
        <button @click="rentBillDetails" :disabled="!rentBillFees.length"
          class="flex items-center justify-center font-bold rounded-full"
          :class="rentBillFees.length ? 'bg-indigo-500 hover:bg-indigo-700 text-white' : 'bg-gray-500 text-gray-400 cursor-not-allowed'"
          :style="{
            transform: rentBillOpen ? 'rotate(90deg)' : 'rotate(-90deg)',
            backgroundColor: rentBillFees.length ? '#4F46E5' : '#6B7280',
            color: rentBillFees.length ? 'white' : '#a3a3a3',
            cursor: rentBillFees.length ? 'pointer' : 'default'
          }"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;">
          +
        </button>
      </h1>
      <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>
      <div v-if="!rentBillFees.length">
        <p class="text-gray-200 font-semibold italic px-2 text-lg">Ehhez az autóhoz nem tartozik egyetlen bírság sem</p>
      </div>
      <transition name="fade-slide">
        <div v-if="rentBillOpen">
          <div v-for="fine in rentBillFees" :key="fine.szamla_azon">
            <BaseCard class="cursor-pointer" :class="rentBillDetailsStates[fine.szamla_azon] ? 'h-44' : 'h-10'"
              :title="fine.szamla_tipus === 'toltes_buntetes' ? 'Akkumulátor lemerítési & szállítási pótdíj - ' + fine.osszeg : fine.szamla_tipus"
              @click="toggleBillDetails(fine.szamla_azon)">
              <div class="cursor-default grid grid-cols-3 gap-2" v-if="rentBillDetailsStates[fine.szamla_azon]">
                <p><b>Számla sorszáma:</b> {{ fine.szamla_azon }}</p>
                <p><b>Összege:</b> {{ fine.osszeg }} Ft</p>
                <p><b>Levezetett út:</b> {{ fine.megtett_tavolsag }} km</p>
                <p><b>Parkolási idő:</b> {{ fine.parkolasi_perc }} perc</p>
                <p><b>Vezetési idő:</b> {{ fine.vezetesi_perc }} perc</p>
                <p><b>Bérlés kezdete:</b> {{ fine.berles_kezd_datum }} {{ fine.berles_kezd_ido }}</p>
                <p><b>Bérlés vége:</b> {{ fine.berles_veg_datum }} {{ fine.berles_veg_ido }}</p>
                <p> <b>Kiállítva:</b> {{ fine.szamla_kelt }}</p>
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
import BaseTooltipCard from '@layouts/BaseTooltipCard.vue';
import BaseCard from '@layouts/BaseCard.vue'
import BaseLayout from "@layouts/BaseLayout.vue";
import BaseReportCard from '@layouts/BaseReportCard.vue';
import { nextTick } from 'vue';

export default {
  components: {
    BaseReportCard,
    BaseTooltipCard,
    BaseCard,
    BaseLayout,
  },
  data() {
    return {
      car: {},
      rentFees: [],
      carRentHistory: [],
      rentBillFees: [],
      carOpen: false,
      noteOpen: false,
      rentHistoryOpen: false,
      rentBillOpen: false,
      rentBillDetailsStates: {},
      isTooltipVisible: false,
      cleanreport:false,
    }
  },
  async mounted() {
    const response = await http.get(`/cars/${this.$route.params.id}`);
    this.car = response.data.data;

    const ticketresponse = await http.get(`/cars/${this.$route.params.id}/tickets`);
    this.rentFees = ticketresponse.data.data;

    const rentResponse = await http.get(`/cars/${this.$route.params.id}/renthistory`);
    this.carRentHistory = rentResponse.data.data;

    const feesResponse = await http.get(`/bills/${this.$route.params.id}/fees`);
    this.rentBillFees = feesResponse.data.data;
    this.rentBillFees.forEach((fine) => {
      this.$set(this.rentBillDetailsStates, fine.szamla_azon, false);
    });
  },
  methods: {
    toggleTooltip() {
      this.isTooltipVisible = !this.isTooltipVisible;
    },

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
    },

    toggleBillDetails(szamla_azon) {
      this.rentBillDetailsStates[szamla_azon] =
        !this.rentBillDetailsStates[szamla_azon];
    },
    async cleanreportOpen(){
      this.cleanreport=!this.cleanreport;
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