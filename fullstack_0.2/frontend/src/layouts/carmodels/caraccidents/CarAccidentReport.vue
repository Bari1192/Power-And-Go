<template>
    <div>
        <label for="location" class="text-2xl my-4 text-white font-semibold">Baleset helyszíne:</label>
        <input id="location" type="text" class="border rounded px-3 py-2 w-full"
            placeholder="Kezdje el beírni a címet..." ref="autocompleteInput" />
        <div id="map" class="w-3/4 h-96 p-6 mx-auto my-6 border-8 rounded-2xl border-sky-300"></div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { http } from '@utils/http.mjs';

export default {
    setup() {
        const autocompleteInput = ref(null);
        let map = null;
        let marker = null;

        const loadGoogleMaps = async () => {
            if (typeof google !== 'undefined' && google.maps) {
                console.log('Google Maps API már betöltve.');
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
                    console.log('Google Maps API betöltve.');
                    initMap();
                    initAutocomplete();
                };

                document.head.appendChild(script);
            } catch (error) {
                console.error('Hiba a Google Maps API betöltésekor:', error);
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
            loadGoogleMaps();
        });

        return {
            autocompleteInput,
        };
    },
};
</script>
