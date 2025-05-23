<template>
    <BaseLayout>
        <div class="min-h-screen bg-slate-900 px-4 py-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header & Navigation -->
                <div
                    class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 mb-8 border border-emerald-500/20">
                    <h1 class="text-3xl font-bold text-white mb-6 text-center">Bérlési Előzmények</h1>

                    <!-- Navigation Tabs -->
                    <div class="flex justify-center gap-4">
                        <button @click="ChargeFines"
                            :class="{ 'bg-emerald-600': showChargeFines, 'bg-slate-700': !showChargeFines }"
                            class="px-6 py-3 rounded-xl text-white font-medium flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-rose-500 bg-white rounded-full"></i>
                            Töltési Büntetések
                        </button>
                        <button @click="RentSummary"
                            :class="{ 'bg-emerald-600': showRentSummary, 'bg-slate-700': !showRentSummary }"
                            class="px-6 py-3 rounded-xl text-white font-medium flex items-center gap-2">
                            <i class="fas fa-history text-amber-400"></i>
                            Bérlés Összesítők
                        </button>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="space-y-6">
                    <!-- Töltési Büntetések -->
                    <div v-if="showChargeFines" class="space-y-6">
                        <Lapozo :items="fees" :itemsPerPage="5" ref="lapozo" class="space-y-6">
                            <template v-slot:default="{ items }">
                                <transition-group :name="lapozo?.transitionDirection" tag="div" class="space-y-6">
                                    <div v-for="fee in items" :key="fee.id">
                                        <ChargeFineEmail :fee="fee" />
                                    </div>
                                </transition-group>
                            </template>
                        </Lapozo>
                    </div>

                    <!-- Bérlés Összesítők -->
                    <div v-if="showRentSummary" class="space-y-6">
                        <Lapozo :items="rents" :itemsPerPage="5" ref="lapozo" class="space-y-6">
                            <template v-slot:default="{ items }">
                                <transition-group :name="lapozo?.transitionDirection" tag="div" class="space-y-20">
                                    <div v-for="rent in items" :key="rent.id">
                                        <RentSummaryEmail :rent="rent" />
                                    </div>
                                </transition-group>
                            </template>
                        </Lapozo>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import { ref } from 'vue';
import BaseLayout from '@layouts/BaseLayout.vue';
import ChargeFineEmail from '@pages/rents/emails/ChargeFineEmail.vue';
import RentSummaryEmail from '@pages/rents/emails/RentSummaryEmail.vue';
import Lapozo from '@layouts/sliders/Lapozo.vue';
import { http } from '@utils/http.mjs';

const rents = ref([]);
const fees = ref([]);
const showChargeFines = ref(false);
const showRentSummary = ref(false);
const lapozo = ref(null);

const fetchData = async () => {
    try {
        const [rentsResp, feesResp] = await Promise.all([
            http.get('/bills/closedrentsbills'),
            http.get('/bills/fees')
        ]);
        rents.value = rentsResp.data.data;
        fees.value = feesResp.data.data;
    } catch (error) {
        console.error('Hiba történt az API hívás során:', error);
    }
};

const ChargeFines = () => {
    showChargeFines.value = !showChargeFines.value;
    if (showChargeFines.value) showRentSummary.value = false;
};

const RentSummary = () => {
    showRentSummary.value = !showRentSummary.value;
    if (showRentSummary.value) showChargeFines.value = false;
};

fetchData();
</script>

<style scoped>
.forward-enter-active,
.forward-leave-active,
.backward-enter-active,
.backward-leave-active {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.forward-enter-from,
.backward-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.forward-leave-to,
.backward-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
