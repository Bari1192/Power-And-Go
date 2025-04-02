<template>
    <BaseLayout>
        <div class="my-6">
            <div class="container mx-auto mb-20 bg-white/60 py-6 rounded-3xl">
                <div class="col">
                    <div class="row">
                        <p
                            class="text-5xl py-4 px-6 rounded-2xl bg-emerald-500 shadow-xl border-4 border-green-700 flex align-middle mx-auto justify-center w-fit text-center mt-10 text-sky-100 font-semibold">
                            Autók Megjelenítése</p>
                        <div class=" w-2/5 border-b-4 mx-auto border-slate-600 mt-3 mb-5 shadow-xl"></div>
                        <div class=" w-2/3 border-b-4 mx-auto border-slate-600 mt-0 mb-6 shadow-xl"></div>
                    </div>

                    <div v-if="!isLoading">
                        <BaseTable :cars="carsStore.cars" @goToCar="goToCar" />
                    </div>
                    <div v-else-if="isLoading" class="text-center">
                        <p class="text-3xl text-red-600 font-medium">Betöltés folyamatban...</p>
                    </div>
                    <div v-else>
                        <p class="text-3xl text-gray-500 font-medium">Nem találhatóak autók az adatbázisban!</p>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from "@layouts/BaseLayout.vue";
import BaseTable from "@layouts/BaseTable.vue";
import { onMounted, ref } from "vue";
import { useCarStore } from "@stores/carStore";
import { storeToRefs } from 'pinia';

const carsStore = useCarStore()
const { cars } = storeToRefs(carsStore);
const adatok = ref([]);
const isLoading = ref(true);
const goToCar = ref(null);

onMounted(async () => {
    await carsStore.getCars();
    isLoading.value = false;
});

</script>