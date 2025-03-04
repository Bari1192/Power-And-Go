<template>
    <BaseLayout>
        <div class="mx-auto mt-16 mb-32 border-2 rounded-2xl border-sky-300">
            <table class="w-full border-collapse rounded-2xl overflow-hidden ">
                <thead>
                    <tr class="text-white text-xl bg-sky-600 border-b-purple-900 border-b-8">
                        <th class="p-2">Bérlés Azon.</th>
                        <th class="p-2">Rendszám</th>
                        <th class="p-2">Bérlő</th>
                        <th class="p-2">Felh. Név</th>
                        <th class="p-2">Számla Állapot</th>
                        <th class="p-2">Számla típusa</th>
                        <th class="p-2">Összege</th>
                        <th class="p-2">Kiállítva</th>
                    </tr>
                </thead>
                <tbody class="text-white text-lg">
                    <tr v-for="fine in fines" :key="fine.id"
                        class="text-white cursor-pointer odd:hover:bg-sky-900 even:hover:bg-sky-800 odd:bg-sky-500 even:bg-sky-600">
                        <td class="text-center p-2">{{ fine.rent_id }}</td>
                        <td class="text-center p-2">{{ fine.plate }}</td>
                        <td class="text-center p-2">{{ fine.person }}</td>
                        <td class="text-center p-2">{{ fine.username }}</td>
                        <td class="text-center p-2 italic text-lime-300 font-bold">{{ fine.invoice_status }}</td>
                        <td v-if="fine.bill_type === 'charging_penalty'" class="text-pink-900 text-opacity-90"><b>ÁSZF - Töltési mulasztás</b></td>
                        <td v-else>Ismeretlen</td>
                        <td class="text-center text-lime-300 font-bold p-2 italic">{{ fine.total_cost }} Ft</td>
                        <td class="text-center p-2">{{ fine.invoice_date }}</td>
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
            const resp = await http.get('/bills/fees');
            this.fines = resp.data.data;
        } catch (error) {
            console.error('Hiba történt az API hívás során:', error);
        }
    },
};

</script>