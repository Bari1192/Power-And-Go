<template>
    <div
        class="max-w-7xl mx-auto p-8 bg-gradient-to-b from-indigo-950 to-gray-950 mb-10 rounded-2xl shadow-2xl border border-emerald-500">
        <div class="w-11/12 mx-auto">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-white">Járműfelviteli rendszer</h2>
                <p class="text-slate-400 font-medium mt-1">Új flotta model hozzáadása</p>
            </div>

            <!-- Figyelmeztetés -->
            <div
                class="bg-red-600/20 rounded-lg w-fit font-semibold px-6 py-3 mb-6 text-slate-100 border border-red-600">
                <p>Kérjük, fokozott figyelemmel töltse ki az adatokat!</p>
            </div>

            <!-- Úrlap mezők -->
            <form @submit.prevent="submitHandler" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Gyártó -->
                <FormKit name="manufacturer" type="text" label="Gyártó" placeholder="Pl.: Toyota"
                    label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
                    input-class="w-full border border-slate-400 bg-slate-600 text-white tracking-wide rounded-md border border-slate-600 px-4 py-2 focus:ring-emerald-500" />

                <!-- Modell -->
                <FormKit name="carmodel" type="text" label="Modell típusa" placeholder="Pl.: Corolla"
                    label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
                    input-class="w-full border border-slate-400 bg-slate-600 text-white tracking-wide rounded-md border border-slate-600 px-4 py-2 focus:ring-emerald-500" />

                <!-- Teljesítmény -->
                <FormKit name="motor_power" type="number" label="Teljesítmény (kW)" placeholder="Pl.: 110"
                    label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
                    input-class="w-full border border-slate-400 bg-slate-600 text-white tracking-wide rounded-md border border-slate-600 px-4 py-2 focus:ring-emerald-500" />

                <!-- Kiegészítő mezők (maradék) -->
                <FormKit name="top_speed" type="number" label="Végsebesség" placeholder="Pl.: 180"
                    label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
                    input-class="w-full border border-slate-400 bg-slate-600 text-white tracking-wide rounded-md border border-slate-600 px-4 py-2 focus:ring-emerald-500" />
                <FormKit name="tire_size" type="text" label="Gumiméret" placeholder="Pl.: 195|65-R16"
                    label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
                    input-class="w-full border border-slate-400 bg-slate-600 text-white tracking-wide rounded-md border border-slate-600 px-4 py-2 focus:ring-emerald-500" />
                <FormKit name="driving_range" type="number" label="Hatótáv (km)" placeholder="Pl.: 500"
                    label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
                    input-class="w-full border border-slate-400 bg-slate-600 text-white tracking-wide rounded-md border border-slate-600 px-4 py-2 focus:ring-emerald-500" />

                <!-- Submit Gomb -->
                <div class="col-span-full text-center py-2">
                    <button type="submit"
                        class="px-6 py-3 text-md bg-emerald-600 hover:bg-emerald-700 transition-colors duration-200 text-slate-100 font-semibold rounded-md shadow-md shadow-teal-500/50">
                        Járműfelvitel véglegesítése
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue'
import { useFleetStore } from '@stores/FleetStore'
import { storeToRefs } from 'pinia'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import BaseSpinner from '@layouts/sliders/BaseSpinner.vue'

const fleetStore = useFleetStore()
const { fleets } = storeToRefs(fleetStore)

const submitted = ref(false)
const loading = ref(false)

const submitHandler = async (values) => {
    loading.value = true

    try {
        if (!values.manufacturer || !values.carmodel || !values.motor_power ||
            !values.top_speed || !values.tire_size || !values.driving_range) {
            console.error("Hiányzó mezők:", values)
            toast.error('Kérjük minden adatot töltsön ki!', {
                position: 'top-right',
                autoClose: 6000,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                theme: 'dark'
            })
            return
        }

        // Számszerű értékek átalakítása
        const formattedValues = {
            manufacturer: values.manufacturer,
            carmodel: values.carmodel,
            motor_power: Number(values.motor_power),
            top_speed: Number(values.top_speed),
            tire_size: values.tire_size,
            driving_range: Number(values.driving_range),
        }

        await fleetStore.createFleet(formattedValues)
        submitted.value = true
        await fleetStore.getFleets()

        toast.success('Flotta modell sikeresen hozzáadva!', {
            position: 'bottom-center',
            autoClose: 6000,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            theme: 'dark'
        })

        setTimeout(() => {
            submitted.value = false
        }, 3000)

    } catch (error) {
        console.error("Hiba történt az adatok mentése közben:", error)
        toast.error('Hiba történt az adatok mentése közben!', {
            position: 'top-right',
            autoClose: 6000,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            theme: 'dark'
        })
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
/* FormKit validációs üzenetek stílusa */
:deep(.formkit-messages) {
    color: #ef4444;
    background-color: rgba(254, 240, 138, 0.2);
    padding: 0.5rem;
    border-radius: 0.375rem;
    font-weight: 600;
    text-align: start;
    margin: 0.5rem 0;
    width: fit-content;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

/* FormKit help üzenetek elrejtése */
:deep(.formkit-help) {
    display: none;
}

/* Input focus animáció */
:deep(.formkit-input:focus) {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.3);
}
</style>
