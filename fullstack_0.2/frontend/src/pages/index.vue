<template>
  <BaseLayout>
    <h1 class="text-6xl my-10 dark:text-white">Főoldal</h1>
    <CarAccidentReport />
  </BaseLayout>
</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue';
import CarAccidentReport from '@layouts/carmodels/caraccidents/CarAccidentReport.vue';

export default {
  components: {
    BaseLayout,
    CarAccidentReport,
  },
};
</script>
