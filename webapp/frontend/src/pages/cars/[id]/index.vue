<template>
  <BaseLayout>
    
    <div class="container mt-20 mb-40 w-full sm:w-11/12 lg:w-2/3 mx-auto">
      <div
        class="m-auto w-4/5 py-6 d-flex justify-center my-10 sm:w-full border-4 rounded-2xl border-sky-300 dark:font-semibold shadow-md shadow-sky-400">
        <div class="text-center grid grid-cols-3">
          <h1 class="text-3xl md:text-4xl xl:text-5xl text-sky-100 border-r-4 border-sky-500 italic"> {{
            car.manufacturer }} </h1>
          <h1 class="text-3xl md:text-4xl xl:text-5xl text-lime-300 border-x-4 border-sky-500"> {{ car.plate }} </h1>
          <h1 class="text-3xl md:text-4xl xl:text-5xl text-sky-100 border-l-4 border-sky-500 italic"> {{ car.carmodel }}
          </h1>
        </div>
      </div>

      <CarReportButtons @toggle="toggleSection" />

      <!-- TISZTASÁG BEJELENTÉSI MODEL -->
      <div v-if="cleanreport" class="bg-sky-950 border-4 border-sky-700 rounded-lg mt-10 p-4">
        <CleanReportCard :carId="car.car_id" :lastRenter="carRentHistory.renters[0]?.user"
          @submit-success="handleFormSubmit" />
      </div>

      <!-- RONGÁLÁS BEJELENTÉSI MODEL -->
      <div v-if="demageReport">
        <component :is="currentModel" :car-id="car.car_id" @submit-success="submitHandler" />
      </div>

      <!-- ACCIDENT BEJELENTÉSI KOMPONENS -->
      <div v-if="accidentReport">
        <CarAccidentReport :lastRenter="carRentHistory.renters[0].user" @submit="submitAccidentReport" />
      </div>

      <!-- BÜNTETÉS KÉSZÍTÉSI KOMPONENS -->
      <div v-if="carManualFines">
        <ManualFines :carRentHistory="carRentHistory" @submit="submitManualFines" />
      </div>

      <h1 class="text-5xl font-bold text-sky-100 italic mb-4" :style="{
        marginTop: accidentReport ? '6rem' : (demageReport ? '6rem' : '3rem')
      }"> Jármű adatai
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
      <transition name="fade-slide transition-all ease-in-out duration-300 ">
        <div class="grid grid-cols-1 sm:grid-cols-2 sm:gap-3 lg:grid-cols-3 lg:gap-6 gap-2" v-if="carOpen">
          <BaseCard :title="'Utolsó rögzített bejegyzés'" :text="latestTicket.status_descrip" />
          <BaseCard :title="'Becsült megtehető távolság'" :text="car.estimated_range + ' km-re elegendő töltés.'" />
          <BaseCard :title="'Akkumulátor töltöttsége'" :text="car.power_kw + ' kW'" />
          <BaseCard :title="'Töltöttségi állapota'" :text="car.power_percent + ' %'" />
          <BaseCard :title="'Akkumulátor kapacitása'" :text="car.motor_power + ' kW'" />
          <BaseCard :title="'Utoljára bérelve'" :text="formattedLastRent" />
          <BaseCard :title="'Maximális hatótáv egy töltéssel'" :text="car.driving_range + ' km'" />
          <BaseCard :title="'Aktuális futásteljesítménye'" :text="car.odometer + ' km'" />
          <BaseCard :title="'Gyártási éve'" :text="car.manufactured" />
        </div>
      </transition>
      <div ref="adatokAlja"></div>

      <h1 class="text-5xl font-bold text-sky-100 italic mt-10 mb-4"> Bejegyzések
        <button @click="noteDetails" class="flex items-center justify-centerfont-bold rounded-full"
          style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;"
          :style="{ transform: noteOpen ? 'rotate(90deg)' : 'rotate(-90deg)' }"
          :class="rentFees.length ? 'bg-indigo-500 hover:bg-indigo-700 text-white' : 'bg-gray-500 text-gray-400'">
          +
        </button>

      </h1>
      <div class=" w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>
      <div v-if="!rentFees.length">
        <p class="text-gray-200 font-semibold italic px-2 text-lg">Ehhez az autóhoz nem tartozik bejegyzés.</p>
      </div>
      <transition name="fade-slide">
        <div v-if="noteOpen">
          <BaseCard v-for="ticket in rentFees" :key="ticket.id" class="min-h-fit text-5xl mb-8"
            :title="'Bejegyzés azonosítója: ' + ticket.id">
            <div class="grid grid-cols-3 gap-4 my-4">
              <!--Első oszlop-->
              <div class="col-span-1 text-white">
                <h2 class="font-semibold">Bejelentés kódja:</h2>
                <div class="w-2/5 border-b-4 border-lime-400 rounded-xl opacity-50"></div>
                <p class="mt-1 mb-3">{{ ticket.status_id }}</p>

                <h2 class="font-semibold">Bejelentés ideje:</h2>
                <div class="w-2/5 border-b-4 border-lime-400 rounded-xl opacity-50"></div>
                <p class="mt-1 mb-3">{{ ticket.created_at }}</p>
              </div>

              <!--Második-->
              <div class="col-span-2 text-white bg-slate-700 rounded-2xl p-4">
                <h2 class="font-semibold text-lime-400 underline underline-offset-2">A bejelentés tárgya:</h2>
                <p class="mt-1 mb-3">{{ ticket.status_descrip }}</p>
                <h2 class="font-semibold text-lime-400 underline underline-offset-2">A bejelentés részletei:</h2>
                <p class="mt-1 mb-3">{{ ticket.admin_description }}</p>
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
                  <td class="mx-auto py-2"><router-link to=""> {{ rent.user }} </router-link></td>
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
          <div v-for="fine in rentBillFees" :key="fine.id">
            <BaseCard class="cursor-pointer"
              :class="rentBillDetailsStates[fine.id] ? 'min-h-full' : 'h-10 md:h-10 lg:h-14 xl:h-16'"
              :title="fine.bill_type === 'charging_penalty' ? 'Akkumulátor lemerítési & szállítási pótdíj - ' + fine.total_cost : fine.bill_type"
              @click="toggleBillDetails(fine.id)">
              <div class="cursor-default grid grid-cols-3 gap-2 mx-1" v-if="rentBillDetailsStates[fine.id]">
                <p><b>Számla sorszáma: </b><br><i class="text-lime-500">{{ fine.id }}</i></p>
                <p class="text-center"> <b>Kiállítva:</b> <br><i class="text-lime-500">{{ fine.invoice_date }}</i></p>
                <p class="text-right"><b>Bérlés kezdete:</b><br><i class="text-lime-500">{{ fine.rent_start }}</i></p>
                <p><b>Összege:</b> <br><i class="text-lime-500">{{ fine.total_cost }} Ft</i></p>
                <p class="text-center"><b>Bérlés vége:</b> <br><i class="text-lime-500">{{ fine.rent_close }}
                    {{ fine.rent_end_time }}</i></p>
                <p class="text-right">
                  <b>Számla állapota: </b>
                  <span
                    :style="fine.invoice_status === 'pending' ? 'color:orange; font-weight:bold;font-style:italic;' : ''">
                    <br>{{ fine.invoice_status }}
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
import EupModel from "@layouts/carmodels/EupModel.vue";
import CitigoModel from "@layouts/carmodels/CitigoModel.vue";
import KangooModel from "@layouts/carmodels/KangooModel.vue";
import VivaroModel from "@layouts/carmodels/VivaroModel.vue";
import KiaNiroModel from "@layouts/carmodels/KiaNiroModel.vue";
import CleanReportCard from '@layouts/reportforms/fastReport/CleanReportCard.vue';
import CarAccidentReport from '@layouts/reportforms/caraccidents/CarAccidentReport.vue';
import ManualFines from '@layouts/reportforms/CarManualFines/ManualFines.vue';
import CarReportButtons from '@layouts/reportforms/CarReportButtons.vue';
import { toast } from 'vue3-toastify';

import BaseSpinner from '@layouts/sliders/BaseSpinner.vue';
import { h } from 'vue';

const loadingToast = () => {
  return toast(
    () => h('div', { class: 'flex items-center gap-3' }, [
      h(BaseSpinner),
      h('span', 'Adatok betöltése...')
    ]),
    {
      position: "top-right",
      autoClose: false,
      closeButton: false
    }
  );
};

export default {
  components: {
    EupModel,
    CarReportButtons,
    CleanReportCard,
    BaseCard,
    BaseLayout,
    EupModel,
    CitigoModel,
    KangooModel,
    KiaNiroModel,
    VivaroModel,
    CarAccidentReport,
    ManualFines,
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
      cleanreport: false,
      demageReport: false,
      accidentReport: false,
      carManualFines: false,
      latestTicket: [],
      loading: true,
    }
  },

  async mounted() {
    const toastId = loadingToast();
    try {
      const response = await http.get(`/cars/${this.$route.params.id}`);
      this.car = response.data.data;

      const ticketresponse = await http.get(`/cars/${this.$route.params.id}/tickets`);
      this.rentFees = ticketresponse.data.data;

      const resp = await http.get(`/cars/${this.$route.params.id}/description`);
      this.latestTicket = resp.data.data;

      const rentResponse = await http.get(`/cars/${this.$route.params.id}/renthistory`);
      this.carRentHistory = rentResponse.data.data;

      const feesResponse = await http.get(`/cars/${this.$route.params.id}/fees`);
      this.rentBillFees = feesResponse.data.data;
      this.rentBillFees.forEach((fine) => {
        this.$set(this.rentBillDetailsStates, fine.id, false);
      });

      toast.update(toastId, {
        render: "Sikeres betöltés",
        type: "success",
        autoClose: 2000
      });
    } catch (error) {
      toast.dismiss(toastId);
      toast.error("Hiba történt");
    }
  },
  methods: {
    toggleSection(section) {
      const sections = ['cleanreport', 'demageReport', 'accidentReport', 'carManualFines'];
      sections.forEach(s => this[s] = s === section ? !this[s] : false);
    },
    submitManualFines(data) {
      console.log('Manual fines adatok:', data);
      // Itt dolgozd fel a ManualFines űrlap adatait, pl. elküldheted a backend felé
    },
    cardetails() {
      this.carOpen = !this.carOpen;
      if (this.carOpen) {
        this.$nextTick(() => {
          this.$refs.adatokAlja.scrollIntoView({ behavior: 'smooth' });
        });
      }
    },
    noteDetails() {
      this.noteOpen = !this.noteOpen;
      if (this.noteOpen) {
        this.$nextTick(() => {
          this.$refs.noteBottom.scrollIntoView({ behavior: 'smooth' });
        });
      }
    },
    rentHistoryDetails() {
      this.rentHistoryOpen = !this.rentHistoryOpen;
      if (this.rentHistoryOpen) {
        this.$nextTick(() => {
          this.$refs.rentHistoryBottom.scrollIntoView({ behavior: 'smooth' });
        });
      }
    },
    rentBillDetails() {
      this.rentBillOpen = !this.rentBillOpen;
    },

    toggleBillDetails(id) {
      this.rentBillDetailsStates[id] =
        !this.rentBillDetailsStates[id];
    },

    async handleFormSubmit(data) {
      try {
        updatePayload = {
          car_id: this.carId,
          plate: this.car.plate,
          category_id: parseInt(this.car.category_id, 10),
          equipment_class: parseInt(this.car.equipment_class, 10),
          fleet_id: parseInt(this.car.fleet_id, 10),
          odometer: parseInt(this.car.odometer),
          manufactured: parseInt(this.car.manufactured, 10),
          power_percent: parseFloat(this.car.power_percent),
          power_kw: parseFloat(this.car.power_kw),
          estimated_range: parseFloat(this.car.estimated_range),
          status_id: parseInt(this.selectedStatus, 10),
          description: this.description.trim(),
        };
        const response = await http.post('/tickets', payload);
        this.submitted = true;
        this.$emit('submit-success', response.data);
      } catch (error) {
      }
    },
    async submitAccidentReport(data) {
      if (this.isSubmitting) return; // Ha már folyamatban van, ne fusson újra
      this.isSubmitting = true;

      const { description } = data; // gyermektől jön //
      if (!confirm(`Balesetet fog bejelenteni a ${this.car.plate} ${this.car.carmodel} carmodelú autóra. Megerősíti?`)) {
        this.isSubmitting = false;
        return;
      }
      else {
        try {
          const CarAccidentData = {
            description,
            car_id: this.car.car_id,
            status_id: 4,
          };
          const CarAccidentRefreshCarData = {
            plate: this.car.plate,
            power_percent: parseFloat(this.car.power_percent),
            power_kw: parseFloat(this.car.power_kw),
            estimated_range: parseFloat(this.car.estimated_range),
            status: 4, // accident //
          }
          await http.post('/tickets', CarAccidentData);
          await http.put(`/cars/${this.car.car_id}`, CarAccidentRefreshCarData)
          alert("A baleseti bejelentés elküldve, a jármű adatai frissültek!");
        }
        catch (error) {
        }
      }
    }
  },
  computed: {
    currentModel() {
      const manufacturerAlapjanModel = {
        VW: "EupModel",
        Skoda: "CitigoModel",
        Renault: "KangooModel",
        Opel: "VivaroModel",
        KIA: "KiaNiroModel",
      };

      return manufacturerAlapjanModel[this.car.manufacturer] || null;
    },
    formattedLastRent() {
      const rentClose = this.carRentHistory.renters[0].rent_close; // Legfrissebb bérlés
      if (!rentClose) return 'Nincs dátum';

      const dateObj = new Date(rentClose);

      const formattedDate = dateObj.toLocaleDateString('hu-HU', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
      });

      const formattedTime = dateObj.toLocaleTimeString('hu-HU', {
        hour: '2-digit',
        minute: '2-digit',
      });

      return `${formattedDate} ${formattedTime}`;
    }
  },
}
</script>