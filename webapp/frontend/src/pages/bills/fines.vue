<template>
    <BaseLayout>
        <div class="main">
            <div class="min-h-screen bg-slate-900/45 py-10 sm:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6">
                    <div
                        class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-4 sm:p-6 border border-emerald-500/20 backdrop-blur-sm">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Büntetések Nyilvántartása</h1>
                        <p class="text-sm sm:text-base text-slate-400">Összes büntetés és mulasztás áttekintése</p>

                        <!-- Quick Stats Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mt-4 sm:mt-6">
                            <div class="bg-slate-800/50 p-3 sm:p-4 rounded-xl border border-red-500/20">
                                <div class="text-red-400 text-sm sm:text-md font-semibold">Büntetés száma</div>
                                <div class="text-xl sm:text-2xl font-bold text-white">{{ fines.length }}</div>
                            </div>
                            <div class="bg-slate-800/50 p-3 sm:p-4 rounded-xl border border-red-500/20">
                                <div class="text-red-400 text-sm sm:text-md font-semibold">Teljesítésre váró ügyek száma
                                </div>
                                <div class="text-xl sm:text-2xl font-bold text-white">{{fines.filter(f =>
                                    f.invoice_status === 'pending').length}} db</div>
                            </div>
                            <div class="bg-slate-800/50 p-3 sm:p-4 rounded-xl border border-red-500/20">
                                <div class="text-red-400 text-sm sm:text-md font-semibold">Fennálló büntetések
                                    összértéke</div>
                                <div class="text-xl sm:text-2xl font-bold text-white">
                                    {{formatStore.formatToOneThousandPrice(fines.reduce((sum, fine) => sum +
                                        Number(fine.total_cost), 0))}} Ft</div>
                            </div>
                            <div class="bg-slate-800/50 p-3 sm:p-4 rounded-xl border border-red-500/20">
                                <div class="text-red-400 text-sm sm:text-md font-semibold">Érintett járművek száma</div>
                                <div class="text-xl sm:text-2xl font-bold text-white">{{new Set(fines.map(f =>
                                    f.plate)).size}} db</div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="hidden lg:block bg-slate-800 rounded-2xl border border-red-500/20 overflow-hidden mt-6">
                        <!-- Filters & Search -->
                        <div class="p-4 sm:p-6 border-b border-slate-700">
                            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                                <div class="w-full sm:flex-grow sm:max-w-md">
                                    <div class="relative">
                                        <input type="text" placeholder="Keresés rendszám vagy felhasználó szerint..."
                                            class="w-full bg-slate-700 border-2 border-emerald-100/20 rounded-xl p-2 sm:p-3 text-sm sm:text-base text-white placeholder-slate-200 focus:outline-none focus:border-emerald-500">
                                        <i
                                            class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-slate-200"></i>
                                    </div>
                                </div>
                                <div class="flex gap-2 sm:gap-4 w-full sm:w-auto">
                                    <select
                                        class="flex-1 sm:flex-none cursor-pointer bg-slate-700 border border-emerald-500/20 rounded-xl text-sm sm:text-base font-semibold px-2 py-2 text-white">
                                        <option>Minden típus</option>
                                        <option>Aktív</option>
                                        <option>Függőben</option>
                                    </select>
                                    <button
                                        class="flex-1 sm:flex-none bg-emerald-600 hover:bg-emerald-500 text-white px-3 sm:px-4 py-2 rounded-xl transition-colors duration-200 text-sm sm:text-base">
                                        <i class="fas fa-filter mr-2"></i>Szűrés
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-slate-500/50">
                                        <th class=" w-fit px-6 py-4 text-left text-md font-semibold text-slate-100">
                                            Bérlés Azon.</th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">
                                            Rendszám</th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">
                                            Bérlő Neve</th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">
                                            Felhasználó</th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">
                                            Státusz</th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">
                                            Típus</th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">
                                            Összeg</th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">
                                            Kiállítva</th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">
                                            Részletek</th>
                                    </tr>
                                </thead>
                                <tbody v-for="fine in fines" :key="fine.id">
                                    <tr
                                        class="text-nowrap border-t border-slate-700 hover:bg-slate-700/30 transition-all duration-200 cursor-pointer">
                                        <td
                                            class="mx-auto text-center max-w-fit text-xs sm:text-sm md:text-base text-slate-300">
                                            {{
                                                fine.rent_id }}</td>
                                        <td class="p-3 sm:p-4 text-xs sm:text-sm md:text-base text-slate-300">{{
                                            fine.plate }}</td>
                                        <td class="p-3 sm:p-4 text-xs sm:text-sm md:text-base text-slate-300">{{
                                            fine.person }}</td>
                                        <td class="p-3 sm:p-4 text-xs sm:text-sm md:text-base text-slate-300">{{
                                            fine.username }}
                                        </td>
                                        <td class="p-3 sm:p-4">
                                            <span :class="{
                                                'bg-emerald-500/20 text-emerald-400': fine.invoice_status === 'active',
                                                'bg-amber-500/20 text-amber-400': fine.invoice_status === 'pending'
                                            }" class="px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm">
                                                {{ fine.invoice_status }}
                                            </span>
                                        </td>
                                        <td class="p-3 sm:p-4">
                                            <i class="fa-solid fa-circle-exclamation text-yellow-300"></i>
                                            <span v-if="fine.bill_type === 'charging_penalty'"
                                                class="text-xs sm:text-sm md:text-base text-red-400 font-semibold">
                                                ÁSZF <br> Töltési mulasztás
                                            </span>

                                            <span v-else
                                                class="text-xs sm:text-sm md:text-base text-slate-400">Ismeretlen</span>
                                        </td>
                                        <td
                                            class="p-3 sm:p-4 text-xs sm:text-sm md:text-base font-semibold text-emerald-400">
                                            {{ formatStore.formatToOneThousandPrice(fine.total_cost) }} Ft
                                        </td>
                                        <td class="p-3 sm:p-4 text-xs sm:text-sm md:text-base text-slate-300">{{
                                            fine.invoice_date }}
                                        </td>
                                        <td class="text-slate-400 hover:text-emerald-400">
                                            <button @click="toggleFeeDetails(fine.id)"
                                                class="w-full h-full transition-all duration-200">
                                                <i class="fas flex justify-center py-1 px-2 bg-slate-300/55 text-emerald-300 rounded-full"
                                                    :class="isFeeDetailOpen(fine.id) ? 'fa-chevron-down' : 'fa-chevron-right'">
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="isFeeDetailOpen(fine.id)" class="bg-gray-800/30">
                                        <td colspan="9" class="p-3 sm:p-6">
                                            <div
                                                class="md:min-h-[100px] w-full border-x-4 border-emerald-500/85 bg-slate-600/50 p-3 sm:p-4 rounded-r-2xl rounded-l-2xl">
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <!-- Főbb adatok -->
                                                    <div class="space-y-2">
                                                        <h3 class="text-base sm:text-lg text-emerald-400 font-semibold">
                                                            Bérlési információk</h3>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-400">
                                                                Felhasználó:
                                                            </div>
                                                            <div class="text-xs sm:text-sm md:text-base text-white">
                                                                {{ fine.person }}
                                                            </div>
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-400">
                                                                Bérlés
                                                                kezdete:</div>
                                                            <div class="text-xs sm:text-sm md:text-base text-white">
                                                                {{ fine.rent_start }}</div>
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-400">
                                                                Bérlés vége:
                                                            </div>
                                                            <div class="text-xs sm:text-sm md:text-base text-white">
                                                                {{ fine.rent_close }}</div>
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-400">
                                                                Jármű Zár.
                                                                Töltöttsége:</div>
                                                            <div class="text-xs sm:text-sm md:text-base text-white">
                                                                {{ fine.end_percent }}%</div>
                                                        </div>
                                                    </div>

                                                    <!-- Számlázási adatok -->
                                                    <div class="space-y-2">
                                                        <h3 class="text-base sm:text-lg text-emerald-400 font-semibold">
                                                            Számlázási adatok</h3>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-400">
                                                                Számla
                                                                azonosító:</div>
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-200">
                                                                #{{ fine.id
                                                                }}
                                                            </div>
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-400">
                                                                Kiállítás
                                                                dátuma:</div>
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-200">
                                                                {{ fine.invoice_date }}</div>
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-400">
                                                                Állapot:
                                                            </div>
                                                            <div :class="fine.invoice_status === 'pending' ? 'text-amber-400' : 'text-emerald-400'"
                                                                class="text-xs sm:text-sm">
                                                                {{ fine.invoice_status === 'pending' ? 'Függőben' :
                                                                    'Fizetve' }}
                                                            </div>
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-400">
                                                                Email
                                                                értesítés:</div>
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-200">
                                                                {{ fine.email_sent ? 'Elküldve' : 'Feldolgozás alatt' }}
                                                            </div>
                                                            <div class="text-xs sm:text-sm md:text-base text-slate-400">
                                                                Teljes
                                                                összeg:</div>
                                                            <div
                                                                class="text-xs sm:text-sm md:text-base text-slate-200 font-semibold">
                                                                {{ formatStore.formatToOneThousandPrice(fine.total_cost) }}
                                                                Ft
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
                        <div class="p-3 sm:p-4 border-t border-slate-700">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                                <div class="text-slate-300 text-xs sm:text-sm order-2 sm:order-1">
                                    <b>Aktuális oldal:</b>
                                    <span class="text-emerald-400">{{ pagination.current_page }}</span>/
                                    <span class="text-emerald-400/80">{{ pagination.last_page }}</span> oldal.
                                </div>
                                <div class="flex gap-2 w-full sm:w-auto order-1 sm:order-2">
                                    <button @click="loadFines(pagination.currentPage = 1)"
                                        :disabled="pagination.current_page === 1"
                                        class="flex-1 sm:flex-none px-3 sm:px-4 py-2 text-xs sm:text-sm rounded-lg bg-slate-700 text-white hover:bg-emerald-600 disabled:opacity-50 disabled:hover:bg-slate-700 transition-colors duration-200">
                                        <i class="fas fa-chevron-left mr-2"></i>Vissza
                                    </button>
                                    <button @click="loadFines(pagination.current_page + 1)"
                                        :disabled="pagination.current_page === pagination.last_page"
                                        class="flex-1 sm:flex-none px-3 sm:px-4 py-2 text-xs sm:text-sm rounded-lg bg-slate-700 text-white hover:bg-emerald-600 disabled:opacity-50 disabled:hover:bg-slate-700 transition-colors duration-200">
                                        Következő<i class="fas fa-chevron-right ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:hidden space-y-4 mt-8 lg:mt-0">
                        <div v-for="fine in fines" :key="fine.id"
                            class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
                            <div class="p-4 space-y-3">
                                <!-- Header info -->
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-sm text-slate-400">Azonosító</div>
                                        <div class="text-base font-semibold text-white">#{{ fine.rent_id }}</div>
                                    </div>
                                    <div>
                                        <span :class="{
                                            'bg-emerald-500/20 text-emerald-400': fine.invoice_status === 'active',
                                            'bg-amber-500/20 text-amber-400': fine.invoice_status === 'pending'
                                        }" class="px-3 py-1 rounded-full text-sm">
                                            {{ fine.invoice_status }}
                                        </span>
                                    </div>
                                </div>
                                <!-- Main info -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <div class="text-sm text-slate-400">Rendszám</div>
                                        <div class="text-base text-white">{{ fine.plate }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-slate-400">Bérlő</div>
                                        <div class="text-base text-white">{{ fine.person }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-slate-400">Összeg</div>
                                        <div class="text-base font-semibold text-emerald-400">
                                            {{ formatStore.formatToOneThousandPrice(fine.total_cost) }} Ft
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-slate-400">Kiállítva</div>
                                        <div class="text-base text-white text-wrap">{{ fine.invoice_date }}</div>
                                    </div>
                                </div>
                                <!-- Type info -->
                                <div>
                                    <div class="text-sm text-slate-400">Típus</div>
                                    <div v-if="fine.bill_type === 'charging_penalty'"
                                        class="text-sm text-red-400 font-semibold">
                                        ÁSZF - Töltési mulasztás
                                    </div>
                                    <div v-else class="text-sm text-slate-400">
                                        Ismeretlen
                                    </div>
                                </div>
                                <!-- Details button -->
                                <button @click="toggleFeeDetails(fine.id)" class="w-full mt-2 px-4 py-2 bg-slate-500/50 hover:bg-slate-700 
                        rounded-lg text-slate-300 hover:text-white transition-all duration-200
                        flex items-center justify-between">
                                    <span>Részletek</span>
                                    <i class="fas"
                                        :class="isFeeDetailOpen(fine.id) ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                </button>
                                <!-- Expanded details -->
                                <div v-if="isFeeDetailOpen(fine.id)"
                                    class="mt-3 pt-3 border-t border-slate-700 bg-slate-600/50 p-2 rounded-md">
                                    <div class="space-y-4">
                                        <!-- Bérlési információk -->
                                        <div>
                                            <h4 class="text-emerald-400 font-semibold mb-2">Bérlési információk</h4>
                                            <div class="grid grid-cols-2 gap-2 text-sm">
                                                <div class="text-slate-400">Felhasználó:</div>
                                                <div class="text-white">{{ fine.person }}</div>
                                                <div class="text-slate-400">Bérlés kezdete:</div>
                                                <div class="text-white">{{ fine.rent_start }}</div>
                                                <div class="text-slate-400">Bérlés vége:</div>
                                                <div class="text-white">{{ fine.rent_close }}</div>
                                                <div class="text-slate-400">Töltöttség:</div>
                                                <div class="text-white">{{ fine.end_percent }}%</div>
                                            </div>
                                        </div>

                                        <!-- Bérlés részletei -->
                                        <div>
                                            <h4 class="text-emerald-400 font-semibold mb-2">Bérlés részletei</h4>
                                            <div class="grid grid-cols-2 gap-2 text-sm">
                                                <div class="text-slate-400">Megtett távolság:</div>
                                                <div
                                                    :class="fine.distance > 125 * ((fine.driving_minutes + fine.parking_minutes % 60) % 24) ? 'text-amber-400' : 'text-white'">
                                                    {{ fine.distance || 0 }} km
                                                </div>
                                                <div class="text-slate-400">Vezetési idő:</div>
                                                <div class="text-white">{{ fine.driving_minutes || 0 }} perc</div>
                                                <div class="text-slate-400">Parkolási idő:</div>
                                                <div class="text-white">{{ fine.parking_minutes || 0 }} perc</div>
                                            </div>
                                        </div>
                                    </div>
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
import { ref, onMounted } from 'vue';
import { http } from '@utils/http.mjs';
import { useCarStore } from '@stores/carStore';
import { useFormatStore } from '@stores/Services/FormatHelperService';

const carStore = useCarStore();
const formatStore = useFormatStore();
const fines = ref([]);
const openFeeDetails = ref({});

const toggleFeeDetails = (id) => {
    openFeeDetails.value[id] = !openFeeDetails.value[id];
};

const isFeeDetailOpen = (id) => {
    return !!openFeeDetails.value[id];
};

const loadFines = async () => {
    try {
        const resp = await http.get('/bills/fees');
        fines.value = resp.data.data;
    } catch (error) {
        console.error('Hiba történt az API hívás során:', error);
    }
};

const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
    links: {}
});

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