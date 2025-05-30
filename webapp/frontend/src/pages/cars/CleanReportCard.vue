<template>
    <div
        class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-sky-500/55 to-transparent my-16 rounded-full">
    </div>

    <div class="bg-slate-800 border-2 border-sky-500/45 rounded-xl mb-8 shadow-2xl shadow-sky-500/10 p-8">
        <div v-if="!loading" class="bg-inherit p-6 w-full">
            <form v-if="!submitted" @submit.prevent="submitHandler" class="space-y-6">
                <!-- Ügyfél-Azonosítás szakasz -->
                <div class="w-full">
                    <div
                        class="bg-gradient-to-r from-sky-600/20 via-sky-500/30 to-sky-600/20 rounded-lg p-4 border-2 border-sky-500/50">
                        <p class="text-center text-xl font-bold text-slate-100 tracking-wider">
                            ÜGYFÉL-AZONOSÍTÁS
                        </p>
                    </div>
                </div>

                <!-- Első sor - 3 mező -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <FormKit type="text" name="car_id" :value="carId" label="Autó azonosítója"
                            :validation="'required|integer'" :validation-messages="{
                                required: 'Kötelező megadni!'
                            }" disabled outer-class="w-full"
                            label-class="block text-lg font-semibold text-slate-200 mb-2"
                            input-class="w-full px-4 py-2 rounded-lg border border-slate-500 bg-slate-700 text-emerald-500 font-semibold opacity-75 cursor-not-allowed" />
                    </div>

                    <div>
                        <FormKit type="select" name="statusDescrip" v-model="selectedStatus" label="Bejelentés törzse"
                            :validation="'required'" :validation-messages="{
                                required: 'Bejelentés típusát kötelező megjelölni!'
                            }" outer-class="w-full" label-class="block text-lg font-semibold text-slate-200 mb-2"
                            input-class="w-full px-4 py-2 rounded-lg border bg-slate-600 text-slate-100 " :options="[
                                { value: '', label: 'Kérem válasszon!' },
                                { value: 6, label: 'Tisztasági takarítást igényel' },
                                { value: 5, label: 'Kerékbilincs/Tilosban parkolás' },
                                { value: 1, label: 'Tisztítva, forgalomba visszaállítása' }
                            ]" />
                    </div>

                    <div>
                        <FormKit type="text" name="lastRenter" :value="lastRenter?.user || 'N/A'"
                            label="Legutóbbi bérlő" disabled outer-class="w-full"
                            label-class="block text-lg font-semibold text-slate-200 mb-2"
                            input-class="w-full px-4 py-2 rounded-lg border border-slate-500 bg-slate-700 text-emerald-500 font-semibold opacity-75 cursor-not-allowed" />
                    </div>
                </div>



                <!-- Második sor - 3 mező -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <FormKit type="text" name="person_birth" label="Ügyfél Születési Dátuma"
                            :validation="'required|date'" placeholder="1990.01.01" :validation-messages="{
                                required: 'Kötelező megadni!'
                            }" outer-class="w-full" label-class="block text-lg font-semibold text-slate-200 mb-2"
                            input-class="w-full px-4 py-2 rounded-lg border bg-slate-600 text-emerald-400 font-semibold tracking-wider" />
                    </div>

                    <div>
                        <FormKit type="text" name="passIdentify" label="Jelszó 2. és utolsó számjegye"
                            :validation="'required|integer'" :validation-messages="{
                                required: 'Kötelező megadni!'
                            }" outer-class="w-full" label-class="block text-lg font-semibold text-slate-200 mb-2"
                            input-class="w-full px-4 py-2 rounded-lg border bg-slate-600 text-emerald-400 font-semibold tracking-wider" />
                    </div>

                    <div>
                        <FormKit type="text" name="person_name" label="Ügyfél Neve"
                            :validation="'required|length:10,128'" :validation-messages="{
                                required: 'Kötelező megadni!'
                            }" outer-class="w-full" label-class="block text-lg font-semibold text-slate-200 mb-2"
                            input-class="w-full px-4 py-2 rounded-lg border bg-slate-600 text-emerald-400 font-semibold tracking-wider" />
                    </div>
                </div>

                <!-- Bejelentés tartalma -->
                <div class="mb-8">
                    <FormKit type="textarea" name="description" v-model="description" label="Bejelentés tartalma"
                        placeholder="Mennyire és hol szennyeződött? Mikor tervezi lezárni a bérlést?"
                        :validation="'required|length:10,255'" :validation-messages="{
                            length: 'A bejelentés szövege min 10, maximum 255 karakter hosszú lehet!',
                            required: 'Kötelező kitölteni!'
                        }" :validation-visibility="submitted ? 'live' : 'dirty'" outer-class="w-full"
                        label-class="block text-lg font-semibold text-slate-200 mb-2"
                        input-class="w-full p-3 rounded-lg border bg-slate-600 text-slate-100  resize-y max-h-[250px] min-h-[125px]" />
                </div>

                <!-- Submit gomb -->
                <div class="text-center">
                    <button type="submit" style="font-family: 'Nunito';"
                        class="px-8 py-3 tracking-wider text-slate-50 bg-sky-600/75 rounded-lg font-bold shadow-sky-800 hover:bg-sky-800/90 transition-colors duration-200">
                        Bejelentés Elküldése
                    </button>
                </div>
            </form>

            <!-- Sikeres beküldés üzenet -->
            <div v-else class="flex justify-center items-center min-h-[200px]">
                <div class="bg-emerald-600/20 border-2 border-emerald-500 rounded-xl p-8">
                    <h2 class="text-3xl font-bold text-emerald-400 text-center">
                        Az adatok sikeresen beküldésre kerültek!
                    </h2>
                </div>
            </div>
        </div>

        <div v-else class="w-full h-full mx-auto flex justify-center items-center min-h-[400px]">
            <BaseSpinner />
        </div>
    </div>

    <div
        class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-sky-500/55 to-transparent my-16 rounded-full">
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { http } from '@utils/http'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import BaseSpinner from '@layouts/sliders/BaseSpinner.vue'

const props = defineProps({
    carId: {
        type: Number,
        required: true
    },
    lastRenter: {
        type: [Object, Array],
        required: true
    }
})

const emit = defineEmits(['submit-success'])

const submitted = ref(false)
const selectedStatus = ref('')
const description = ref('')
const loading = ref(false)
const fleets = ref([])

const submitHandler = async () => {
    try {
        loading.value = true
        const payload = {
            car_id: props.carId,
            status_id: parseInt(selectedStatus.value, 10),
            description: description.value.trim()
        }

        const response = await http.post('/tickets', payload)
        submitted.value = true
        emit('submit-success', response.data)

        // Toast értesítés
        toast.success('Bejelentés sikeresen elküldve!', {
            position: 'bottom-center',
            autoClose: 6000,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            theme: 'dark'
        })
    } catch (error) {
        toast.error('Hiba történt a bejelentés során!', {
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

// Watchers
watch(selectedStatus, (statusNumber) => {
    if (description.value === '' || description.value === 'Az autó elérhető és bérlésre kész.') {
        if (statusNumber === 6) {
            description.value = `Az autót tisztítása ki kell vonni a forgalomból.\n` +
                `Szennyeződés helye:\n` +
                `Szennyeződés mértéke, területe:\n`
        } else if (statusNumber === 1) {
            description.value = 'Az autó elérhető és bérlésre kész.'
        } else if (statusNumber === 5) {
            description.value = 'A gépjrmármű helytelen lezárását követően kerékbilincs ellátták el az autót.'
        } else {
            description.value = ''
        }
    }
})

// Lifecycle
onMounted(async () => {
    try {
        const resp = await http.get('/fleets')
        fleets.value = resp.data.data
    } catch (error) {
        console.error('Hiba a fleets lekérdezése során:', error)
    }
})
</script>

<style scoped>
/* FormKit validációs üzenetek stílusa */
:deep(.formkit-messages) {
    color: #ef4444;
    padding: 0.5rem;
    border-radius: 0.375rem;
    font-weight: 600;
    text-align: start;
    margin: 0.5rem 0;
    width: fit-content;
}
</style>
