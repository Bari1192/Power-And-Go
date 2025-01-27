<template>
  <BaseLayout>
    <MainPage />
  </BaseLayout>

</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue';
import MainPage from './mainpage.vue';

export default {
  components: {
    BaseLayout,
    MainPage,
  },
};
</script>
