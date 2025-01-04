<template>
    <div class="flex flex-col my-8">
        <label for="location" class="text-2xl mb-4 text-sky-50 font-semibold">
            Baleset helyszíne:
        </label>
        <input id="location" type="text" class="border rounded px-3 py-2 w-1/4"
            placeholder="Kezdje el beírni a címet..." ref="autocompleteInput" />
    </div>
</template>


<script>
export default {
    mounted() {
        this.waitForGoogleMapsAPI(() => {
            this.initializeAutocomplete();
        });
    },
    methods: {
        waitForGoogleMapsAPI(callback) {
            if (typeof google !== "undefined" && google.maps) {
                callback(); // Google Maps API elérhető
            } else {
                // Ellenőrzi 500ms-onként, hogy betöltődött-e az API
                const interval = setInterval(() => {
                    if (typeof google !== "undefined" && google.maps) {
                        clearInterval(interval);
                        callback();
                    }
                }, 500);
            }
        },
        initializeAutocomplete() {
            const input = this.$refs.autocompleteInput;

            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['geocode'], // Csak címek
                componentRestrictions: { country: 'hu' }, // Csak Magyarország
            });

            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();
            });
        },
    },
};
</script>