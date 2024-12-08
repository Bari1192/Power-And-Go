<template>
    <BaseLayout>
        <div class="mx-auto my-10 border-2 rounded-2xl border-sky-300">
            <table class="w-full border-collapse rounded-2xl overflow-hidden ">
                <thead>
                    <tr class="text-white text-xl bg-sky-600 border-b-8 border-sky-800">
                        <th class="p-2">Számla típusa</th>
                        <th class="p-2">Kiállítva</th>
                        <th class="p-2">Összege</th>
                        <th class="p-2">Állapota</th>
                        <th class="p-2">Levezetett Km.</th>
                        <th class="p-2">Parkolás</th>
                        <th class="p-2">Vezetés</th>
                        <th class="p-2">Bérlés kezdete</th>
                        <th class="p-2">Bérlés vége</th>
                    </tr>
                </thead>
                <tbody class="text-white text-lg">
                    <tr v-for="fine in fines" :key="fine.szamla_id"
                        class="text-white cursor-pointer odd:hover:bg-sky-900 even:hover:bg-sky-800 odd:bg-sky-500 even:bg-sky-600">
                        <td class="text-center p-2">{{ fine.szamla_tipus }}</td>
                        <td class="text-center p-2">{{ fine.szamla_kelt }}</td>
                        <td class="text-center p-2">{{ fine.osszeg }}</td>
                        <td class="text-center p-2">{{ fine.szamla_status }}</td>
                        <td class="text-center p-2">{{ fine.megtett_tavolsag }}</td>
                        <td class="text-center p-2">{{ fine.parkolasi_perc }}</td>
                        <td class="text-center p-2">{{ fine.vezetesi_perc }}</td>
                        <td class="text-center p-2">{{ fine.berles_kezd_datum }}{{ fine.berles_kezd_ido }}</td>
                        <td class="text-center p-2">{{ fine.berles_veg_datum }}{{ fine.berles_veg_ido }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue';
import { http } from '@utils/http.mjs';

export default {
    data() {
        return {
            fines: [],
        }
    },
    components: {
        BaseLayout,
    },
    async mounted() {
        try {
            const resp = await http.get('/szamlak');
            this.fines = resp.data.data;
        } catch (error) {
            console.error('Hiba történt az API hívás során:', error);
        }
    },
};

</script>