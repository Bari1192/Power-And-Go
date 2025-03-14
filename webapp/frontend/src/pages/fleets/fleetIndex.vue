<template>
    <BaseLayout>
      <NewFleetCar />
      <div class="mx-auto border-2 rounded-2xl border-sky-300 w-3/4 px-4 mb-20">
        <p class="text-center tracking-wide text-5xl font-semibold text-sky-100 mt-8 mb-4 capitalize">
          A flotta jelenlegi katalógusa
        </p>
        <div class="m-auto d-flex justify-center border-b-4 border-sky-300 w-2/3"></div>
        <div class="flex flex-wrap my-12">
          <div v-for="fleet in fleets" :key="fleet.id" class="w-1/2 px-3 mb-3 cursor-pointer">
            <BaseFleet
              :id="fleet.id"
              :manufacturer="fleet.manufacturer"
              :carmodel="fleet.carmodel"
              :motor_power="fleet.motor_power"
              :top_speed="fleet.top_speed"
              :driving_range="fleet.driving_range"
              :tire_size="fleet.tire_size"
              @edit="editFleet"
              @delete="deleteFleet"
              @update="updateFleet"
            >
              <p>Teljesítmény: <b>{{ fleet.motor_power }}</b> kW</p>
              <p>Végsebesség: {{ fleet.top_speed }}</p>
              <p>Hatótáv: {{ fleet.driving_range }}</p>
              <p>Gumiméret: {{ fleet.tire_size }}</p>
            </BaseFleet>
          </div>
        </div>
      </div>
    </BaseLayout>
  </template>
  
  <script>
  import { http } from '@utils/http.mjs';
  import BaseLayout from '@layouts/BaseLayout.vue';
  import NewFleetCar from '@pages/fleets/fleetcomponents/newFleetCar.vue';
  import BaseFleet from '@pages/fleets/fleetcomponents/BaseFleetCards.vue';
  
  export default {
    name: 'FleetIndex',
    components: {
      BaseLayout,
      NewFleetCar,
      BaseFleet,
    },
    data() {
      return {
        fleets: [],
      };
    },
    async mounted() {
      await this.fetchFleets();
    },
    methods: {
      async fetchFleets() {
        try {
          const resp = await http.get('/fleets');
          this.fleets = resp.data.data;
        } catch (error) {
          console.error('Hiba történt az API hívás során:', error);
        }
      },
      async updateFleet(id, updatedData) {
        const payload = {
          manufacturer: updatedData.manufacturer,
          carmodel: updatedData.carmodel,
          motor_power: updatedData.motor_power,
          top_speed: updatedData.top_speed,
          driving_range: updatedData.driving_range,
          tire_size: updatedData.tire_size,
        };
        try {
          const response = await http.put(`/fleets/${id}`, payload);
          if (response.status === 200) {
            alert('A módosítás sikeres!');
            await this.fetchFleets();
          }
        } catch (error) {
          alert('Hiba történt a mentés során: ' + error.response.data.message);
        }
      },
      async deleteFleet(id) {
        if (confirm('Biztosan törölni szeretné?')) {
          try {
            const response = await http.delete(`/fleets/${id}`);
            if (response.status === 200) {
              alert('A törlés sikeres!');
              this.fleets = this.fleets.filter(fleet => fleet.id !== id);
            }
          } catch (error) {
            alert('Törlési hiba: ' + error.response.data.message);
          }
        } else {
          alert('A törlés visszavonva.');
        }
      },
      editFleet(id) {
        console.log('Edit esemény érkezett a következő fleet id-től:', id);
      },
    },
  };
  </script>
  