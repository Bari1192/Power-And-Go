<script setup>
import { useAuthStore } from '@stores/AuthenticationStore';
import { onMounted } from 'vue';

const authStore = useAuthStore();

onMounted(async () => {
  await authStore.initializeFromStorage();
});
</script>

<template>
  <RouterView />
</template>
