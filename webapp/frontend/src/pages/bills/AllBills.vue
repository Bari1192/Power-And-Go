<template>
    <BaseLayout>
        <!-- Dashboard -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            <!-- Fejléc panel fent -->
            <div class="mx-auto my-8 py-4 w-4/5 h-full bg-gradient-to-b from-emerald-500 via-emerald-600 to-green-700 
            font-bold rounded-lg border-4 border-emerald-700 space-y-2" style="font-family: 'Nunito','Arial';">
                <p class="text-center  text-2xl">
                    Számlák Áttekintése
                </p>
                <p class="text-center text-xl">
                    Részletes kimutatás a bérlések és kapcsolódó költségek nyilvántartásáról
                </p>
            </div>

            <div class="bg-slate-800 rounded-2xl shadow-lg border-4 border-emerald-600">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <!-- Lapozók -->
                        <div class="flex items-center space-x-2">
                            <button @click="loadFines(pagination.currentPage = 1)"
                                :disabled="pagination.current_page === 1" class="inline-flex items-center px-4 py-2 text-base font-medium rounded-lg
                         bg-emerald-500 text-white hover:bg-emerald-600 
                         disabled:bg-slate-500 disabled:cursor-not-allowed disabled:opacity-60
                         transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-angle-double-left mr-2"></i>
                                Első
                            </button>

                            <button :disabled="pagination.current_page === 1"
                                @click="loadFines(pagination.current_page - 1)" class="inline-flex items-center px-4 py-2 text-base font-medium rounded-lg
                         bg-emerald-500 text-white hover:bg-emerald-600 
                         disabled:bg-slate-500 disabled:cursor-not-allowed disabled:opacity-60
                         transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-angle-left mr-2"></i>
                                Előző
                            </button>

                            <span class="px-4 py-2 text-base font-medium text-slate-600 dark:text-slate-300">
                                {{ pagination.current_page }} / {{ pagination.last_page }}
                            </span>

                            <button :disabled="pagination.current_page === pagination.last_page"
                                @click="loadFines(pagination.current_page + 1)" class="inline-flex items-center px-4 py-2 text-base font-medium rounded-lg
                         bg-emerald-500 text-white hover:bg-emerald-600 
                         disabled:bg-slate-500 disabled:cursor-not-allowed disabled:opacity-60
                         transition-all duration-200 shadow-sm hover:shadow-md">
                                Következő
                                <i class="fas fa-angle-right ml-2"></i>
                            </button>

                            <button @click="loadFines(pagination.last_Page)" class="inline-flex items-center px-4 py-2 text-base font-medium rounded-lg
                         bg-emerald-500 text-white hover:bg-emerald-600 
                         disabled:bg-slate-500 disabled:cursor-not-allowed disabled:opacity-60
                         transition-all duration-200 shadow-sm hover:shadow-md">
                                Utolsó
                                <i class="fas fa-angle-double-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold border-b-4 border-emerald-500" style="font-family: 'Nunito','Arial';">
                                <th class="px-2 py-4 text-left text-base font-semibold">Típus</th>
                                <th class="px-2 py-4 text-left text-base font-semibold">Kiállítva</th>
                                <th class="px-2 py-4 text-left text-base font-semibold">Összege</th>
                                <th class="px-2 py-4 text-left text-base font-semibold">Állapota</th>
                                <th class="px-2 py-4 text-left text-base font-semibold">Távolság</th>
                                <th class="px-2 py-4 text-left text-base font-semibold">Parkolás</th>
                                <th class="px-2 py-4 text-left text-base font-semibold">Vezetés</th>
                                <th class="px-2 py-4 text-left text-base font-semibold">Bérlés kezdete</th>
                                <th class="px-2 py-4 text-left text-base font-semibold">Bérlés vége</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="fine in fines" :key="fine.id" class="border-b-2 border-slate-900/35 hover:bg-emerald-50 dark:hover:bg-slate-700/50
                         transition-all duration-200 cursor-pointer max-w-fit ">
                                <td class="px-6 py-4 text-base text-white">
                                    {{ fine.bill_type }}
                                </td>
                                <td class="px-6 py-4 text-base text-slate-300">
                                    {{ fine.invoice_date }}
                                </td>
                                <td class="px-6 py-4 text-base font-semibold text-emerald-600 dark:text-emerald-400 lg:text-nowrap">
                                    {{ fine.total_cost }} Ft
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-2 text-sm rounded-full" :class="{
                                        'bg-emerald-100 text-emerald-700': fine.invoice_status === 'active',
                                        'bg-orange-100 text-yellow-700 font-semibold': fine.invoice_status === 'pending'
                                    }">
                                        {{ fine.invoice_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-base text-slate-300">
                                    {{ fine.distance }} km
                                </td>
                                <td class="px-6 py-4 text-base text-slate-300 md:text-nowrap">
                                    {{ formatMinutesToHoursAndMinutes(fine.parking_minutes) }}
                                </td>
                                <td class="px-6 py-4 text-base text-slate-300 md:text-nowrap">
                                    {{ formatMinutesToHoursAndMinutes(fine.driving_minutes) }}
                                </td>
                                <td class="px-6 py-4 text-base text-slate-300">
                                    {{ fine.rent_start }}
                                </td>
                                <td class="px-6 py-4 text-base text-slate-300 md:text-nowrap">
                                    {{ fine.rent_close }}
                                    <b>{{ fine.rent_end_time }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from '@layouts/BaseLayout.vue';
import { http } from '@utils/http.mjs';
import { ref, onMounted } from 'vue';

const fines = ref({});
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