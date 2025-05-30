<template>
    <!-- Díszítő vonal -->
    <div
        class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-yellow-500/55 to-transparent my-16 rounded-full">
    </div>

    <div class="bg-slate-800 border-2 border-yellow-500/45 rounded-xl mb-8 shadow-2xl shadow-yellow-500/10 p-8">
        <!-- Fejléc -->
        <div class="w-full mb-8">
            <div
                class="bg-gradient-to-r from-yellow-600/20 via-yellow-500/30 to-yellow-600/20 rounded-lg p-4 border-2 border-yellow-500/50">
                <p class="text-center text-xl font-bold text-slate-100 tracking-wider uppercase">kár,-és hibabejelentés
                    készítése</p>
            </div>
        </div>

        <!-- Fő tartalom konténer -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Autó szekció -->
            <div class="lg:col-span-3 bg-slate-700/50 rounded-xl p-6 border border-yellow-500/30">
                <div class="car-container relative mx-auto">
                    <img src="http://backend.vm1.test/storage/carsImages/eupmodel.png" alt="VW e-up model"
                        class="w-5/6 h-auto">

                    <!-- Markerek -->
                    <div v-for="marker in markers" :key="marker.name"
                        class="marker absolute w-6 h-6 rounded-full border-2 transition-all duration-200 cursor-pointer transform -translate-x-1/2 -translate-y-1/2 hover:scale-110"
                        :class="[
                            marker.name === selectedLocation
                                ? 'bg-red-600 border-red-700 shadow-lg shadow-red-500/50 animate-pulse'
                                : 'bg-emerald-400 border-emerald-500 hover:bg-emerald-500'
                        ]" :style="{ top: marker.top, left: marker.left }" @click="selectLocation(marker.name)">
                        <!-- Tooltip -->
                        <div
                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-slate-900 text-white text-xs rounded opacity-0 hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none">
                            {{ marker.name }}
                        </div>
                    </div>
                </div>

                <!-- Kiválasztott hely megjelenítése -->
                <div v-if="selectedLocation" class="mt-6 p-4 bg-slate-500/50 rounded-md mx-auto w-2/3">
                    <div class="flex justify-evenly items-center">
                        <span class="text-emerald-400 font-semibold">A sérülés helye:</span> <span class="text-white">{{
                            selectedLocation }}</span>
                    </div>
                </div>
            </div>

            <!-- Form szekció -->
            <div class="lg:col-span-2">
                <DemageForm :selected-location="selectedLocation" @submit-success="handleFormSubmit" />
            </div>
        </div>
    </div>

    <!-- Díszítő vonal -->
    <div
        class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-yellow-500/55 to-transparent my-16 rounded-full">
    </div>
</template>

<script setup>
import { ref } from 'vue'
import DemageForm from '@pages/cars/DemageForm.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

// Reaktív változók
const selectedLocation = ref('')

// Markerek adatai
const markers = [
    { name: 'Bal hátsó lökhárító', top: '12%', left: '82%' },
    { name: 'Bal hátsó ajtó', top: '25%', left: '85%' },
    { name: 'Bal első ajtó', top: '36%', left: '82%' },
    { name: 'Bal visszapillantó', top: '40.5%', left: '89%' },
    { name: 'Bal első sárvédő', top: '71%', left: '80%' },
    { name: 'Rendszám', top: '74%', left: '48%' },
    { name: 'Hátsó lökhárító', top: '2%', left: '48%' },
    { name: 'Jobb első sárvédő', top: '71%', left: '15%' },
    { name: 'Jobb visszapillantó tükör', top: '40.5%', left: '8.5%' },
    { name: 'Jobb hátsó ajtó', top: '23%', left: '15%' },
    { name: 'Jobb első ajtó', top: '36%', left: '15%' },
    { name: 'Jobb hátsó lökhárító', top: '12%', left: '15%' },
    { name: 'Szélvédő', top: '40%', left: '50%' },
    { name: 'Tető', top: '20%', left: '48%' }
]

// Metódusok
const selectLocation = (name) => {
    selectedLocation.value = name
}

const handleFormSubmit = (data) => {
    toast.success('Sérülés bejelentve!', {
        position: 'bottom-center',
        autoClose: 6000,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        theme: 'dark'
    })
}
</script>

<style scoped>
.car-container {
    max-width: 500px;
    position: relative;
}

/* Animáció a kiválasztott markerhez */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    }

    70% {
        box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
    }
}

.animate-pulse {
    animation: pulse 2s infinite;
}
</style>
