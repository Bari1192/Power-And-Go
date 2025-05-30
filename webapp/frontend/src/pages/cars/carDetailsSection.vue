<template>
    <div class="min-h-screen">
        <!-- Fejléc -->
        <header
            class=" rounded-l-md bg-gradient-to-r from-slate-600 to-slate-900/5 border-b border-emerald-700/25 mt-2">
            <div class="container mx-auto px-4 py-6 ">
                <h1 class="text-3xl font-bold text-emerald-400 ">Járműkezelő Rendszer</h1>
            </div>
        </header>

        <div class="container mx-auto px-4 pt-4 lg:pt-10">
            <!-- Járműadatok kártya -->
            <div v-if="carStore.car && !carStore.isLoading"
                class="bg-slate-900 rounded-2xl border border-emerald-500/30 shadow-2xl shadow-emerald-500/10 p-8 mb-8">
                <!-- Jármű fejléc -->
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl p-6 mb-8 shadow-lg">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="border-r-2 border-emerald-500/30">
                            <h2 class="text-2xl font-bold text-white">{{ carStore.car.manufacturer }}</h2>
                            <p class="text-slate-300 text-sm font-semibold tracking-wider italic mt-1">(Gyártó)</p>
                        </div>
                        <div class="border-r-2 border-emerald-500/30">
                            <h2 class="text-3xl font-bold text-white">{{ carStore.car.plate }}</h2>
                            <p class="text-slate-300 text-sm font-semibold tracking-wider italic mt-1">(Rendszám)</p>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">{{ carStore.car.carmodel }}</h2>
                            <p class="text-slate-300 text-sm font-semibold tracking-wider italic mt-1">(Modell)</p>
                        </div>
                    </div>
                </div>

                <!-- Funkció gombok -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

                    <button @click="toggleSection('cleanReport')" :class="[
                        'px-6 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105',
                        cleanReport ?
                            'bg-sky-600 text-white shadow-lg shadow-sky-500/30' :
                            'bg-slate-800 text-sky-400 hover:bg-slate-700 border border-sky-500/30'
                    ]">
                        <i class="fa-solid fa-broom-ball text-slate-50 pr-2"></i>
                        Állapotbejelentés
                    </button>

                    <button @click="toggleSection('damageReport')" :class="[
                        'px-6 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105',
                        damageReport ? 'bg-amber-600 text-white shadow-lg shadow-orange-500/30' : 'bg-slate-800 text-amber-400 hover:bg-slate-700 border border-amber-500/30'
                    ]">
                        <i class="fa-solid fa-hammer pr-2 text-slate-50"></i>
                        Kárbejelentés
                    </button>

                    <button @click="toggleSection('accidentReport')" :class="[
                        'px-6 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 tracking-wide',
                        accidentReport ? 'bg-red-600 text-white shadow-lg shadow-red-500/25' : 'bg-slate-800 text-red-400 hover:bg-slate-700 border border-red-500/30'
                    ]">
                        <i class="fa-solid fa-car text-slate-50 pr-2"></i>
                        Baleseti Gyorsbejelentő
                    </button>

                    <button @click="toggleSection('manualFines')" :class="[
                        'px-6 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 tracking-wide',
                        manualFines ? 'bg-purple-600 text-white shadow-lg shadow-purple-500/25' : 'bg-slate-800 text-purple-400 hover:bg-slate-700 border border-purple-500/30'
                    ]">
                        <i class="fa-solid fa-file-invoice text-slate-50 h-5 w-5"></i>
                        Büntetés Kiállítása
                    </button>
                </div>

                <div class="relative">
                    <transition name="fade-slide" mode="out-in">
                        <div v-if="cleanReport" key="clean">
                            <CleanReportCard :carId="carStore.car.car_id" :lastRenter="getLastRenter()" />
                        </div>

                        <div v-else-if="damageReport && currentModel" key="damage">
                            <component :is="currentModel" :car-id="carStore.car.car_id"  />
                        </div>

                        <div v-else-if="accidentReport" key="accident">
                            <CarAccidentReport />
                        </div>

                        <div v-else-if="manualFines" key="fines">
                            <BasedManualFines :carUserRentsHistory="carStore.carRentHistory" />
                        </div>
                    </transition>
                </div>

                <!-- Járműadatok részletek -->
                <div class="mb-8">
                    <button @click="toggleCarDetails" class="flex items-center justify-between w-full text-left group">
                        <h2 class="text-2xl font-bold text-emerald-400 group-hover:text-emerald-300 transition-colors">
                            Jármű részletes adatai
                        </h2>
                        <div :class="[
                            'w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center transition-all duration-300',
                            carDetailsOpen ? 'rotate-180' : ''
                        ]">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <transition name="fade-slide">
                        <div v-if="carDetailsOpen" class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div
                                class="bg-slate-800 rounded-lg p-4 border border-slate-700 hover:border-emerald-500/30 transition-colors">
                                <h3 class="text-sm font-semibold text-slate-400 mb-1">Akkumulátor töltöttség</h3>
                                <div class="flex items-center justify-between">
                                    <p class="text-2xl font-bold text-emerald-400">{{ carStore.car.power_percent }}%</p>
                                    <div class="w-16 h-16 relative">
                                        <svg class="w-16 h-16 transform -rotate-90">
                                            <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="8"
                                                fill="none" class="text-slate-700"></circle>
                                            <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="8"
                                                fill="none"
                                                :stroke-dasharray="`${carStore.car.power_percent * 1.76} 176`"
                                                class="text-emerald-500">
                                            </circle>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-slate-800 rounded-lg p-4 border border-slate-700 hover:border-emerald-500/30 transition-colors">
                                <h3 class="text-sm font-semibold text-slate-400 mb-1">Becsült hatótáv</h3>
                                <p class="text-2xl font-bold text-emerald-400">{{ carStore.car.estimated_range }} km</p>
                                <p class="text-xs text-slate-500 mt-1">Jelenlegi töltöttséggel</p>
                            </div>

                            <div
                                class="bg-slate-800 rounded-lg p-4 border border-slate-700 hover:border-emerald-500/30 transition-colors">
                                <h3 class="text-sm font-semibold text-slate-400 mb-1">Futásteljesítmény</h3>
                                <p class="text-2xl font-bold text-emerald-400">{{
                                    carStore.formatToOneThousandPrice(carStore.car.odometer) }}
                                    km</p>
                                <p class="text-xs text-slate-500 mt-1">Összesen megtett távolság</p>
                            </div>

                            <div
                                class="bg-slate-800 rounded-lg p-4 border border-slate-700 hover:border-emerald-500/30 transition-colors">
                                <h3 class="text-sm font-semibold text-slate-400 mb-1">Akkumulátor teljesítmény</h3>
                                <p class="text-2xl font-bold text-emerald-400">{{ carStore.car.power_kw }} kW</p>
                            </div>

                            <div
                                class="bg-slate-800 rounded-lg p-4 border border-slate-700 hover:border-emerald-500/30 transition-colors">
                                <h3 class="text-sm font-semibold text-slate-400 mb-1">Max. hatótáv</h3>
                                <p class="text-2xl font-bold text-emerald-400">{{ carStore.car.driving_range }} km</p>
                                <p class="text-xs text-slate-500 mt-1">Teljes töltöttséggel</p>
                            </div>

                            <div
                                class="bg-slate-800 rounded-lg p-4 border border-slate-700 hover:border-emerald-500/30 transition-colors">
                                <h3 class="text-sm font-semibold text-slate-400 mb-1">Gyártási év</h3>
                                <p class="text-2xl font-bold text-emerald-400">{{ carStore.car.manufactured }}</p>
                            </div>
                        </div>
                    </transition>
                </div>

                <!-- Elválasztó -->
                <div class="w-full h-[2px] bg-slate-400/20 my-4 rounded-3xl"></div>

                <!-- Bejegyzések -->
                <div class="mb-8">
                    <button @click="toggleNotes" class="flex items-center justify-between w-full text-left group">
                        <h2 class="text-2xl font-bold text-emerald-400 group-hover:text-emerald-300 transition-colors">
                            Bejegyzések
                            <span class="text-sm font-normal text-slate-400 ml-2">({{ carStore.carAllTickets?.length ||
                                0 }}
                                db)</span>
                        </h2>
                        <div v-if="carStore.carAllTickets?.length" :class="[
                            'w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center transition-all duration-300',
                            notesOpen ? 'rotate-180' : ''
                        ]">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>


                    <transition name="fade-slide">
                        <div v-if="notesOpen && carStore.carAllTickets?.length" class="space-y-8">
                            <div v-for="ticket in carStore.carAllTickets" :key="ticket.id" class="bg-slate-800 my-8 rounded-xl border border-slate-700 overflow-hidden hover:border-emerald-500/30 transition-all 
                                duration-300">
                                <div
                                    class="bg-gradient-to-r from-slate-600 to-slate-700 px-6 py-3 border-b border-slate-600">
                                    <h3 class="text-lg font-semibold text-white/80 italic">Bejegyzés azonosító szám:
                                        #{{ ticket.status_id + '-' + ticket.id }}</h3>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                        <div>
                                            <p class="text-md text-emerald-400 mb-1 font-semibold tracking-wide">
                                                Státuszkód</p>
                                            <span
                                                class="inline-block w-7 h-6 text-center m-auto bg-indigo-500/85 text-gray-50 rounded-full text-md font-semibold">
                                                {{ ticket.status_id }}
                                            </span>
                                        </div>
                                        <div class="md:col-span-2">
                                            <p class="text-md text-emerald-400 mb-1 font-bold tracking-wide">Létrehozva
                                            </p>
                                            <p class="text-slate-300 tracking-wide">{{ ticket.created_at }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-slate-700">
                                        <p class="text-md text-emerald-400 mb-1 font-semibold tracking-wide">Bejelentés
                                            tárgya</p>
                                        <p class="text-slate-300 tracking-wide italic">{{ ticket.status_descrip }}</p>
                                    </div>
                                    <div v-if="ticket.admin_description" class="mt-4">
                                        <p class="text-md text-emerald-400 mb-1 font-semibold tracking-wide">Részletek
                                        </p>
                                        <p class="text-slate-300 tracking-wide italic">{{ ticket.admin_description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>

                    <div v-if="!carStore.carAllTickets?.length" class="mt-4 text-slate-400 italic">
                        Ehhez az autóhoz nem tartozik bejegyzés.
                    </div>
                </div>

                <!-- Elválasztó -->
                <div class="w-full h-[2px] bg-slate-400/20 my-4 rounded-3xl"></div>

                <!-- Bérlési előzmények -->
                <div class="mb-8">
                    <button @click="toggleRentHistory" class="flex items-center justify-between w-full text-left group">
                        <h2 class="text-2xl font-bold text-emerald-400 group-hover:text-emerald-300 transition-colors">
                            Bérlési előzmények
                            <span class="text-sm font-normal text-slate-400 ml-2">({{
                                carStore.carRentHistory?.renters?.length || 0 }}
                                bérlés)</span>
                        </h2>
                        <div v-if="carStore.carRentHistory?.renters?.length" :class="[
                            'w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center transition-all duration-300',
                            rentHistoryOpen ? 'rotate-180' : ''
                        ]">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <transition name="fade-slide">
                        <div v-if="rentHistoryOpen && carStore.carRentHistory?.renters?.length"
                            class="mt-6 overflow-x-hidden">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-slate-700">
                                        <th class="text-left py-3 px-4 text-sm lg:text-lg font-semibold text-slate-400">
                                            Bérlő</th>
                                        <th class="text-left py-3 px-4 text-sm lg:text-lg font-semibold text-slate-400">
                                            Zárás</th>
                                        <th class="text-left py-3 px-4 text-sm lg:text-lg font-semibold text-slate-400">
                                            Töltés</th>
                                        <th class="text-left py-3 px-4 text-sm lg:text-lg font-semibold text-slate-400">
                                            Távolság
                                        </th>
                                        <th class="text-left py-3 px-4 text-sm lg:text-lg font-semibold text-slate-400">
                                            Összeg</th>
                                        <th class="text-left py-3 px-4 text-sm lg:text-lg font-semibold text-slate-400">
                                            Kiállítva
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="rent in carStore.carRentHistory.renters" :key="rent.rent_id"
                                        class="border-b border-slate-800 hover:bg-slate-800/50 transition-colors">
                                        <td class="py-3 px-4">
                                            <a :href="`/users/${rent.user}`"
                                                class="text-emerald-400 hover:text-emerald-300 font-medium">
                                                {{ rent.user }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-4 text-slate-300">{{ rent.rent_close }}</td>
                                        <td class="py-3 px-4">
                                            <span :class="[
                                                'inline-block px-2 py-1 rounded text-sm',
                                                rent.end_percent > 20 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400'
                                            ]">
                                                {{ rent.end_percent }}%
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-300">{{ rent.distance }} km</td>
                                        <td class="py-3 px-4 text-emerald-400 font-semibold">{{
                                            rent.rental_cost }} Ft</td>
                                        <td class="py-3 px-4 text-slate-300">{{ rent.invoice_date }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </transition>
                </div>

                <!-- Elválasztó -->
                <div class="w-full h-[2px] bg-slate-400/20 my-4 rounded-3xl"></div>

                <div v-if="finesCount === 0" class="flex items-center justify-left mt-4">
                    <h2 class="text-2xl font-bold text-slate-500">
                        Büntetések
                        <span class="text-sm font-normal text-slate-500 ml-2 italic">
                            (Nincs kiállított büntetés.)
                        </span>
                    </h2>
                </div>

                <!-- Ha vannak bírságok -->
                <div v-else>
                    <h2 class="text-2xl font-bold text-emerald-400 group-hover:text-emerald-300 transition-colors">
                        Büntetések
                        <span class="text-sm font-normal text-slate-400 ml-2">
                            ({{ finesCount }} db)
                        </span>
                    </h2>

                    <div class="mt-6 space-y-4">
                        <!-- Iterálás a szűrt bírságokon -->
                        <div v-for="fine in carFines" :key="fine.id"
                            class="bg-slate-800 rounded-xl border border-slate-700 cursor-pointer hover:border-red-500/30 transition-all duration-300"
                            @click="toggleFineDetails(fine.id)">
                            <div class="bg-gradient-to-r from-red-900/15 to-slate-800/50 px-6 py-4 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-semibold text-yellow-300/85 rounded-xl">
                                        {{ fine.bill_type === 'charging_penalty' ? 'Akkumulátor lemerítési pótdíj' :
                                            fine.bill_type }}
                                    </h3>
                                    <div class="">
                                        <span class="text-xl font-bold text-red-400 rounded-xl">
                                            {{ carStore.formatToOneThousandPrice(fine.total_cost) }} Ft
                                        </span>
                                        <i
                                            class="fas fa-chevron-down flex justify-center py-1 px-2 ml-2 text-white/85 rounded-full"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Részletek megjelenítése, ha nyitott -->
                            <transition name="fade">
                                <div v-if="fineDetails[fine.id]" class="p-6 border-t border-slate-700">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 font-semibold mt-1 justify-center">
                                        <div>
                                            <p class="text-sm text-slate-400 mb-1">Számla sorszám</p>
                                            <p class="text-emerald-400">#{{ fine.id }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-400 mb-1">Kiállítás dátuma</p>
                                            <p class="text-emerald-400 text-sm">{{ fine.invoice_date }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-400 mb-1">Email értesítés állapota</p>
                                            <span :class="[
                                                'inline-block px-3 py-1 rounded-full text-sm tracking-wide',
                                                fine.email_sent === 1 ? 'bg-slate-800 text-emerald-400 hover:bg-slate-700 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-400'
                                            ]">
                                                {{ fine.email_sent === 1 ? 'Elküldve' : 'Nem lett elküldve' }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-400 mb-1">Bérlő</p>
                                            <p class="text-slate-200 text-sm">{{ fine.person }}</p>
                                        </div>
                                    </div>
                                    <div class="w-full h-[2px] bg-slate-400/20 my-4 rounded-3xl"></div>
                                    <div
                                        class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 font-semibold">
                                        <div>
                                            <p class="text-sm text-slate-400 mb-1">Bérlés indítása</p>
                                            <p class="text-white text-sm ">{{ fine.rent_start }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-400 mb-1">Bérlés zárása</p>
                                            <p class="text-white text-sm">{{ fine.rent_close }}</p>
                                        </div>
                                        <div class="text-sm">
                                            <p class="text-sm text-slate-400 mb-1">Számla állapota</p>
                                            <span :class="[
                                                'inline-block px-3 py-1 rounded-full text-sm tracking-wide',
                                                fine.invoice_status === 'pending' ? 'bg-slate-800 text-amber-400 hover:bg-slate-700 border border-amber-500/30' : 'bg-slate-800 text-emerald-400 hover:bg-slate-700 border border-emerald-500/30'
                                            ]">
                                                {{ fine.invoice_status === 'pending' ? 'Kiállítva' : 'Kiegyenlítve' }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm text-slate-400 mb-1">Felhasználónév</p>
                                            <p class="text-slate-200 italic tracking-wide text-sm">{{ fine.username }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Betöltés állapot -->
            <div v-else-if="carStore.isLoading" class="flex items-center justify-center min-h-[30vh]">
                <div class="text-center">
                    <div
                        class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-emerald-500 mx-auto mb-4">
                    </div>
                    <p class="text-slate-400 text-lg">Adatok betöltése...</p>
                </div>
            </div>


            <!-- Hiba állapot -->
            <div v-else-if="carStore.error" class="flex items-center justify-center min-h-[30vh]">
                <div class="text-center bg-red-500/10 border border-red-500/30 rounded-xl p-8">
                    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-400 text-lg font-semibold mb-2">Hiba történt az adatok betöltése közben</p>
                    <p class="text-slate-400">{{ carStore.error }}</p>
                    <button @click="loadCarData"
                        class="mt-4 px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Újrapróbálkozás
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useCarStore } from '@stores/carStore';
import CarAccidentReport from './CarAccidentReport.vue';
import BasedManualFines from './BasedManualFines.vue';
import CleanReportCard from './CleanReportCard.vue';
import EupModel from '@layouts/carmodels/EupModel.vue';
import CitigoModel from '@layouts/carmodels/CitigoModel.vue';
import KangooModel from '@layouts/carmodels/KangooModel.vue';
import VivaroModel from '@layouts/carmodels/VivaroModel.vue';
import KiaNiroModel from '@layouts/carmodels/KiaNiroModel.vue';

const props = defineProps({
    carId: {
        type: [Number, String],
        required: true
    },
    carFines: {
        type: [Array, Object, null],
        required: true,
    }
})

const carStore = useCarStore()

const loadCarData = async () => {
    await carStore.getCarDetails(props.carId);
}
const fineDetails = ref({})

const carDetailsOpen = ref(false)
const notesOpen = ref(false)
const rentHistoryOpen = ref(false)

// Jelentések
const cleanReport = ref(false)
const damageReport = ref(false)
const accidentReport = ref(false)
const manualFines = ref(false)

const finesCount = computed(() => {
    return Array.isArray(props.carFines) ? props.carFines.length : 0;
});
function getLastRenter() {
    const renters = carStore.carRentHistory.renters || []
    return renters.length > 0 ? renters[renters.length - 1] : null
}
const currentModel = computed(() => {
  const manufacturerModelMap = {
    'VW': EupModel,
    'Skoda': CitigoModel,
    'Renault': KangooModel,
    'Opel': VivaroModel,
    'KIA': KiaNiroModel
  }
  const manufacturer = carStore.car?.manufacturer
  return manufacturerModelMap[manufacturer] || null
})
// Dinamikus megjelenése a szekciós részeknek
const toggleSection = (section) => {
    if (section === 'cleanReport') {
        cleanReport.value = !cleanReport.value
        damageReport.value = false
        accidentReport.value = false
        manualFines.value = false
    } else if (section === 'damageReport') {
        damageReport.value = !damageReport.value
        cleanReport.value = false
        accidentReport.value = false
        manualFines.value = false
    } else if (section === 'accidentReport') {
        accidentReport.value = !accidentReport.value
        cleanReport.value = false
        damageReport.value = false
        manualFines.value = false
    } else if (section === 'manualFines') {
        manualFines.value = !manualFines.value
        cleanReport.value = false
        damageReport.value = false
        accidentReport.value = false
    }
}

const toggleCarDetails = () => {
    carDetailsOpen.value = !carDetailsOpen.value
}

const toggleNotes = () => {
    notesOpen.value = !notesOpen.value
}

const toggleRentHistory = () => {
    rentHistoryOpen.value = !rentHistoryOpen.value;
}

const toggleFineDetails = (fineId) => {
    fineDetails.value[fineId] = !fineDetails.value[fineId];
};

onMounted(() => {
    loadCarData()
})
</script>

<style scoped>
.fade-slide-leave-active {
    transition: opacity 0.2s ease, transform 0.15s ease;
}

.fade-slide-enter-active {
    transition: opacity 0.2s ease, transform 0.15s ease;
    transition-delay: 0.05s;
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(10px);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>