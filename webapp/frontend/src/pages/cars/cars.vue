<template>
    <BaseLayout>
        <div class="container mx-auto mb-20">
            <div class="col">
                <div class="row">
                    <p class="text-5xl py-4 px-6 rounded-2xl bg-emerald-500 shadow-xl border-4 border-green-700 flex align-middle mx-auto justify-center w-fit text-center mt-10 text-sky-100 font-semibold">Autók Megjelenítése</p>
                    <div class=" w-2/5 border-b-4 mx-auto border-slate-600 mt-3 mb-5 shadow-xl"></div>
                    <div class=" w-2/3 border-b-4 mx-auto border-slate-600 mt-0 mb-6 shadow-xl"></div>
                </div>
                <BaseTable v-if="!isLoading" :cars="carsStore.cars" />
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from "@layouts/BaseLayout.vue";
import BaseTable from "@layouts/BaseTable.vue";
import { useCarStore } from "@stores/carStore";
import { onMounted} from "vue";

const carsStore = useCarStore()

onMounted(async () => {
  await carsStore.getCars();
});

</script>