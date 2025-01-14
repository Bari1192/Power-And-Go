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
                    <BaseFleet :src="fleet.flotta_id" :imgalt="fleet.gyarto + fleet.tipus + 'képe'" :title="fleet.gyarto === 'VW' ? 'Volkswagen ' + fleet.tipus :
                        fleet.gyarto === 'Renault' ? fleet.gyarto + ' Kangoo Z.E.'
                            : fleet.gyarto + ' ' + fleet.tipus" @edit="editFleet" @delete="deleteFleet"
                            :teljesitmeny="fleet.teljesitmeny"
                            :vegsebesseg="fleet.vegsebesseg"
                            :hatotav="fleet.hatotav"
                            :abroncs="fleet.gumimeret"
                            >
                        <p>Teljesítmény: <b>{{ fleet.teljesitmeny }}</b> kW </p>
                        <p>Végsebesség: {{ fleet.vegsebesseg }} </p>
                        <p>Hatótáv: {{ fleet.hatotav }}</p>
                        <p>Abroncs méret: {{ fleet.gumimeret }}</p>
                    </BaseFleet>
                </div>
                <div v-if="isEditing" class="flex flex-wrap my-12">
                    <div v-for="selectedfleet in fleet" :key="fleet.flotta_id" class="w-1/2 px-3 mb-3 cursor-pointer">
                        <BaseFleet :src="selectedfleet.flotta_id" :imgalt="fleet.gyarto + fleet.tipus + ' képe'"
                            :title="edita.gyarto + ' ' + selectedfleet.tipus"
                            :teljesitmeny="selectedfleet.teljesitmeny" 
                            :vegsebesseg="selectedfleet.vegsebesseg"
                            :hatotav="selectedfleet.hatotav" 
                            :abroncs="selectedfleet.gumimeret">
                        
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