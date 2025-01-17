<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 font-semibold">
        <div>
            <label for="location" class="text-2xl  my-4 text-white block">accident helyszíne:</label>
            <input id="location" type="text" class="border rounded-lg px-3 py-2 w-4/5"
                placeholder="Kezdje el beírni a címet..." ref="autocompleteInput" />
            <div class="mt-4 space-y-2 text-white ">
                <FormKit 
                    type="form" 
                    id="demageReport"
                    :form-class="submitted ? 'hide' : 'show'"
                    submit-label="Bejelentés"
                    @submit="onSubmit" 
                    :actions="false" 
                    :validation="'required'"
                    :validation-messages="{
                        required: 'Kérjük minden adatot töltsön ki!'
                    }">
                    <div class="w-full px-3">
                        <FormKit name="lastRenter" type="text" label="Legutóbbi bérlő"
                            label-class="text-xl my-2 text-white block" :value="lastRenter" disabled
                            input-class=" rounded-lg appearance-none block w-3/5 bg-gray-300 bg-opacity-90 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                    </div>

                    <div class="w-full px-3">
                        <FormKit type="datetime-local" v-model="formattedDateTime" label="Mikor történt a accident?"
                            validation="required|date_before" :validation-messages="{
                                date_before: 'Nem lehet a accident későbbi időpontban!',
                                required: 'Kötelező megadni!'
                            }" validation-visibility="live" input-class="text-sky-900 py-2 pr-4 rounded-xl text-center"
                            inner-class="py-2" label-class="text-xl mt-4 text-white block" />
                    </div>

                    <div class="w-full px-3">
                        <FormKit formtKit-class="text-xl my-4 py-4 text-white block" type="radio" name="someOneInjured"
                            :options="['Igen', 'Nem']" label="Személyi sérülés történt?" />
                    </div>
                    <div class="w-full my-2 px-3">
                        <FormKit name="AccidentDescription" type="textarea" label="accident rövid leírása"
                            placeholder="Mennyire sérült az autó? Forgalmat zavarja? Le tudta állítani?"
                            v-model="description" :validation="'required|length:10,255'" :validation-messages="{
                                length: 'A bejelentés szövege min 20, maximum 255 karakter hosszú lehet!',
                                required: 'Kötelező kitölteni!'
                            }" label-class="text-white text-lg mt-4 font-semibold mb-2"
                            input-class="max-h-28 min-h-16 w-full align-top appearance-none bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded-md py-3 px-4 focus:outline-none focus:bg-white focus:border-gray-500"
                            @input="value => description = value" />
                    </div>
                    <div
                        class="flex justify-center mx-auto mt-5 bg-red-600 py-2 px-5 w-1/4 text-xl  border-2 rounded-xl hover:bg-red-800 hover:border-yellow-600">
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
            map :null,
            marker : null,
        }
    },
    props: {
        lastRenter: {
            type: [String, null],
            required: true,
        },
    },
    setup(props, { emit }) {
        const formattedDateTime = ref('');
        const autocompleteInput = ref(null);

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
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            formattedDateTime.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        };

        const loadGoogleMaps = async () => {
            if (typeof google !== 'undefined' && google.maps) {
                initMap();
                return;
            }

            try {
                const response = await http.get('/googlemapsapi');
                const scriptUrl = response.data.url;

                const script = document.createElement('script');
                script.src = scriptUrl;
                script.async = true;
                script.defer = true;
                script.onload = () => {
                    initMap();
                    initAutocomplete();
                };

                document.head.appendChild(script);
            } catch (error) {
            }
        };

        const initMap = () => {
            if (typeof google === 'undefined' || !google.maps) {
                return;
            }

            const defaultLocation = { lat: 47.497913, lng: 19.040236 }; // Budapest az alap.
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: defaultLocation,
                mapId: '<MAP_ID>', // Backendről jön a MAP-ID!
            });

            marker = new google.maps.marker.AdvancedMarkerElement({
                map,
                position: defaultLocation,
                title: 'Alapértelmezett helyszín',
                content: createCustomIcon(), //Az egyedi ikont itt behívni!
            });

            // Ikon személyre szabása
            function createCustomIcon() {
                const div = document.createElement('div');
                div.style.width = '40px';
                div.style.height = '40px';
                div.style.backgroundImage = 'url("http://backend.vm1.test/storage/googleMapsIcon/otvenes.png")';
                div.style.backgroundSize = 'cover';
                return div;
            }
        };

        const initAutocomplete = () => {
            const inputElement = document.getElementById('location');
            const autocomplete = new google.maps.places.Autocomplete(inputElement, {
                types: ['geocode'],
                componentRestrictions: { country: 'hu' },
            });

            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();
                if (place && place.geometry) {
                    const location = place.geometry.location;

                    map.setCenter(location);
                    map.setZoom(14);

                    marker.position = location;
                    marker.title = place.formatted_address;
                } else {
                    console.error('Hely nem található.');
                }
            });
        };

        onMounted(() => {
            updateDateTime();
            loadGoogleMaps();
        });

        return {
            formattedDateTime,
            autocompleteInput,

            submitted,
            description,
            onSubmit,
        };
    },
};
</script>
