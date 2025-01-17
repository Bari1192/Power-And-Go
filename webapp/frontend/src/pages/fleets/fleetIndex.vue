<template>
    <BaseLayout>
        <NewFleetCar />

        <div class="mx-auto border-2 rounded-2xl border-sky-300 w-3/4 px-4 mb-20">
            <p class="text-center tracking-wide text-5xl font-semibold text-sky-100 mt-8 mb-4 capitalize">
                A flotta jelenlegi katalógusa
            </p>
            <div class="m-auto d-flex justify-center border-b-4 border-sky-300 w-2/3 "></div>

            <div class="flex flex-wrap my-12">
                <div v-for="fleet in fleets" :key="fleet.flotta_id" class="w-1/2 px-3 mb-3 cursor-pointer">
                    <BaseFleet :src="fleet.flotta_id" :imgalt="fleet.manufacturer + fleet.carmodel + 'képe'" :title="fleet.manufacturer === 'VW' ? 'Volkswagen ' + fleet.carmodel :
                        fleet.manufacturer === 'Renault' ? fleet.manufacturer + ' Kangoo Z.E.'
                            : fleet.manufacturer + ' ' + fleet.carmodel" @edit="editFleet" @delete="deleteFleet"
                            :motor_power="fleet.motor_power"
                            :top_speed="fleet.top_speed"
                            :driving_range="fleet.driving_range"
                            :abroncs="fleet.tire_size"
                            >
                        <p>Teljesítmény: <b>{{ fleet.motor_power }}</b> kW </p>
                        <p>Végsebesség: {{ fleet.top_speed }} </p>
                        <p>Hatótáv: {{ fleet.driving_range }}</p>
                        <p>Abroncs méret: {{ fleet.tire_size }}</p>
                    </BaseFleet>
                </div>
                <div v-if="isEditing" class="flex flex-wrap my-12">
                    <div v-for="selectedfleet in fleet" :key="fleet.flotta_id" class="w-1/2 px-3 mb-3 cursor-pointer">
                        <BaseFleet :src="selectedfleet.flotta_id" :imgalt="fleet.manufacturer + fleet.carmodel + ' képe'"
                            :title="edita.manufacturer + ' ' + selectedfleet.carmodel"
                            :motor_power="selectedfleet.motor_power" 
                            :top_speed="selectedfleet.top_speed"
                            :driving_range="selectedfleet.driving_range" 
                            :abroncs="selectedfleet.tire_size">
                        
                        </BaseFleet>
                    </div>
                </div>
            </div>

        </div>
    </BaseLayout>
</template>

<script>
import { http } from '@utils/http.mjs';
import BaseLayout from '@layouts/BaseLayout.vue';
import NewFleetCar from './fleetcomponents/newFleetCar.vue';
import BaseFleet from './fleetcomponents/BaseFleetCards.vue';

export default {
    data() {
        return {
            fleets: [],
            isEditing: false,
        }
    },
    components: {
        BaseLayout,
        NewFleetCar,
        BaseFleet,
    },
    async mounted() {
        const resp = await http.get('/fleets')
        this.fleets = resp.data.data;
    },
    methods: {
        async updateFleet(flotta_id, updatedData) {
            try {
                const response = await http.put(`/fleets/${flotta_id}`, updatedData);
                if (response.status === 200) {
                    alert('A módosítás sikeres!');
                    this.fetchFleets();
                }
            } catch (error) {
                alert('Hiba történt a mentés során: ' + error.response.data.message);
            }
        },
        async deleteFleet(flotta_id) {
            if (confirm('Biztosan törölni szeretné?')) {
                try {
                    const response = await http.delete(`/fleets/${flotta_id}`);
                    if (response.status === 200) {
                        alert('A törlés sikeres!');
                        this.fleets = this.fleets.filter(fleet => fleet.flotta_id !== flotta_id);
                    }
                } catch (error) {
                    alert('Törlési hiba: ' + error.response.data.message);
                }
            } else {
                alert('A törlés visszavonva.');
            }
        }
    }
}
</script>