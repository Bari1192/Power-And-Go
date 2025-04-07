<template>
    <div class="grid justify-center mx-auto w-full mt-12">
        <p class=" bg-emerald-700 mx-auto text-center px-8 py-3 rounded-lg mb-4">
        <h1 class="text-3xl text-white  mx-auto font-bold">Előfoglaláshoz a jármű leadási
            helyszínének kiválasztása</h1>
        </p>
    </div>
    <div class="border-b-8 mx-auto border-b-emerald-900/25 w-full lg:w-11/12 my-6 rounded-t-3xl shadow-md"></div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 font-semibold w-full my-12 ">
        <div>
            <label for="location" class="text-2xl  my-4 text-slate-700 block">Helyszín pontos címe:</label>
            <input id="location" type="text" class="border text-sky-900 font-semibold rounded-lg px-3 py-2 w-4/5"
                placeholder="Kezdje el beírni a címet..." ref="autocompleteInput" />
            <div class="mt-4 space-y-2 text-slate-700 ">
                <FormKit type="form" id="demageReport" :form-class="submitted ? 'hide' : 'show'"
                    submit-label="Bejelentés" @submit="onSubmit" :actions="false" :validation="'required'"
                    :validation-messages="{
                        required: 'Kérjük minden adatot töltsön ki!'
                    }">
                    <div class="w-full px-3">
                        <FormKit name="lastRenter" type="text" label="Munkatárs hozzárendelése"
                            label-class="text-xl my-2 text-slate-700 block"
                            input-class=" rounded-lg appearance-none block w-3/5 bg-gray-300 bg-opacity-90 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            placeholder="Opcionális" />

                    </div>

                    <div class="w-full px-3">
                        <FormKit type="datetime-local" v-model="formattedDateTime" label="Kiszállítás időpontja"
                            validation="required|date_after" :validation-messages="{
                                date_before: 'Nem lehet a kiszállítás korábbi időpontban!',
                                required: 'Kötelező megadni!'
                            }" input-class="text-sky-900 py-2 pr-4 rounded-xl text-center" inner-class="py-2"
                            label-class="text-xl mt-4 text-slate-700 block" />
                    </div>
                    <div class="my-4 border-t-4 border-gray-50/45 rounded-lg w-11/12 mx-auto">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-3">
                        <div class=" flex flex-col w-full mx-auto bg-green-100/75 py-2 px-3 rounded-lg">
                            <FormKit formtKit-class="text-xl my-4 py-4 text-slate-700 block" type="radio"
                                name="chargingCabelRequest" :options="['Igen', 'Nem']" label="Töltőkábel igényelt?" />
                        </div>
                        <div class=" flex flex-col w-full mx-auto bg-green-200/75 py-2 px-3 rounded-lg">
                            <FormKit formtKit-class="text-xl my-4 py-4 text-slate-700 block" type="radio"
                                name="insuranceRequest" :options="['Igen', 'Nem']" label="Biztosítást igényelt?" />
                        </div>
                    </div>
                    <div class="my-4 border-t-4 border-gray-50/45 rounded-lg w-11/12 mx-auto"></div>

                    <div class="w-full my-2 px-3">
                        <FormKit name="userAdminDescription" type="textarea"
                            label="A felhasználó kérései és egyéb megjegyzések"
                            placeholder="A ház előtti kapubeállóba szabadon leparkolható a jármű. Szabad kapacitás függvényében piros színű autót kíván az ügyfél."
                            v-model="description" :validation="'length:0,255'" :validation-messages="{
                                max: 'A megjegyzések hossza maximum 255 karakter lehet!',
                            }" label-class="text-slate-700 text-lg mt-4 font-semibold mb-2"
                            input-class="mt-2 max-h-28 min-h-16 w-full align-top appearance-none bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded-md py-3 px-4 focus:outline-none focus:bg-white focus:border-gray-500"
                            @input="value => description = value" />
                    </div>
                    <div
                        class="flex justify-center mx-auto mt-5 bg-orange-400 text-white py-2 px-5 w-2/5 text-xl
                            transition-colors ease-in-out duration-300 border-2 rounded-xl hover:bg-orange-200/75 hover:text-emerald-600 hover:border-lime-600 cursor-pointer">
                        <FormKit type="submit" label="Következő" id="button" class="" />
                    </div>
                </FormKit>
            </div>
        </div>
        <div class="mt-8 max-h-[600px] border-4 rounded-2xl border-emerald-300/75" id="map"></div>
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
#input_4-option-nem,
#input_5-option-nem {
    margin-right: 1rem;
    margin-top: 0.5rem;
}

#input_4-option-igen,
#input_5-option-igen {
    margin-right: 1rem;
    margin-bottom: 0.5rem;
}
</style>
