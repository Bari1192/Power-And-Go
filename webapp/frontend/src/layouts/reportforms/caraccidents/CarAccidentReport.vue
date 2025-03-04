<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 font-semibold">
        <div>
            <label for="location" class="text-2xl  my-4 text-white block">Baleset pontos helyszíne:</label>
            <input id="location" type="text" class="border text-sky-900 font-semibold rounded-lg px-3 py-2 w-4/5"
                placeholder="Kezdje el beírni a címet..." ref="autocompleteInput" />
            <div class="mt-4 space-y-2 text-white ">
                <FormKit type="form" id="demageReport" :form-class="submitted ? 'hide' : 'show'"
                    submit-label="Bejelentés" @submit="onSubmit" :actions="false" :validation="'required'"
                    :validation-messages="{
                        required: 'Kérjük minden adatot töltsön ki!'
                    }">
                    <div class="w-full px-3">
                        <FormKit name="lastRenter" type="text" label="Legutóbbi bérlő"
                            label-class="text-xl my-2 text-white block" :value="lastRenter" disabled
                            input-class=" rounded-lg appearance-none block w-3/5 bg-gray-300 bg-opacity-90 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                    </div>

                    <div class="w-full px-3">
                        <FormKit type="datetime-local" v-model="formattedDateTime" label="Mikor történt??"
                            validation="required|date_before" :validation-messages="{
                                date_before: 'Nem lehet a baleset későbbi időpontban!',
                                required: 'Kötelező megadni!'
                            }" validation-visibility="live" input-class="text-sky-900 py-2 pr-4 rounded-xl text-center"
                            inner-class="py-2" label-class="text-xl mt-4 text-white block" />
                    </div>

                    <div class="w-full px-3 mb-2">
                        <FormKit formtKit-class="text-xl my-4 py-4 text-white block" type="radio" name="someOneInjured"
                            :options="['Igen', 'Nem']" label="Személyi sérülés történt?" />
                    </div>
                    <div class="w-full my-2 px-3">
                        <FormKit name="AccidentDescription" type="textarea" label="Baleset tömör leírása"
                            placeholder="Mennyire sérült az autó? Forgalmat zavarja? Le tudta állítani?"
                            v-model="description" :validation="'required|length:10,255'" :validation-messages="{
                                length: 'A bejelentés szövege min 20, maximum 255 karakter hosszú lehet!',
                                required: 'Kötelező kitölteni!'
                            }" label-class="text-white text-lg mt-4 font-semibold mb-2"
                            input-class="mt-2 max-h-28 min-h-16 w-full align-top appearance-none bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded-md py-3 px-4 focus:outline-none focus:bg-white focus:border-gray-500"
                            @input="value => description = value" />
                    </div>
                    <div
                        class="flex justify-center mx-auto mt-5 bg-red-600 py-2 px-5 w-2/5 text-xl  border-2 rounded-xl hover:bg-red-800 hover:border-sky-300">
                        <FormKit type="submit" label="Bejelentés" id="button" />
                    </div>
                </FormKit>
            </div>
        </div>
        <div class="mt-8 h-full border-8 rounded-2xl border-sky-300" id="map"></div>
    </div>
</template>


<script>
import { ref, onMounted } from 'vue';
import { http } from '@utils/http.mjs';

export default {
    data() {
        return {
            map: null,
            marker: null,
        }
    },
    props: {
        lastRenter: {
            type: [String, null],
            required: true,
        },
    },
    setup(props, { emit }) {
        const map = ref(null);
        const marker = ref(null);
        const formattedDateTime = ref('');
        const submitted = ref(false);
        const description = ref('');

        const onSubmit = () => {
            if (description.value.trim().length < 10) {
                alert('A leírás túl rövid!');
                return;
            }
            emit('submit', { description: description.value });
            submitted.value = true;
        };

        const updateDateTime = () => {
            const now = new Date();
            formattedDateTime.value = now.toISOString().slice(0, 16);
        };

        const loadGoogleMaps = async () => {
            try {
                const response = await http.get('/googlemapsapi');
                const { url, mapId } = response.data;

                if (!url || !mapId) {
                    console.error('Hiányzó URL vagy Map ID:', response.data);
                    return;
                }

                const script = document.createElement('script');
                script.src = url;
                script.async = true;
                script.defer = true;
                script.onload = () => {
                    console.log('Google Maps script betöltve.');
                    initMap(mapId);
                    initAutocomplete();
                };

                document.head.appendChild(script);
            } catch (error) {
                console.error('Google Maps betöltési hiba:', error);
            }
        };
        const initMap = (mapId) => {
            const defaultLocation = { lat: 47.497913, lng: 19.040236 }; 
            const mapElement = document.getElementById('map');

            if (!mapElement) {
                console.error('A térkép elem nem található.');
                return;
            }

            map.value = new google.maps.Map(mapElement, {
                zoom: 12,
                center: defaultLocation,
                mapId: mapId,
            });

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
            });

            console.log('Térkép sikeresen inicializálva egyedi ikonnal.');
        };
        const initAutocomplete = () => {
            const inputElement = document.getElementById('location');
            if (!inputElement) {
                console.error('Az autocomplete input nem található.');
                return;
            }

            const autocomplete = new google.maps.places.Autocomplete(inputElement, {
                types: ['geocode'],
                componentRestrictions: { country: 'hu' },
            });

            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();
                if (place && place.geometry) {
                    const location = place.geometry.location;

                    map.value.setCenter(location);
                    map.value.setZoom(15);

                    if (marker.value) {
                        marker.value.setPosition(location);
                    } else {
                        console.error('A marker nem inicializált.');
                    }
                    console.log('Hely kiválasztva:', place.formatted_address);
                } else {
                    console.error('Hely nem található vagy nincs megfelelő adat.');
                }
            });
        };
        onMounted(() => {
            updateDateTime();
            loadGoogleMaps();
        });
        return {
            formattedDateTime,
            submitted,
            description,
            onSubmit,
        };
    },
};
</script>

<style>
#input_2-option-nem{
    margin-right: 1rem;
    margin-top: 0.5rem;
}
 #input_2-option-igen{
    margin-right: 1rem;
    margin-bottom: 0.5rem;
}
.pac-container {
    z-index: 10000; 
    border: 3px solid rgb(62, 137, 167);
    border-radius: 12px; 
    font-family: Arial, sans-serif;
    background-color: rgb(235, 240, 243);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.pac-item {
    cursor: pointer; 
    padding: 5px 5px;
    font-size: 12px;
    color: #333; 
    border-bottom: 1px solid #e0e0e0;
    font-weight:600;

}
.pac-item:hover {
    background-color: #f1f8e9; 
    color: rgb(30, 90, 33); 
}
.pac-item:last-child {
    border-bottom: none;
}
</style>
