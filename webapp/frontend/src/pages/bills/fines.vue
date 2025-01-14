<template>
    <BaseLayout>
        <div class="mx-auto my-10 border-2 rounded-2xl border-sky-300">
            <table class="w-full border-collapse rounded-2xl overflow-hidden ">
                <thead>
                    <tr class="text-white text-xl bg-sky-600 border-b-purple-900 border-b-8">
                        <th class="p-2">Azonosító</th>
                        <th class="p-2">Személy Azon.</th>
                        <th class="p-2">Felhasználó Azon</th>
                        <th class="p-2">Számla Állapot</th>
                        <th class="p-2">Számla típusa</th>
                        <th class="p-2">Összege</th>
                        <th class="p-2">Kiállítva</th>
                    </tr>
                </thead>
                <tbody class="text-white text-lg">
                    <tr v-for="fine in fines" :key="fine.szamla_id"
                        class="text-white cursor-pointer odd:hover:bg-sky-900 even:hover:bg-sky-800 odd:bg-sky-500 even:bg-sky-600">
                        <td class="text-center p-2">{{ fine.szamla_id }}</td>
                        <td class="text-center p-2">{{ fine.szemely_id }}</td>
                        <td class="text-center p-2">{{ fine.felh_id }}</td>
                        <td class="text-center p-2 italic text-lime-300 font-bold">{{ fine.szamla_status }}</td>
                        <td class="text-center p-2 font-semibold">{{ fine.szamla_tipus }}</td>
                        <td class="text-center text-lime-300 font-bold p-2 italic">{{ fine.osszeg }} Ft</td>
                        <td class="text-center p-2">{{ fine.szamla_kelt }}</td>
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
            const resp = await http.get('/szamlak/filter/toltes_buntetes');
            this.fines = resp.data.data;
        } catch (error) {
            console.error('Hiba történt az API hívás során:', error);
        }
    },
};

</script>