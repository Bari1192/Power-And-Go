<template>
    <h1 class="text-5xl font-bold text-sky-100 italic mt-10 mb-4">
        Büntetések
        <button @click="rentBillDetails" :disabled="!carBills.length"
            class="flex items-center justify-center font-bold rounded-full"
            :class="carBills.length ? 'bg-indigo-500 hover:bg-indigo-700 text-white' : 'bg-gray-500 text-gray-400 cursor-not-allowed'"
            :style="{
                transform: rentBillOpen ? 'rotate(90deg)' : 'rotate(-90deg)',
                backgroundColor: carBills.length ? '#4F46E5' : '#6B7280',
                color: carBills.length ? 'white' : '#a3a3a3',
                cursor: carBills.length ? 'pointer' : 'default'
            }"
            style="width: 34px; height: 36px; font-size: 2.5rem; line-height: 100px; padding-bottom: 10px; border: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 1s;">
            +
        </button>
    </h1>
    <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>
    <div v-if="!carBills.length">
        <p class="text-gray-200 font-semibold italic px-2 text-lg">Ehhez az autóhoz nem tartozik egyetlen bírság sem</p>
    </div>
    <transition name="fade-slide">
        <div v-if="rentBillOpen">
            <div v-for="fine in carBills" :key="fine.szamla_azon">
                <BaseCard class="cursor-pointer" :class="rentBillDetailsOpenStates[fine.szamla_azon] ? 'h-44' : 'h-10'"
                    :title="fine.szamla_tipus === 'toltes_buntetes' ? 'Akkumulátor lemerítési & szállítási pótdíj - ' + fine.osszeg : fine.szamla_tipus"
                    @click="toggleBillDetails(fine.szamla_azon)">
                    <!-- Részletek megjelenítése kattintásra -->
                    <div class="cursor-default grid grid-cols-3 gap-2"
                        v-if="rentBillDetailsOpenStates[fine.szamla_azon]">
                        <p><b>Számla sorszáma:</b> {{ fine.szamla_azon }}</p>
                        <p><b>Összege:</b> {{ fine.osszeg }} Ft</p>
                        <p><b>Levezetett út:</b> {{ fine.megtett_tavolsag }} km</p>
                        <p><b>Parkolási idő: </b>{{ fine.parkolasi_perc }} perc</p>
                        <p><b>Vezetési idő:</b> {{ fine.vezetesi_perc }} perc</p>
                        <p><b>Bérlés kezdete:</b> {{ fine.berles_kezd_datum }} {{ fine.berles_kezd_ido }}</p>
                        <p><b>Bérlés vége: </b> {{ fine.berles_veg_datum }} {{ fine.berles_veg_ido }}</p>
                        <p> <b>Kiállítva: </b>{{ fine.szamla_kelt }}</p>
                        <p>
                            <b>Számla állapota: </b>
                            <span
                                :style="fine.szamla_status === 'pending' ? 'color:orange; font-weight:bold;font-style:italic;' : ''">
                                {{ fine.szamla_status }}
                            </span>
                        </p>
                    </div>
                </BaseCard>
            </div>
        </div>
    </transition>








</template>



<script>
import { http } from '@utils/http'
import BaseCard from '@layouts/BaseCard.vue'
import BaseLayout from "@layouts/BaseLayout.vue";
import { nextTick } from 'vue';

export default {
    components: {
        BaseCard,
        BaseLayout,
    },
    data() {
        return {
            car: {},
            carBills: [],
            carRentBills: [],
            carOpen: false,
            noteOpen: false,
            rentHistoryOpen: false,
            rentBillOpen: false,
            rentBillDetailsOpenStates: {},
        }
    },
    async mounted() {
        const response = await http.get(`/cars/${this.$route.params.id}`);
        this.car = response.data.data;

        const billsresponse = await http.get(`/renthistories/filterCarHistory/toltes_buntetes/${this.$route.params.id}`);
        this.carBills = billsresponse.data.data;

        const rentresponse = await http.get(`/renthistories/filterCarHistory/berles/${this.$route.params.id}`);
        this.carRentBills = rentresponse.data.data;

    },
    methods: {
        async cardetails() {
            this.carOpen = !this.carOpen;
            if (this.carOpen) {
                await nextTick();
                this.$refs.adatokAlja.scrollIntoView({ behavior: 'smooth' });
            }
        },
        async noteDetails() {
            this.noteOpen = !this.noteOpen;
            if (this.noteOpen) {
                await nextTick();
                this.$refs.noteBottom.scrollIntoView({ behavior: 'smooth' });
            }
        },
        async rentHistoryDetails() {
            this.rentHistoryOpen = !this.rentHistoryOpen;
            if (this.rentHistoryOpen) {
                await nextTick();
                this.$refs.rentHistoryBottom.scrollIntoView({ behavior: 'smooth' });
            }
        },
        async rentBillDetails() {
            this.rentBillOpen = !this.rentBillOpen;

            // Inicializáljuk az állapotokat minden számlához
            if (this.rentBillOpen) {
                this.carBills.forEach((bill) => {
                    this.$set(this.rentBillDetailsOpenStates, bill.szamla_azon, false);
                });
            }
        },
        toggleBillDetails(szamla_azon) {
            // Az adott számla nyitási állapotának váltása
            this.rentBillDetailsOpenStates[szamla_azon] =
                !this.rentBillDetailsOpenStates[szamla_azon];
        },
    }
}

</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 3s ease-in-out;
}

.fade-slide-enter,
.fade-slide-leave-to {
    transform: translateY(-30px);
    opacity: 0;
    transition: all 1s ease-in-out;
}
</style>