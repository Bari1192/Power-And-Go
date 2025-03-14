<template>
    <BaseLayout>
        <div class="container mb-20">
            <div class="col">
                <div class="row">
                    <p class="text-5xl text-center mt-10 text-sky-100 font-semibold">Autók Megjelenítése</p>
                    <div class=" w-2/5 border-b-4 mx-auto border-lime-500 mt-3 mb-5" ></div>
                    <div class=" w-2/3 border-b-4 mx-auto border-lime-500 mt-0 mb-6"></div>
                </div>
                <div class="row">
                    <BaseTable :cars="cars" />
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from "@layouts/BaseLayout.vue";
import BaseTable from "@layouts/BaseTable.vue";
import { http } from "@utils/http";

export default {
    components: {
        BaseLayout,
        BaseTable,
    },
    data() {
        return {
            cars: [],
        };
    },
    async mounted() {
        try {
            const response = await http.get("/cars");
            this.cars = response.data.data;
        } catch (error) {
            console.error("Hiba történt az API-hívás során:", error);
        }
    }
};
</script>