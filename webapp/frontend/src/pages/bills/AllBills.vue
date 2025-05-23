<template>
    <BaseLayout>
        <div class="main">
            <div class="min-h-screen bg-slate-900/45 py-20">
                <!-- Header Section -->
                <div class="max-w-7xl mx-auto mb-8">
                    <div
                        class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 border border-emerald-500/20 backdrop-blur-sm">
                        <h1 class="text-3xl font-bold text-white mb-2">Számlák Áttekintése</h1>
                        <p class="text-slate-400">Részletes kimutatás a bérlések és kapcsolódó költségek
                            nyilvántartásáról
                        </p>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="bg-slate-800/50 p-4 rounded-xl border border-emerald-500/20">
                                <div class="text-emerald-400 text-md">Összes számla</div>
                                <div class="text-2xl font-bold text-white">{{
                                    carStore.formatToOneThousandPrice(pagination.total) }} db</div>
                            </div>
                            <div class="bg-slate-800/50 p-4 rounded-xl border border-emerald-500/20">
                                <div class="text-emerald-400 text-md">Aktív számlák</div>
                                <div class="text-2xl font-bold text-white">{{ activeInvoices }} db</div>
                            </div>
                            <div class="bg-slate-800/50 p-4 rounded-xl border border-emerald-500/20">
                                <div class="text-emerald-400 text-md">Függőben lévő számlák</div>
                                <div class="text-2xl font-bold text-white">{{ pendingInvoices }} db</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="max-w-7xl mx-auto">
                    <!-- Filters & Controls -->
                    <div class="bg-slate-800 rounded-t-2xl p-6 border border-emerald-500/20">
                        <div class="flex flex-wrap gap-4 items-center justify-between">
                            <!-- Search -->
                            <div class="flex-grow max-w-md">
                                <div class="relative">
                                    <input type="text" placeholder="Keresés..."
                                        class="w-full bg-slate-700 border-2 border-emerald-100/20 rounded-xl p-3 text-white placeholder-slate-200 focus:outline-none focus:border-emerald-500">
                                    <i class="fas fa-search absolute right-3 top-3 text-slate-200"></i>
                                </div>
                            </div>

                            <!-- Filters -->
                            <div class="flex gap-4">
                                <select
                                    class="bg-slate-700 border border-emerald-500/20 rounded-xl font-semibold pl-2 pr-4 py-2 text-white">
                                    <option>Minden típus</option>
                                    <option>Aktív</option>
                                    <option>Függőben</option>
                                </select>
                                <button
                                    class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl transition-colors duration-200">
                                    <i class="fas fa-filter mr-2 font-semibold"></i>Szűrés
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="bg-slate-800 rounded-b-2xl border-x border-b border-emerald-500/20 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-slate-500/50 ">
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">Típus</th>

                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">Bérlési idő
                                        </th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">Töltésért
                                            járó
                                            kredit
                                        </th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">Kiállítva
                                        </th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">Összege
                                        </th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">Állapot
                                        </th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">Részletek
                                        </th>
                                    </tr>
                                </thead>
                                <tbody v-for="fine in fines" :key="fine.id">
                                    <tr
                                        class="border-t border-slate-700 hover:bg-slate-700/30 transition-colors duration-200 cursor-pointer">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-invoice text-emerald-400 mr-3"></i>
                                                <span class="text-white">{{ fine.bill_type }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-300">{{
                                            formatMinutesToHoursAndMinutes(fine.parking_minutes + fine.driving_minutes)
                                        }}
                                        </td>
                                        <td v-if="fine.credits" class="px-6 py-4 flex flex-row justify-between m-auto">
                                            <span class="text-emerald-400">{{
                                                carStore.formatToOneThousandPrice(fine.credits)
                                                }} Ft</span> <span class="text-slate-400 text-md italic">({{
                                                    fine.charged_kw }} kW)</span>
                                        </td>
                                        <td v-else class="px-6 py-4 text-slate-400 italic">
                                            Töltés nem volt.
                                        </td>
                                        <td class="px-6 py-4 text-slate-300">{{ fine.invoice_date }}</td>
                                        <td class="px-6 py-4">
                                            <span class="text-emerald-400 font-semibold">{{
                                                carStore.formatToOneThousandPrice(fine.total_cost) }} Ft</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span :class="{
                                                'bg-emerald-500/20 text-emerald-400': fine.invoice_status === 'active',
                                                'bg-amber-500/20 text-amber-400': fine.invoice_status === 'pending'
                                            }" class="px-3 py-1 rounded-full text-sm">
                                                {{ fine.invoice_status }}
                                            </span>
                                        </td>
                                        <td class="text-slate-400 hover:text-emerald-400 ">
                                            <button @click="toggleRentalDetail(fine.id)"
                                                class="w-full h-full  transition-all duration-200">
                                                <i class="fas flex justify-center py-1 px-2 bg-slate-300/55 text-emerald-300 rounded-full"
                                                    :class="isRentalDetailOpen(fine.id) ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="isRentalDetailOpen(fine.id)" class="bg-gray-800/30">
                                        <td colspan="7" class="px-6 py-4">
                                            <div class="md:min-h-[100px] w-full border-x-4 border-emerald-500/85 pl-4 bg-slate-600/50 p-4 rounded-r-2xl 
                                                rounded-l-2xl">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <!-- Főbb adatok -->
                                                    <div class="space-y-2">
                                                        <h3 class="text-emerald-400 font-semibold text-lg">Bérlési
                                                            információk</h3>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div class="text-slate-300">Felhasználó:</div>
                                                            <div class="text-white">{{ fine.person }}</div>

                                                            <div class="text-slate-300">Rendszám:</div>
                                                            <div class="text-white">{{ fine.plate }}</div>

                                                            <div class="text-slate-300">Bérlés kezdete:</div>
                                                            <div class="text-white">{{ fine.rent_start }}</div>

                                                            <div class="text-slate-300">Bérlés vége:</div>
                                                            <div class="text-white">{{ fine.rent_close }}</div>

                                                            <div class="text-slate-300">Jármű Zár. Töltöttsége:</div>
                                                            <div class="text-white">{{ fine.end_percent || 0 }}%</div>
                                                        </div>
                                                    </div>

                                                    <!-- Számlázási adatok -->
                                                    <div class="space-y-2">
                                                        <h3 class="text-emerald-400 font-semibold text-lg">Számlázási
                                                            adatok</h3>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div class="text-slate-300">Számla azonosító:</div>
                                                            <div class="text-slate-200">{{}}#{{ fine.id }}</div>

                                                            <div class="text-slate-300">Kiállítás dátuma:</div>
                                                            <div class="text-slate-200">{{ fine.invoice_date }}
                                                            </div>

                                                            <div class="text-slate-300">Állapot:</div>
                                                            <div
                                                                :class="fine.invoice_status === 'pending' ? 'text-amber-400' : 'text-emerald-400'">
                                                                {{ fine.invoice_status === 'pending' ? 'Függőben' :
                                                                    'Fizetve' }}
                                                            </div>

                                                            <div class="text-slate-300">Email értesítés:</div>
                                                            <div class="text-slate-200">{{ fine.email_sent ? 'Elküldve'
                                                                :
                                                                'Feldolgozás alatt'
                                                            }}</div>

                                                            <div class="text-slate-300">Teljes összeg:</div>
                                                            <div class="text-slate-200 font-semibold">{{
                                                                carStore.formatToOneThousandPrice(fine.total_cost) }} Ft
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Egyéb adatok -->
                                                    <div class="space-y-2">
                                                        <h3 class="text-emerald-400 font-semibold text-lg">Bérlés
                                                            részletei</h3>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div class="text-slate-300">Megtett távolság:</div>
                                                            <!-- Azt vizsgálja, hogy a napi km limitet a bérlésnél túllépte-e -->
                                                            <div :class="fine.distance > 125 * ((fine.driving_minutes + fine.parking_minutes % 60) % 24) ?
                                                                'text-amber-400' : 'text-slate-200'">
                                                                {{ fine.distance || 0 }} km</div>

                                                            <div v-if="fine.charged_kw > 0" class="text-slate-300">
                                                                Jármű visszatöltés:</div>
                                                            <div v-if="fine.charged_kw > 0" class="text-white">{{
                                                                fine.charged_kw }} kW</div>

                                                            <div class="text-slate-300">Vezetési idő:</div>
                                                            <div class="text-white italic">{{ fine.driving_minutes }} perc
                                                            </div>

                                                            <div v-if="fine.parking_minutes > 0" class="text-slate-300">Parkolási idő:</div>
                                                            <div v-if="fine.parking_minutes > 0" class="text-white italic">{{ fine.parking_minutes }} perc
                                                            </div>

                                                            <div v-if="fine.credits > 0" class="text-slate-300">Jóváírt
                                                                kreditek:</div>
                                                            <div v-if="fine.credits > 0" class="text-white italic">{{
                                                                carStore.formatToOneThousandPrice(fine.credits) }} Ft
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="p-4 border-t border-slate-700">
                            <div class="flex items-center justify-between">
                                <div class="text-slate-300 text-sm">
                                    <b>Aktuális oldal:</b> <span class="text-emerald-400 text-sm">{{
                                        pagination.current_page }}</span> /
                                    <span class="text-emerald-400/80 text-sm">{{ pagination.last_page }}</span>
                                    oldal.
                                </div>
                                <div class="flex gap-2">
                                    <button @click="loadFines(pagination.currentPage = 1)"
                                        :disabled="pagination.current_page === 1"
                                        class="px-4 py-2 rounded-lg bg-slate-700 text-white hover:bg-emerald-600 disabled:opacity-50 disabled:hover:bg-slate-700 transition-colors duration-200">
                                        <i class="fas fa-chevron-left mr-2"></i>
                                        Vissza
                                    </button>
                                    <button @click="loadFines(pagination.current_page + 1)"
                                        :disabled="pagination.current_page === pagination.last_page"
                                        class="px-4 py-2 rounded-lg bg-slate-700 text-white hover:bg-emerald-600 disabled:opacity-50 disabled:hover:bg-slate-700 transition-colors duration-200">
                                        Következő
                                        <i class="fas fa-chevron-right ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from '@layouts/BaseLayout.vue';
import { http } from '@utils/http.mjs';
import { ref, onMounted, computed } from 'vue';
import { useCarStore } from '@stores/carStore';

const carStore = useCarStore();

const openRentalDetails = ref({});

const fines = ref({});
const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
    links: {}
});
const toggleRentalDetail = (id) => {
    openRentalDetails.value[id] = !openRentalDetails.value[id];
};

// Ellenőrzés, hogy egy adott számla részletei meg vannak-e jelenítve
const isRentalDetailOpen = (id) => {
    return !!openRentalDetails.value[id];
};

const activeInvoices = computed(() => {
    return Object.values(fines.value).filter(fine => fine.invoice_status === 'active').length;
});

const pendingInvoices = computed(() => {
    return Object.values(fines.value).filter(fine => fine.invoice_status === 'pending').length;
});

const formatMinutesToHoursAndMinutes = (minutes) => {
    if (!minutes || minutes < 0) return '0 perc';

    const remainingMinutes = minutes % 60;
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const remainingHours = hours % 24;

    // Kereken X napot bérelt.
    if (days > 0 && remainingHours === 0 && remainingMinutes === 0) {
        return `${days} nap`;
    }
    // X nap és Y perc - Óra nélkül
    if (days > 0 && remainingHours === 0 && remainingMinutes > 0) {
        return `${days} nap ${remainingMinutes} perc`;
    }

    // X Nap és óra - Perc nélkül
    if (days > 0 && remainingHours > 0 && remainingMinutes === 0) {
        return `${days} nap ${remainingHours} óra`;
    }

    // Napok, órák és percek is
    if (days > 0 && remainingHours > 0 && remainingMinutes > 0) {
        return `${days} nap ${remainingHours} óra ${remainingMinutes} perc`;
    }
    // Csak órák
    if (hours > 0 && remainingMinutes === 0) {
        return `${hours} óra`;
    }

    // Órák és percek
    if (hours > 0 && remainingMinutes > 0) {
        return `${hours} óra ${remainingMinutes} perc`;
    }
    // Csak percek 
    return `${minutes} perc`;
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


<style>
.main {
    background: url('@img/Welcome/blocks3.webp');
    background-position: center;
    background-repeat: repeat-y;
    background-size: cover;
    background-attachment: fixed;

}
</style>