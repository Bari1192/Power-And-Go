<script setup>
import { useAuthStore } from '@stores/AuthenticationStore';
import { useCarStore } from '@stores/carStore';
import { onMounted } from 'vue';

const authStore = useAuthStore();
const carStore = useCarStore();

onMounted(async () => {
  await authStore.initializeFromStorage();
  await carStore.getCars();
  await carStore.getAllFinesBill();
});
</script>

<template>
  <RouterView />
</template>
