<template>
    <BaseLayout>
        <div class="main">
            <div class="min-h-screen bg-slate-900/45 py-20">
                <div class="max-w-7xl mx-auto mb-8">
                    <div
                        class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 border border-emerald-500/20 backdrop-blur-sm">
                        <h1 class="text-3xl font-bold text-white mb-2">Járműpark Áttekintése</h1>
                        <p class="text-slate-400 font-semibold">
                            Részletes kimutatás a járművek állapotáról és elérhetőségéről
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                            <div class="bg-slate-800/50 p-4 rounded-xl border border-emerald-500/20">
                                <div class="text-emerald-400 text-md font-semibold">Összes jármű</div>
                                <div class="text-2xl font-bold text-white">{{ cars.length }} db</div>
                            </div>
                            <div class="bg-slate-800/50 p-4 rounded-xl border border-emerald-500/20">
                                <div class="text-emerald-400 text-md font-semibold">Folyamatban lévő bérlések</div>
                                <div class="text-2xl font-bold text-white">{{ carsInRentAmount }} db</div>
                            </div>
                            <div class="bg-slate-800/50 p-4 rounded-xl border border-emerald-500/20">
                                <div class="text-emerald-400 text-md font-semibold">Szabad járművek</div>
                                <div class="text-2xl font-bold text-white">{{ carsFreeForRent }} db</div>
                            </div>
                            <div class="bg-slate-800/50 p-4 rounded-xl border border-emerald-500/20">
                                <div class="text-emerald-400 text-md font-semibold">Lemerült járművek</div>
                                <div class="text-2xl font-bold text-white">{{ carsWithLowCharge }} db</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto">
                    <div class="bg-slate-800 rounded-t-2xl p-6 border border-emerald-500/20">
                        <div class="flex justify-between gap-4 items-center">
                            <div class="flex-grow max-w-md">
                                <div class="relative">
                                    <input type="text" placeholder="Keresés rendszám, márka vagy modell alapján..."
                                        v-model="searchTerm"
                                        class="w-full bg-slate-700 border-2 border-emerald-300/20 rounded-xl p-3 text-white placeholder-slate-200 focus:outline-none focus:border-emerald-500">
                                    <i class="fas fa-search absolute right-5 top-5 text-md text-lime-300"></i>
                                </div>
                            </div>

                            <div>
                                <select v-model="statusFilter"
                                    class="bg-slate-700 border border-emerald-500/20 rounded-xl cursor-pointer selection:cursor-pointer font-semibold pl-4 pr-8 py-3 text-white focus:outline-none focus:border-emerald-500">
                                    <option selected value="all">Minden státusz</option>
                                    <option class="cursor-pointer selection:cursor-pointer " value="Szabad">Szabad
                                    </option> <!-- [1] -->
                                    <option value="Foglalva">Foglalás alatt</option><!-- [2] | Felhasználói oldalról -->
                                    <option value="Bérlés alatt">Aktív Bérlések</option> <!-- [3] -->
                                    <option value="Baleset miatt kivonva">Balesetes - Kivonva</option> <!-- [4] -->
                                    <option value="Szervízre vár">Szervízelésre előjegyezve</option> <!-- [5] -->
                                    <option value="Tisztításra vár">Tisztításra előjegyezve</option> <!-- [6] -->
                                    <option value="Kritikus töltés">Lemerült járművek</option> <!-- [7] -->
                                    <option value="Előrendelésre lefoglalva">Előrendelések</option> <!-- [8] -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-800 rounded-b-2xl border-x border-b border-emerald-500/20 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-slate-500/50 ">
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">Rendszám
                                        </th>
                                        <th class="px-6 py-4 text-center text-md font-semibold text-slate-100">
                                            Töltöttség
                                        </th>
                                        <th class="w-1">

                                        </th>
                                        <th class="px-6 py-4 text-center text-md font-semibold text-slate-100">Töltési
                                            Hatótáv</th>
                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">
                                            Kilométeróra
                                        </th>

                                        <th class="px-6 py-4 text-left text-md font-semibold text-slate-100">Típus</th>

                                        <th class="px-6 py-4 text-center text-md font-semibold text-slate-100">Státusz
                                        </th>
                                        <th class="px-6 py-4 text-center text-md font-semibold text-slate-100">Műveletek
                                        </th>
                                    </tr>
                                </thead>
                                <tbody v-for="car in carsFilterBy" :key="car.car_id">
                                    <tr class="border-t border-slate-700 hover:bg-slate-700/30 transition-colors duration-200"
                                        :class="isOperationOpen(car.car_id) ? 'bg-emerald-600/35 hover:bg-emerald-600/35' : 'bg-slate-700/20 hover:bg-slate-700/30'">
                                        <td class="px-6 py-4 text-white">{{ car.plate }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center w-full gap-4">
                                                <!-- Töltési sáv konténer -->
                                                <div class="flex items-center flex-1 gap-2">
                                                    <div class="relative w-32 h-2 bg-gray-700 rounded-full">
                                                        <div class="absolute h-full rounded-full transition-all duration-300"
                                                            :style="{
                                                                width: car.power_percent + '%',
                                                                backgroundColor: car.power_percent > 50 ? 'oklch(76.8% 0.233 130.85)' :
                                                                    car.power_percent > 20 ? 'oklch(75% 0.183 55.934)' :
                                                                        '#ef4444'
                                                            }">
                                                        </div>
                                                    </div>
                                                    <span class="text-sm font-medium text-white">
                                                        {{ Math.round(car.power_percent) }}%
                                                    </span>
                                                </div>

                                            </div>
                                        </td>
                                        <td>
                                            <span v-if="car.power_percent < 20" class="material-symbols-outlined text-amber-400 text-3xl">
                                                battery_android_alert
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-300 text-center">{{ car.estimated_range }} km
                                        </td>

                                        <td class="px-6 py-4 text-slate-300">{{ car.manufacturer }} {{ car.carmodel }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-300">
                                            {{ formatService.formatToOneThousandPrice(car.odometer) }} km</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-4 py-1 rounded-lg text-sm font-medium" :class="{
                                                'bg-green-500/20 text-green-400': car.status === 1,
                                                'bg-yellow-200/20 text-yellow-300': car.status === 3,
                                                'bg-red-500/20 text-red-400': car.status === 7
                                            }">{{ car.status_name }}</span>
                                        </td>
                                        <td class="text-slate-400 hover:text-emerald-400 ">
                                            <button @click="openOperation(car.car_id)"
                                                class="w-full h-full  transition-all duration-200">
                                                <i class="fas flex justify-center py-1 px-2 bg-slate-300/55 text-emerald-300 rounded-full"
                                                    :class="isOperationOpen(car.car_id) ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                            </button>
                                        </td>
                                    </tr>


                                    <tr v-if="isOperationOpen(car.car_id)" class="bg-gray-800/30">
                                        <td colspan="8" class="px-6 py-2">
                                            <carDetailsSection :carId="car.car_id"
                                                :carFines="carsStore.getOneFineBill(car.car_id)" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-if="isLoading" class="p-4">
                            <p class="text-center text-green">Betöltés folyamatban...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from "@layouts/BaseLayout.vue";
import carDetailsSection from "./carDetailsSection.vue";

import { computed, ref } from "vue";
import { useCarStore } from "@stores/carStore";
import { useFormatStore } from "@stores/Services/FormatHelperService";

import { storeToRefs } from 'pinia';

const formatService = useFormatStore();
const carsStore = useCarStore();
const { cars } = storeToRefs(carsStore);

const searchTerm = ref('');
const statusFilter = ref('all');

const isLoading = ref(false);
const openCarDetails = ref({});

const openOperation = (carId) => {
    openCarDetails.value[carId] = !openCarDetails.value[carId];

};
// Ellenőrzés, nyitva / csukva
const isOperationOpen = (carId) => !!openCarDetails.value[carId];

/////// Szűrési inputhoz a struki \\\\\\\\\\\\
const carsFilterBy = computed(() => {
    let resultValues = cars.value;

    if (searchTerm.value && searchTerm.value.trim() !== '') {
        resultValues = resultValues.filter(car =>
            car.plate.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            car.carmodel.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            car.manufacturer.toLowerCase().includes(searchTerm.value.toLowerCase())
        );
    }

    if (statusFilter.value !== 'all') {
        resultValues = carsStore.cars.filter(car => car.status_name === statusFilter.value);
    }
    return resultValues;
});
const carsFreeForRent = computed(() => carsStore.cars.filter(car => car.status === 1).length);
const carsReservedByUsers = computed(() => carsStore.cars.filter(car => car.status === 2).length);
const carsInRentAmount = computed(() => carsStore.cars.filter(car => car.status === 3).length);

const carsWithLowCharge = computed(() => carsStore.cars.filter(car => car.status === 7).length);
</script>

<style>
@import url('@assets/styles/MainBackgroundStyle.css');
@import url("https://fonts.googleapis.com/css2?family=Funnel+Sans:wght@300;400;700&display=swap");
</style>