<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 font-semibold">
        <!-- Első oszlop -->
        <div>
            <label for="location" class="text-2xl  my-4 text-white block">Baleset helyszíne:</label>
            <input id="location" type="text" class="border rounded px-3 py-2 w-3/5"
                placeholder="Kezdje el beírni a címet..." ref="autocompleteInput" />
            <div class="mt-4 space-y-2 text-white">
                <!-- FormKit datetime mező -->
                <FormKit
                    type="datetime-local"
                    v-model="formattedDateTime"
                    label="Mikor történt a baleset?"
                    validation="[required|date_before|time_before]"
                    :validation-messages="{
                        time_before: 'NHibás időpont!' ,
                        date_before: 'Nem lehet a mai napnál későbbi a baleset ideje!' ,
                        required:'Kötelező megadni!'
                    }"
                    validation-visibility="live"

                    input-class="text-sky-900 py-2 pr-4 rounded-xl text-center"
                    inner-class="py-2"
                    
                />

                <FormKit type="form" #default="{ value }" :actions="false">
                    <FormKit formtKit-class=" space-y-10" type="radio" name="someOneInjured" :options="['Igen', 'Nem']"
                        label="Személyi sérülés történt?" />
                </FormKit>


                <p></p>
                <p>Állítsa le a motort!</p>
                <p>Kapcsolja be a Vészvillogót!</p>
                <p>Biztonsági mellényt vegye fel!</p>
                <p>Helyezze ki az elakadásjelző háromszöget!</p>
                <p>Készítsen fényképet az autókról, helyszínről, minden lényeges körülményről!</p>
                <p>Készítse elő a személyes okmányait!</p>
                <p>Kollégánk hamarosan érkezik a helyszínre. Mindenképpen maradjon a helyszínen!</p>
            </div>
        </div>

        <!-- Második oszlop -->
        <div class="h-96 border-8 rounded-2xl border-sky-300" id="map"></div>
    </div>
</template>


<script>
import { ref, onMounted } from 'vue';
import { http } from '@utils/http.mjs';

export default {
    setup() {
        const formattedDateTime = ref('');
        const autocompleteInput = ref(null);

        let map = null;
        let marker = null;

        // Dátum formázása a FormKit számára
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
                console.error('Google Maps API még nem áll készen.');
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
        };
    },
};
</script>

<style>
[data-invalid] .formkit-inner::after {
  content: '❌'; /* Piros X ikon */
  font-size: larger;
  margin-left: 8px;
  display: inline-flex;
  border: 1px red dotted;
  color: red;
}
[data-invalid] .formkit-messages {
  color: red;
  font-style: italic;
}

[data-complete] .formkit-inner::after {
  content: '✅'; /* Zöld pipa ikon */
  display: inline-flex;
  color: green;
  margin-left: 8px;
}
</style>