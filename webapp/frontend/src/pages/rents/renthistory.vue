<template>
    <BaseLayout>
        <div class="py-20">
            <div class="mx-auto space-x-8 text-center mb-6">
                <button @click="ChargeFines" class="switch-button">Töltési Büntetések</button>
                <button @click="RentSummary" class="switch-button">Bérlés Összesítők</button>
            </div>

            <div v-if="showChargeFines">
                <Lapozo :items="fees" :itemsPerPage="5" ref="lapozo">
                    <template v-slot:default="{ items }">
                        <transition-group :name="lapozo?.transitionDirection" tag="div">
                            <div v-for="fee in items" :key="fee.id" class="container mx-auto mb-10">
                                <ChargeFineEmail :fee="fee" />
                            </div>
                        </transition-group>
                    </template>
                </Lapozo>
            </div>

            <div v-if="showRentSummary">
                <Lapozo :items="rents" :itemsPerPage="5" ref="lapozo">
                    <template v-slot:default="{ items }">
                        <transition-group :name="lapozo?.transitionDirection" tag="div">
                            <div v-for="rent in items" :key="rent.id" class="container mx-auto mb-10">
                                <RentSummaryEmail :rent="rent" />
                            </div>
                        </transition-group>
                    </template>
                </Lapozo>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import { ref } from 'vue';
import BaseLayout from '@layouts/BaseLayout.vue';
import ChargeFineEmail from '@pages/rents/emails/ChargeFineEmail.vue';
import RentSummaryEmail from '@pages/rents/emails/RentSummaryEmail.vue';
import Lapozo from '@layouts/sliders/Lapozo.vue';
import { http } from '@utils/http.mjs';

export default {
    components: { BaseLayout, ChargeFineEmail, RentSummaryEmail, Lapozo },
    setup() {
        const rents = ref([]);
        const fees = ref([]);
        const showChargeFines = ref(false);
        const showRentSummary = ref(false);
        const lapozo = ref(null);

        async function fetchData() {
            try {
                const resp = await http.get('/bills/closedrentsbills');
                rents.value = resp.data.data;
            } catch (error) {
                console.error('Hiba történt az API hívás során:', error);
            }
            try {
                const resp = await http.get('/bills/fees');
                fees.value = resp.data.data;
            } catch (error) {
                console.error('Hiba történt az API hívás során:', error);
            }
        }

        function ChargeFines() {
            showChargeFines.value = !showChargeFines.value;
            if (showChargeFines.value) showRentSummary.value = false;
        }

        function RentSummary() {
            showRentSummary.value = !showRentSummary.value;
            if (showRentSummary.value) showChargeFines.value = false;
        }

        fetchData();

        return { rents, fees, showChargeFines, showRentSummary, ChargeFines, RentSummary, lapozo };
    }
};
</script>

<style scoped>
.switch-button {
    @apply cursor-pointer mx-2 text-lg focus:outline-none text-white focus:ring-4 focus:ring-sky-700 font-medium rounded-lg px-5 py-2.5 bg-sky-600 hover:bg-sky-700;
}

.forward-enter-active,
.forward-leave-active,
.backward-enter-active,
.backward-leave-active {
    transition: all 1s ease-in-out;
}

.forward-enter-from {
    opacity: 0;
    transform: translateX(50px);
}

.forward-leave-to {
    opacity: 0;
    transform: translateX(-50px);
}

.backward-enter-from {
    opacity: 0;
    transform: translateX(-50px);
}

.backward-leave-to {
    opacity: 0;
    transform: translateX(50px);
}
</style>
