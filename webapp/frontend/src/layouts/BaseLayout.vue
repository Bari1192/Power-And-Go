<template>
    <BaseHeader />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <div class="custom-background-third bg-opacity-25">

        <div class="spring-banner">
            <div class="banner-container">
                <div class="banner-content">
                    <h1 class="banner-title">
                        <span class="flower-decoration">🌸</span>
                        Tavaszi szél, új autóval kél!
                        <span class="flower-decoration">🌸</span>
                    </h1>
                    <p class="banner-description">
                        <b>Csatlakozz</b> a Power and Go közösségéhez!
                    </p>
                    <p class="banner-description">
                        Váltsd valóra a tavaszi terveidet <b>kedvezményes bérlésekkel</b>!
                    </p>
                    <a href="" class="banner-cta">
                        <i class="fas fa-car-side mr-2"></i> Érdekel!
                    </a>
                </div>
            </div>
        </div>
        <div class="mx-2">
            <div class="w-full bg-teal-700 rounded-2xl shadow-lg shadow-green-800 bg-opacity-80 py-5 mx-auto">
                <div class="w-8/12 mx-auto justify-center align-middle pb-6 items-center ">

                    <!-- Slider ITT -->
                    <Slider />

                </div>
            </div>
        </div>

        <main class="container h-auto mx-auto ">
            <slot />

        </main>
        <hullamInverz />

    </div>
    <BaseFooter />
</template>

<script>
import BaseHeader from '@components/layout/BaseHeader.vue'
import BaseFooter from '@components/layout/BaseFooter.vue'
import hullamInverz from '@layouts/hullamInverz.vue';
import Slider from '@layouts/sliders/Slider.vue';

// Saját CSS behúzása
import '@assets/styles/SpringBannerStyle.css'

export default {
    components: {
        BaseHeader,
        BaseFooter,
        hullamInverz,
        Slider
    }
}
</script>

<style>
.custom-background-third {
    background:
        url('@img/Welcome/leaf1.png');
    background-position: right;
    background-repeat: round;
    overflow: hidden;
}
</style>