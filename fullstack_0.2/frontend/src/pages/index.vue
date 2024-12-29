<template>
  <BaseLayout>
    <h1 class="text-6xl my-10 dark:text-white">Főoldal</h1>

    <EupModel />



  </BaseLayout>
</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue'

import EupModel from '@layouts/carmodels/EupModel.vue';

import { mapState, mapActions } from 'pinia'

export default {
  components: {
    BaseLayout,
    EupModel,
  },
}
</script>
