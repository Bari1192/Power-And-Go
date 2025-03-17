<template>
    <BaseLayout>
        <SpringBanner />

        <div class="keret px-4">
            <div class="keret-kartya w-full bg-yellow-800 bg-opacity-60 rounded-2xl shadow-md shadow-yellow-500 py-5 mx-auto">
                <div class="w-8/12 mx-auto justify-center align-middle pb-6 items-center ">

                    <!-- Slider ITT -->
                    <Slider />

                </div>
            </div>
        </div>

        <PricesComponent />
    </BaseLayout>
</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue';
import PricesComponent from '@pages/indexpage/PricesComponent.vue';
import SpringBanner from '@pages/indexpage/SpringBanner.vue'
import Slider from '@layouts/sliders/Slider.vue';

export default {
    components: {
        BaseLayout,
        PricesComponent,
        SpringBanner,
        Slider
    },
};
</script>

<style>
.keret {
    background: linear-gradient(0deg,
            rgba(217, 249, 157, 0.75) 0%,
            rgba(226, 250, 180, 0.7) 30%,
            rgba(236, 252, 203, 0.6) 60%,
            rgba(255, 255, 255, 0.6) 100%);
}

</style>
