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
                    <ActualFleet :src="fleet.flotta_id" :imgalt="fleet.gyarto + fleet.tipus + 'képe'" :title="fleet.gyarto === 'VW' ? 'Volkswagen ' + fleet.tipus :
                        fleet.gyarto === 'Renault' ? fleet.gyarto + ' Kangoo Z.E.'
                            : fleet.gyarto + ' ' + fleet.tipus">
                        <p>Teljesítmény: <b>{{ fleet.teljesitmeny }}</b> kW </p>
                        <p>Végsebesség: {{ fleet.vegsebesseg }} </p>
                        <p>Hatótáv: {{ fleet.hatotav }}</p>
                        <p>Abroncs méret: {{ fleet.gumimeret }}</p>
                    </ActualFleet>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import { http } from '@utils/http.mjs';
import BaseLayout from '@layouts/BaseLayout.vue';
import NewFleetCar from './fleetcomponents/newFleetCar.vue';
import ActualFleet from './fleetcomponents/BaseFleetCards.vue';

export default {
    data() {
        return {
            fleets: [],
        }
    },
    components: {
        BaseLayout,
        NewFleetCar,
        ActualFleet,
    },
    async mounted() {
        const resp = await http.get('/fleets')
        this.fleets = resp.data.data;
    }
}

</script>