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
import { http } from '@utils/http.mjs'

onMounted(async () => {
  try {
    const response = await http.get('/googlemapsapi');
    const scriptUrl = response.data.url;

    const script = document.createElement('script');
    script.src = scriptUrl;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
  } catch (error) {
    console.error('Hiba a Google Maps API betöltése során:', error);
  }
});
</script>