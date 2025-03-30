<template>
    <BaseLayout>
        <div class="mx-auto w-fit my-10 border-2 p-5 rounded-2xl border-lime-500 bg-orange-100/90">
            <div class="m-auto w-fit mx-auto px-auto py-3 border-b-2 flex-wrap dark:border-none">
                <button @click="loadFines(pagination.currentPage = 1)" :disabled="pagination.current_page === 1"
                    class="bg-lime-500 hover:bg-lime-600/90 text-white font-bold py-2 px-4 border-2 border-lime-700 rounded mx-3 disabled:cursor-not-allowed disabled:bg-slate-400">
                    1. oldal
                </button>
                <button :disabled="pagination.current_page === 1" @click="loadFines(pagination.current_page - 1)"
                    class="bg-lime-500 hover:bg-lime-600/90 text-white font-bold py-2 px-4 border-2 border-lime-700 rounded mx-3 disabled:cursor-not-allowed  disabled:bg-slate-400">
                    Előző
                </button>
                <button :disabled="pagination.current_page === pagination.last_page"
                    @click="loadFines(pagination.current_page + 1)"
                    class="bg-lime-500 hover:bg-lime-600/90 text-white font-bold py-2 px-4 border-2 border-lime-700 rounded mx-3 disabled:bg-slate-400 disabled:cursor-not-allowed">
                    Következő
                </button>
                <button @click="loadFines(pagination.lastPage)"
                    class="bg-lime-500 hover:bg-lime-600/90 text-white font-bold py-2 px-4 border-2 border-lime-700 rounded mx-3 ">
                    Utolsó oldal
                </button>
            </div>
        </div>
        <div class="mx-auto w-3/4 my-10 border-2 rounded-2xl border-emerald-600">
            <table class="w-full border-collapse rounded-2xl overflow-hidden">
                <thead>
                    <tr class="text-white text-xl bg-amber-500 border-b-8 border-emerald-800">
                        <th class="px-2 py-4">Típus</th>
                        <th>Számla kiállítása</th>
                        <th>Összege</th>
                        <th>Állapota</th>
                        <th>Távolság</th>
                        <th>Parkolás</th>
                        <th>Vezetés</th>
                        <th>Bérlés kezdete</th>
                        <th>Bérlés vége</th>
                    </tr>
                </thead>
                <tbody class="text-lg">
                    <tr v-for="fine in fines" :key="fine.id"
                        class="text-lime-700/90 cursor-pointer hover:bg-amber-400 odd:bg-amber-100 even:bg-amber-200 transition-transform duration-200 hover:scale-[1.02] origin-bottom">
                        <td class="text-center p-2 pl-4 font-bold italic">{{ fine.bill_type }}</td>
                        <td class="text-center py-2">{{ fine.invoice_date }}</td>
                        <td class="text-center p-2 font-semibold">{{ fine.total_cost }} Ft</td>
                        <td class="text-center text-emerald-500 font-bold p-2 italic">{{ fine.invoice_status }}</td>
                        <td class="text-center p-2">{{ fine.distance }} km</td>
                        <td class="text-center p-2">{{ formatMinutesToHoursAndMinutes(fine.parking_minutes) }} </td>
                        <td class="text-center p-2">{{ formatMinutesToHoursAndMinutes(fine.driving_minutes) }} </td>
                        <td class="text-center p-2">{{ fine.rent_start }}</td>
                        <td class="text-center p-2">{{ fine.rent_close }} <b>{{ fine.rent_end_time }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from '@layouts/BaseLayout.vue';
import { http } from '@utils/http.mjs';
import { ref, onMounted } from 'vue';

const fines = ref({});
const links = ref({});
const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
    links: {}
});
const formatMinutesToHoursAndMinutes = (minutes) => {
    if (!minutes || minutes < 0) return '0 perc';

    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;

    if (hours === 0) {
        return `${remainingMinutes} perc`;
    } else {
        return `${hours} óra ${remainingMinutes} perc`;
    }
};
const loadFines = async (page = 1) => {
    try {
        const resp = await http.get('/bills/closedrentsbills?page=' + page);
        fines.value = resp.data.data;

        pagination.value = {
            current_page: resp.data.meta.current_page,
            last_page: resp.data.meta.last_page,
            per_page: resp.data.meta.per_page,
            total: resp.data.meta.total,
            links: resp.data.links
        };
    } catch (error) {
        console.error('Hiba történt:', error);
    }
};
onMounted(() => {
    loadFines();
});
</script>