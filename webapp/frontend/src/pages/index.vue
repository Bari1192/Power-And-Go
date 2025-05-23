<template>
    <BaseHeader />

    <NewInspiration />
    <Sustainable />
    <ZeroEmissionSection />
    <CompanyOfferSection />


    <BaseFooter />
</template>

<script setup>
import BaseHeader from '@layout/BaseHeader.vue';
import BaseFooter from '@layout/BaseFooter.vue';
import NewInspiration from '@/pages/indexpage/NewInspiration.vue';
import { useAuthStore } from '@stores/AuthenticationStore';
import { onMounted } from 'vue';
import Sustainable from '@/pages/indexpage/Sustainable.vue';
import ZeroEmissionSection from '@/pages/indexpage/ZeroEmissionSection.vue';
import CompanyOfferSection from '@/pages/indexpage/CompanyOfferSection.vue';

const authStore = useAuthStore();
onMounted(async () => {
    await authStore.initializeFromStorage();
})



</script>

<style scoped>
.main {
    background: url('@img/Welcome/blocks3.webp');
    background-position: center;
    background-repeat: repeat-y;
    background-size: contain;
    width: 100%;
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}
</style>