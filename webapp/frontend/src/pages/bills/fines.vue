<template>
    <BaseLayout>
        < <div class="mx-auto w-3/4 my-10 border-2 rounded-2xl border-emerald-600 mb-40">
            <table class="w-full border-collapse rounded-2xl overflow-hidden">
                <thead>
                    <tr class="text-white text-xl bg-amber-500 border-b-8 border-emerald-800">
                        <th class="px-2 py-4">Bérlés Azon.</th>
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
                    class="text-lime-700/90 cursor-pointer hover:bg-amber-400 odd:bg-amber-100 even:bg-amber-200 transition-transform duration-200 hover:scale-[1.02] origin-bottom">
                    <td class="text-center p-2">{{ fine.rent_id }}</td>
                        <td class="text-center p-2">{{ fine.plate }}</td>
                        <td class="text-center p-2">{{ fine.person }}</td>
                        <td class="text-center p-2">{{ fine.username }}</td>
                        <td class="text-center text-emerald-500 font-bold p-2 italic">{{ fine.invoice_status }}</td>
                        <td v-if="fine.bill_type === 'charging_penalty'" class="text-red-700 text-opacity-90"><b>ÁSZF - Töltési mulasztás</b></td>
                        <td v-else>Ismeretlen</td>
                        <td class="text-center text-emerald-500 font-bold p-2 italic"> {{ fine.total_cost }} Ft</td>
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