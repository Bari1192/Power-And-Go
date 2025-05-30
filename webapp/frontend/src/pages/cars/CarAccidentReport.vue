<template>
    <div
        class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-red-500/55 to-transparent my-16 rounded-full">
    </div>

    <div class="bg-slate-800 border-2 border-red-500/35 rounded-xl mb-8 shadow-2xl shadow-red-400/10 p-8">
        <div v-if="!loading" class="bg-inherit p-6 grid grid-cols-1 md:grid-cols-2 w-full gap-6">
            <form @submit.prevent="onSubmit" class="space-y-6">
                <div>
                    <FormKit type="text" name="location" id="location" v-model="location"
                        label="Baleset pontos helyszíne" :validation="'required'"
                        :validation-messages="{ required: 'A helyszín megadása kötelező!' }" outer-class="w-full"
                        label-class="block text-lg font-semibold text-slate-200 mb-2"
                        input-class="w-full px-4 py-2 rounded-lg border bg-slate-600 text-slate-100 focus:ring-2 focus:ring-red-400"
                        placeholder="Pl.: Budapest, Andrássy út 3." />
                </div>
                <div>
                    <FormKit type="datetime-local" name="datetime" v-model="formattedDateTime" label="Mikor történt?"
                        :validation="'required'" :validation-messages="{ required: 'Az időpont megadása kötelező!' }"
                        outer-class="w-full" label-class="block text-lg font-semibold mb-2"
                        input-class="w-full px-4 py-2 rounded-lg border bg-slate-700 text-slate-100 focus:ring-2 focus:ring-red-400" />
                </div>

                <div>
                    <span class="font-semibold text-lg text-yellow-300 mx-auto">Személyi sérülés történt a baleset
                        során? <i
                            class="fa-solid fa-circle-exclamation text-white/90 bg-red-600 rounded-full"></i></span>
                    <div class="flex justify-center gap-6 mt-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="injuryStatus" value="igen" v-model="injuryStatus"
                                class="form-radio" />
                            <span class="ml-2 text-slate-100 bg-emerald-600/85 hover:bg-emerald-600 border border-slate-800/65 py-1 px-3 rounded-md font-semibold
                            transition-colors duration-200 ">Igen</span>
                        </label>

                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="injuryStatus" value="nem" v-model="injuryStatus"
                                class="form-radio" />
                            <span class="ml-2 text-slate-100 bg-red-500/85 hover:bg-red-600 border border-slate-800/65 py-1 px-3 rounded-md font-semibold
                            transition-colors duration-200 ">Nem</span>
                        </label>
                    </div>
                </div>

                <div>
                    <FormKit type="textarea" name="description" v-model="description" label="Baleset részletes leírása"
                        placeholder="Részletezze a balesetet: hogyan történt, milyen sérülések keletkeztek..."
                        :validation="'required|min:10|max:255'" :validation-messages="{
                            required: 'A részletes leírás megadása kötelező!',
                            min: 'A leírásnak legalább 10 karakter hosszúnak kell lennie!',
                            max: 'A leírás legfeljebb 255 karakter lehet!'
                        }" outer-class="w-full" label-class="block text-lg font-semibold text-white mb-2"
                        input-class="w-full p-3 rounded-lg resize-y  max-h-32 min-h-16" />
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" style="font-family: 'Nunito';"
                        class="px-6 py-2 tracking-wider text-slate-50 bg-red-600/75 rounded-lg font-bold shadow-red-800 hover:bg-red-700 focus:ring-2 focus:ring-red-400 transition">
                        Bejelentés Elküldése
                    </button>
                </div>
            </form>
            <div class="w-5/6 mx-auto h-5/6 flex justify-center">
                <div class="w-full h-full border-8 rounded-2xl border-slate-200/65 mx-auto" id="map"></div>
            </div>
        </div>
        <div v-else class="w-full h-full mx-auto ">
            <BaseSpinner />
        </div>
    </div>
    <div
        class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-red-500/55 to-transparent my-16 rounded-full">
    </div>

</template>

<script setup>
import { ref, onMounted } from 'vue'
import { http } from '@utils/http.mjs'
import BaseSpinner from '@layouts/sliders/BaseSpinner.vue';

const map = ref(null)
const marker = ref(null)
const formattedDateTime = ref('')
const submitted = ref(false)
const description = ref('')
const loading = ref(false);


const onSubmit = () => {
    if (description.value.trim().length < 10) {
        alert('A leírás túl rövid!')
        return
    }
    emit('submit', { description: description.value })
    submitted.value = true
}
const updateDateTime = () => {
    const now = new Date()
    formattedDateTime.value = now.toISOString().slice(0, 16)
}
const loadGoogleMaps = async () => {
    try {
        const response = await http.get('/googlemapsapi')
        const { url, mapId } = response.data

        if (!url || !mapId) {
            console.error('Hiányzó URL vagy Map ID:', response.data)
            return
        }
        const script = document.createElement('script')
        script.src = url
        script.async = true
        script.defer = true
        script.onload = () => {
            console.log('Google Maps script betöltve.')
            initMap(mapId)
            initAutocomplete()
        }
        document.head.appendChild(script)
    } catch (error) {
        console.error('Google Maps betöltési hiba:', error)
    }
}

const initMap = (mapId) => {
    const defaultLocation = { lat: 47.497913, lng: 19.040236 }
    const mapElement = document.getElementById('map')

    if (!mapElement) {
        console.error('A térkép elem nem található.')
        return
    }

    map.value = new google.maps.Map(mapElement, {
        zoom: 12,
        center: defaultLocation,
        mapId: mapId,
    })

    marker.value = new google.maps.Marker({
        map: map.value,
        position: defaultLocation,
        title: 'Alapértelmezett helyszín',
        icon: {
            url: "http://backend.vm1.test/storage/googleMapsIcon/otvenes.png",
            scaledSize: new google.maps.Size(40, 40),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(20, 20),
        },
    })

    console.log('Térkép sikeresen inicializálva egyedi ikonnal.')
}

const initAutocomplete = () => {
    const inputElement = document.getElementById('location')
    if (!inputElement) {
        console.error('Az autocomplete input nem található.')
        return
    }

    const autocomplete = new google.maps.places.Autocomplete(inputElement, {
        types: ['geocode'],
        componentRestrictions: { country: 'hu' },
    })

    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace()
        if (place && place.geometry) {
            const location = place.geometry.location

            map.value.setCenter(location)
            map.value.setZoom(15)

            if (marker.value) {
                marker.value.setPosition(location)
            } else {
                console.error('A marker nem inicializált.')
            }
            console.log('Hely kiválasztva:', place.formatted_address)
        } else {
            console.error('Hely nem található vagy nincs megfelelő adat.')
        }
    })
}
onMounted(() => {
    loading.value = true;
    try {
        updateDateTime();
        loadGoogleMaps();
    } catch (error) {
        console.log("Hiba a Baleseti gyorsbejelentő oldalon.", error);
    } finally {
        loading.value = false;
    }
})
</script>

<style scoped>
:deep(.formkit-options) {
    counter-reset: radio-counter;
}

:deep(.formkit-option) {
    counter-increment: radio-counter;
}

:deep(.formkit-option:nth-child(odd) .formkit-label) {
    background-color: rgb(102, 203, 102) !important;
}

:deep(.formkit-option:nth-child(even) .formkit-label) {
    background-color: rgb(222, 81, 81) !important;
}

:deep(.formkit-option:nth-child(odd) .formkit-label),
:deep(.formkit-option:nth-child(even) .formkit-label) {
    padding: 0 .8rem !important;
    border-radius: .3rem !important;
    color: white !important;
}

#input_1-option-igen,
#input_2-option-nem {
    display: flex;
    align-items: center;
    margin: auto;
}
</style>