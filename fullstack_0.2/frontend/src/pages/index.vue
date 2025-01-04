<template>
  <BaseLayout>
    <h1 class="text-6xl my-10 dark:text-white">Főoldal</h1>

    <CarAccidentReport />

  </BaseLayout>
</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue'
import CarAccidentReport from '@layouts/carmodels/caraccidents/CarAccidentReport.vue';

export default {
  components: {
    BaseLayout,
    CarAccidentReport,
  },
}
</script>

<script setup>
import { onMounted } from 'vue';
onMounted(() => {
  if (!document.getElementById('google-maps-script')) {
    const script = document.createElement('script');
    script.id = 'google-maps-script'; // Egyedi azonosító a script számára
    script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyBt2dTfggXDgUgNR9jdKL7xysDeKcy28Og&libraries=places`;
    script.async = true;
    script.defer = true;

    // A script hozzáadása a dokumentum fejéhez
    document.head.appendChild(script);

    // Betöltés utáni inicializálás
    script.onload = () => {
      initializeAutocomplete();
    };
  } else {
    initializeAutocomplete();
  }
});

// Az Autocomplete funkció inicializálása
function initializeAutocomplete() {
  const input = document.getElementById('location');

  const autocomplete = new google.maps.places.Autocomplete(input, {
    types: ['geocode'], // Csak címek
    componentRestrictions: { country: 'hu' }, // Magyarország
  });

  autocomplete.addListener('place_changed', () => {
    const place = autocomplete.getPlace();
    if (place && place.geometry) {
      console.log('Kiválasztott hely:', place.formatted_address);
    }
  });
}
</script>