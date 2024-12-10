<template>
    <BaseLayout>
        <div class="mx-auto my-10 w-3/4 border-2 rounded-2xl border-sky-300">
            <table class="w-full border-collapse rounded-2xl overflow-hidden ">
                <thead>
                    <tr class="text-white text-xl bg-sky-600">
                        <th class="px-2">Azonosító</th>
                        <th class="px-2">Rendszám</th>
                        <th class="px-2">Futásteljesítmény</th>
                        <th class="px-2">Gyártási év</th>
                        <th class="px-2">Felszereltség bes.</th>
                        <th class="px-2">Flotta bes.</th>
                    </tr>
                </thead>
                <tbody class="text-white text-lg">
                    <tr v-for="car in cars" :key="car.rendszam"
                        class="text-white cursor-pointer odd:hover:bg-sky-900 even:hover:bg-sky-800 odd:bg-sky-500 even:bg-sky-600">
                        <td class="text-center p-2">{{ car.autok_id }}</td>
                        <td class="text-center p-2">{{ car.rendszam }}</td>
                        <td class="text-center p-2">{{ car.km_ora_allas }}</td>
                        <td class="text-center p-2">{{ car.gyartasi_ev }}</td>
                        <td class="text-center p-2">{{ car.felsz_id_fk }}</td>
                        <td class="text-center p-2">{{ car.flotta_id_fk }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </BaseLayout>
</template>

<script>
import { http } from '@utils/http.mjs';
import BaseLayout from '@layouts/BaseLayout.vue'

export default {
    data() {
        return {
            cars: []
        }
    },
    components: {
        BaseLayout,
    },
    async mounted() {
        try {
            const resp = await http.get('/autok');
            this.cars = resp.data.data;
        } catch (error) {
            console.error('Hiba történt az API hívás során:', error);
        }
    }
}

</script>
