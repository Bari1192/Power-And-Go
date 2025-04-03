<template>
  <BaseLayout>
    <div v-if="car" class="container p-8 rounded-2xl bg-sky-700 shadow-2xl shadow-lime-800 mt-20 mb-40 w-full sm:w-11/12 
    lg:w-3/5 mx-auto border-4 border-lime-200 border-opacity-35">
      <!-- Fejléc rész -->
      <div
        class="m-auto w-4/5 py-6 d-flex justify-center my-10 sm:w-full border-4 rounded-2xl border-sky-300 dark:font-semibold shadow-md shadow-sky-400">
        <div class="text-center grid grid-cols-3">
          <h1 class="text-3xl md:text-4xl xl:text-5xl text-sky-100 border-r-4 border-sky-500 italic">
            {{ car.manufacturer }}
          </h1>
          <h1 class="text-3xl md:text-4xl xl:text-5xl text-lime-300 border-x-4 border-sky-500">
            {{ car.plate }}
          </h1>
          <h1 class="text-3xl md:text-4xl xl:text-5xl text-sky-100 border-l-4 border-sky-500 italic">
            {{ car.carmodel }}
          </h1>
        </div>
      </div>

      <CarReportButtons @toggle="toggleSection" />

      <!-- Tisztasági jelentés -->
      <div v-if="cleanreport" class="bg-sky-950 border-4 border-sky-700 rounded-lg mt-10 p-4">
        <CleanReportCard :carId="car.car_id" :lastRenter="carRentHistory?.renters?.[0]?.user"
          @submit-success="handleFormSubmit" />
      </div>

      <!-- RONGÁLÁS BEJELENTÉSI MODEL -->
      <div v-if="demageReport">
        <component :is="currentModel" :car-id="car.car_id" @submit-success="submitHandler" />
      </div>

      <!-- ACCIDENT BEJELENTÉSI KOMPONENS -->
      <div v-if="accidentReport">
        <CarAccidentReport :lastRenter="carRentHistory?.renters?.[0]?.user" @submit="submitAccidentReport" />
      </div>

      <!-- BÜNTETÉS KÉSZÍTÉSI KOMPONENS -->
      <div v-if="carManualFines">
        <ManualFines :carRentHistory="carRentHistory" @submit="submitManualFines" />
      </div>

      <!-- Jármű adatok -->
      <h1 class="text-5xl font-bold text-sky-50 italic mb-4" :style="{
        marginTop: accidentReport ? '6rem' : (demageReport ? '6rem' : '3rem')
      }">
        Jármű adatai
        <button @click="cardetails"
          class="flex items-center justify-center bg-indigo-500 text-white font-bold rounded-full hover:bg-indigo-700"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;"
          :style="{
            transform: carOpen ? 'rotate(90deg)' : 'rotate(-90deg)'
          }">
          +
        </button>
      </h1>
      <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>

      <!-- Jármű adatok részletek -->
      <transition name="fade-slide">
        <div v-if="carOpen" class="grid grid-cols-1 sm:grid-cols-2 sm:gap-3 lg:grid-cols-3 lg:gap-6 gap-2">
          <BaseCard :title="'Utolsó rögzített bejegyzés'" :text="latestTicket?.status_descrip" />
          <BaseCard :title="'Becsült megtehető távolság'" :text="`${car.estimated_range} km-re elegendő töltés.`" />
          <BaseCard :title="'Akkumulátor töltöttsége'" :text="`${car.power_kw} kW`" />
          <BaseCard :title="'Töltöttségi állapota'" :text="`${car.power_percent} %`" />
          <BaseCard :title="'Akkumulátor kapacitása'" :text="`${car.motor_power} kW`" />
          <BaseCard :title="'Utoljára bérelve'" :text="formattedLastRent" />
          <BaseCard :title="'Maximális hatótáv egy töltéssel'" :text="`${car.driving_range} km`" />
          <BaseCard :title="'Aktuális futásteljesítménye'" :text="`${car.odometer} km`" />
          <BaseCard :title="'Gyártási éve'" :text="car.manufactured" />
        </div>
      </transition>
      <div ref="adatokAlja"></div>

      <!-- Bejegyzések -->
      <h1 class="text-5xl font-bold text-sky-50 italic mt-10 mb-4">
        Bejegyzések
        <button @click="noteDetails" class="flex items-center justify-center font-bold rounded-full"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;"
          :style="{ transform: noteOpen ? 'rotate(90deg)' : 'rotate(-90deg)' }"
          :class="rentFees?.length ? 'bg-indigo-500 hover:bg-indigo-700 text-white' : 'bg-gray-500 text-gray-400'">
          +
        </button>
      </h1>
      <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>

      <div v-if="!rentFees?.length">
        <p class="text-gray-200 font-semibold italic px-2 text-lg">
          Ehhez az autóhoz nem tartozik bejegyzés.
        </p>
      </div>

      <transition name="fade-slide">
        <div v-if="noteOpen && rentFees?.length" class="lg:w-9/12 lg:mx-auto">
          <BaseCarTicketCard v-for="ticket in rentFees" :key="ticket.id" class="text-5xl mb-8"
            :title="`Bejegyzés azonosítója: ${ticket.id}`">
            <div class="grid grid-cols-3 gap-4 my-4">
              <!-- Első oszlop -->
              <div class="col-span-1 text-white">
                <h2 class="font-semibold">Bejelentés kódja:</h2>
                <div class="w-[160px] border-b-4 border-lime-400/90 rounded-xl mr-20"></div>
                <div class="bg-amber-100 w-fit h-fit px-2 rounded-full">
                  <p class="my-2 mb-3 text-red-600">{{ ticket.status_id }}</p>
                </div>

                <h2 class="font-semibold">Bejelentés ideje:</h2>
                <div class="w-[150px] border-b-4 border-lime-400 rounded-xl"></div>
                <div class="bg-amber-200 w-fit h-fit rounded-md">
                  <p class="m-3 text-red-700/85">{{ ticket.created_at }}</p>
                </div>
              </div>
              <!--Második-->
              <div class="col-span-2 text-white bg-slate-700 rounded-2xl p-4">
                <h2 class="font-semibold text-lime-400 underline underline-offset-4">
                  A bejelentés tárgya:
                </h2>
                <p class="mt-1 pl-1 mb-3">{{ ticket.status_descrip }}</p>
                <h2 class="font-semibold text-lime-400 underline underline-offset-4">
                  A bejelentés részletei:
                </h2>
                <p class="mt-1 pl-1 mb-3">{{ ticket.admin_description }}</p>
              </div>
            </div>
          </BaseCarTicketCard>
        </div>
      </transition>
      <div ref="noteBottom"></div>

      <h1 class="text-5xl font-bold text-sky-50 italic mt-10 mb-4">
        Bérlési előzmények
        <button @click="rentHistoryDetails"
          class="flex items-center justify-center bg-indigo-500 text-white font-bold rounded-full hover:bg-indigo-700"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;"
          :style="{ transform: rentHistoryOpen ? 'rotate(90deg)' : 'rotate(-90deg)' }">
          +
        </button>
      </h1>
      <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>

      <transition name="fade-slide">
        <div v-if="rentHistoryOpen && carRentHistory?.renters?.length">
          <div class="mx-auto border-4 border-indigo-900 rounded-3xl overflow-hidden">
            <table class="w-full">
              <thead>
                <tr class="text-indigo-600 font-semibold bg-indigo-50 border-b-8 border-indigo-600 text-lg">
                  <th class="py-2 text-center">Bérlő</th>
                  <th class="py-2 text-center">Zárás</th>
                  <th class="py-2 text-center">Zárás töltés</th>
                  <th class="py-2 text-center">Megtett táv</th>
                  <th class="py-2 text-center">Végösszeg</th>
                  <th class="py-2 text-center">Kiállítva</th>
                </tr>
              </thead>
              <tbody class="p-8">
                <tr v-for="rent in carRentHistory.renters" :key="rent.rent_id"
                  class="odd:bg-indigo-50 even:bg-indigo-100 bg-opacity-10 even:border-b-4 even:border-t-4 even:border-indigo-400 text-center text-lg font-semibold text-sky-900">
                  <td class="mx-auto py-2">
                    <router-link :to="`/users/${rent.user}`">{{ rent.user }}</router-link>
                  </td>
                  <td class="mx-auto py-2">{{ rent.rent_close }}</td>
                  <td class="mx-auto py-2">{{ rent.end_percent }} %</td>
                  <td class="mx-auto py-2">{{ rent.distance }} km</td>
                  <td class="mx-auto py-2">{{ rent.rental_cost }} Ft</td>
                  <td class="mx-auto py-2">{{ rent.invoice_date }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </transition>
      <div ref="rentHistoryBottom"></div>

      <div v-if="!rentBillFees || rentBillFees.length === 0 || !rentBillFees.some(fine => fine.id && fine.total_cost)"
        class="text-gray-200 font-semibold italic px-2 text-lg">
        <h1 class="text-5xl font-bold text-sky-50 italic mt-10 mb-4">
          Büntetések
          <button disabled
            class="flex items-center justify-center font-bold rounded-full bg-indigo-600  text-gray-400 cursor-not-allowed"
            style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center;">
            +
          </button>
        </h1>
        <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>
      </div>

      <div v-else>
        <h1 class="text-5xl font-bold text-sky-50 italic mt-10 mb-4">
          Büntetések
          <button @click="rentBillDetails"
            class="flex items-center justify-center font-bold rounded-full bg-indigo-500 hover:bg-indigo-700 text-white"
            :style="{
              transform: rentBillOpen ? 'rotate(90deg)' : 'rotate(-90deg)'
            }"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;">
          +
          </button>
        </h1>

        <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>

        <transition name="fade-slide">
          <div v-if="rentBillOpen && rentBillFees?.length">
            <div v-for="fine in rentBillFees" :key="fine.id">
              <BaseFineCard class="cursor-pointer"
                :title="fine.fine_types === 'charging_penalty' ? `Akkumulátor lemerítési & szállítási pótdíj - ${fine.total_cost}` : fine.bill_type"
                @click="toggleBillDetails(fine.id)">
                <template v-if="rentBillDetailsStates[fine.id]">
                  <div class="grid grid-cols-4 gap-4 min-h-[200px]">
                    <div>
                      <p class="pt-2 font-semibold">Számla sorszáma:</p>
                      <i class="text-lime-400">{{ fine.id }}</i>
                    </div>
                    <div class="text-center">
                      <p class="pt-2 font-semibold ">Kiállítva:</p>
                      <i class="text-lime-400">{{ fine.invoice_date }}</i>
                    </div>
                    <div class="text-center">
                      <p class="pt-2 font-semibold">Bérlés kezdete:</p>
                      <i class="text-lime-400">{{ fine.rent_start }}</i>
                    </div>
                    <div class="text-center">
                      <p class="pt-2 font-semibold">Összege:</p>
                      <i class="text-lime-400">{{ fine.total_cost }} Ft</i>
                    </div>
                    <div>
                      <p class="pt-2 font-semibold">Bérlés vége:</p>
                      <i class="text-lime-400">{{ fine.rent_close }}</i>
                    </div>
                    <div class="text-center">
                      <p class="pt-2 font-semibold">Értesítő email állapota:</p>
                      <span :class="{
                        'bg-amber-50 text-red-600 font-bold italic px-2 rounded-2xl': fine.email_sent === 'no',
                        'bg-amber-50 text-lime-700 font-bold italic px-2 rounded-2xl': fine.email_sent === 'yes'
                      }">
                        {{ fine.email_sent === 'no' ? 'Nem lett elküldve' : 'Elküldve' }}
                      </span>
                    </div>
                    <div class="text-center">
                      <p class="pt-2 font-semibold">Személy neve:</p>
                      <i class="text-lime-400">{{ fine.person }}</i>
                    </div>
                    <div class="text-center">
                      <p class="pt-2 font-semibold">Felhasználó email:</p>
                      <i class="text-lime-400">{{ fine.email }}</i>
                    </div>
                  </div>
                </template>
              </BaseFineCard>
            </div>
          </div>
        </transition>
      </div>
      <div ref="rentBillsBottom"></div>
    </div>

    <!-- Loading állapot -->
    <div v-else class="flex justify-center items-center h-screen">
      <p class="text-xl text-gray-500">Adatok betöltése...</p>
    </div>
  </BaseLayout>
</template>

<script setup>
import BaseCard from '@layouts/BaseCard.vue';
import BaseCarTicketCard from '@layouts/BaseCarTicketCard.vue';
import BaseFineCard from '@layouts/BaseFineCard.vue';
import BaseLayout from "@layouts/BaseLayout.vue";
import EupModel from "@layouts/carmodels/EupModel.vue";
import CitigoModel from "@layouts/carmodels/CitigoModel.vue";
import KangooModel from "@layouts/carmodels/KangooModel.vue";
import VivaroModel from "@layouts/carmodels/VivaroModel.vue";
import KiaNiroModel from "@layouts/carmodels/KiaNiroModel.vue";
import CleanReportCard from '@layouts/reportforms/fastReport/CleanReportCard.vue';
import CarAccidentReport from '@layouts/reportforms/caraccidents/CarAccidentReport.vue';
import ManualFines from '@layouts/reportforms/CarManualFines/ManualFines.vue';
import CarReportButtons from '@layouts/reportforms/CarReportButtons.vue';
import { http } from '@utils/http'

import { onMounted, ref, computed } from 'vue';
import { useCarStore } from '@/stores/carStore';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';

const route = useRoute();
const carStore = useCarStore();

const {
  car,
  carAllTickets: rentFees,
  carLatestTicket: latestTicket,
  carRentHistory,
  carFees: rentBillFees,
} = storeToRefs(carStore);

const carOpen = ref(false);
const noteOpen = ref(false);
const rentHistoryOpen = ref(false);
const rentBillOpen = ref(false);
const cleanreport = ref(false);
const demageReport = ref(false);
const accidentReport = ref(false);
const carManualFines = ref(false);
const rentBillDetailsStates = ref({});
const isSubmitting = ref(false);


const currentModel = computed(() => {
  const manufacturerAlapjanModel = {
    'VW': EupModel,
    'Skoda': CitigoModel,
    'Renault': KangooModel,
    'Opel': VivaroModel,
    'KIA': KiaNiroModel
  };
  return manufacturerAlapjanModel[car.value?.manufacturer] || null;
});


const formattedLastRent = computed(() => {
  const rentClose = carRentHistory.value?.renters?.[0]?.rent_close;
  if (!rentClose) return 'Nincs dátum';

  return new Date(rentClose).toLocaleString('hu-HU', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  });
});

// Metódusok
const toggleSection = (section) => {
  const sections = ['cleanreport', 'demageReport', 'accidentReport', 'carManualFines']
  sections.forEach(s => {
    eval(`${s}.value = s === section ? !${s}.value : false`)
  })
}
const toggleBillDetails = (id) => {
  rentBillDetailsStates.value[id] = !rentBillDetailsStates.value[id]
}

const submitAccidentReport = async (data) => {
  if (isSubmitting.value) return;
  isSubmitting.value = true;

  try {
    if (!confirm(`Balesetet fog bejelenteni a ${car.value.plate} ${car.value.carmodel} modellű autóra. Megerősíti?`)) {
      return;
    }

    const CarAccidentData = {
      description: data.description,
      car_id: car.value.car_id,
      status_id: 4
    };

    const CarAccidentRefreshCarData = {
      plate: car.value.plate,
      power_percent: parseFloat(car.value.power_percent),
      power_kw: parseFloat(car.value.power_kw),
      estimated_range: parseFloat(car.value.estimated_range),
      status: 4
    };

    await Promise.all([
      http.post('/tickets', CarAccidentData),
      http.put(`/cars/${car.value.car_id}`, CarAccidentRefreshCarData)
    ]);

    alert("A baleseti bejelentés elküldve, a jármű adatai frissültek!");
  } catch (error) {
    console.error('Hiba történt:', error);
  } finally {
    isSubmitting.value = false;
  }
};
const submitManualFines = (data) => {
  console.log('Manualfinesadatok:', data)
}
const cardetails = () => {
  carOpen.value = !carOpen.value
  if (carOpen.value) {
    nextTick(() => {
      const adatokAlja = document.querySelector('#adatokAlja')
      adatokAlja.scrollIntoView({ behavior: 'smooth' })
    })
  }
}
const noteDetails = () => {
  noteOpen.value = !noteOpen.value
  if (noteOpen.value) {
    nextTick(() => {
      const noteBottom = document.querySelector('#noteBottom')
      noteBottom.scrollIntoView({ behavior: 'smooth' })
    })
  }
};
const rentHistoryDetails = () => {
  rentHistoryOpen.value = !rentHistoryOpen.value
  if (rentHistoryOpen.value) {
    nextTick(() => {
      const rentHistoryBottom = document.querySelector('#rentHistoryBottom')
      rentHistoryBottom.scrollIntoView({ behavior: 'smooth' })
    })
  }
};

const rentBillDetails = () => {
  rentBillOpen.value = !rentBillOpen.value
};
onMounted(async () => {
  const carId = route.params.id
  if (carId) {
    await carStore.getCarDetails(carId)
  }
});
</script>
