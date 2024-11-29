<template>
  <BaseLayout>
    <h1 class="text-6xl my-10 dark:text-white">Hello!</h1>

    <Cars /> <!-- AUTOK OLDAL IDE TÖLTVE! -->

    <button class="bg-blue-500 text-white rounded py-2 px-4" @click="increment()">
      Számláló: {{ counter }}
    </button>
  </BaseLayout>
</template>

<script>
import Cars from './cars/cars.vue';
import BaseLayout from '@layouts/BaseLayout.vue'
import { useCounter } from '@stores/CounterStore.mjs'
import { mapState, mapActions } from 'pinia'
import Renthistory from './rents/renthistory.vue';

export default {
  components: {
    Cars,
    BaseLayout,
    Renthistory
  },
  computed: {
    ...mapState(useCounter, ['counter'])
  },
  methods: {
    ...mapActions(useCounter, ['increment'])
  }
}
</script>
