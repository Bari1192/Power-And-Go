<template>
  <BaseLayout>
    <h1 class="text-6xl my-10 dark:text-white">Főoldal</h1>
  </BaseLayout>
</template>

<script>
import Cars from './cars/cars.vue';
import BaseLayout from '@layouts/BaseLayout.vue'
import { useCounter } from '@stores/CounterStore.mjs'
import { mapState, mapActions } from 'pinia'
import Renthistory from './rents/renthistory.vue';
import fines from './bills/fines.vue';

export default {
  components: {
    Cars,
    BaseLayout,
    Renthistory,
    fines,
  },
  computed: {
    ...mapState(useCounter, ['counter'])
  },
  methods: {
    ...mapActions(useCounter, ['increment'])
  }
}
</script>
