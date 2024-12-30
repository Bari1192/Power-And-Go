<template>
  <BaseLayout>
    <h1 class="text-6xl my-10 dark:text-white">Főoldal</h1>

    <CitigoModel />
    <KiaNiroModel />
    <EupModel />
    <VivaroModel />
    <KangooModel />

  </BaseLayout>
</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue'

import { mapState, mapActions } from 'pinia'
import VivaroModel from '@layouts/carmodels/VivaroModel.vue';
import KangooModel from '@layouts/carmodels/KangooModel.vue';
import EupModel from '@layouts/carmodels/EupModel.vue';
import KiaNiroModel from '@layouts/carmodels/KiaNiroModel.vue';
import CitigoModel from '@layouts/carmodels/CitigoModel.vue';

export default {
  components: {
    BaseLayout,
    VivaroModel,
    KangooModel,
    EupModel,
    KiaNiroModel,
    CitigoModel,
  },
}
</script>
