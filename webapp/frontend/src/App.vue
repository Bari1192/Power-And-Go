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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Rasa:ital,wght@0,300..700;1,300..700&display=swap" />
  <RouterView />
</template>
