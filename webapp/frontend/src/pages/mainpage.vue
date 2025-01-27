<template>
    <section class="slider_section relative min-w-full -mt-56 -mb-56">
        <Swiper
            :space-between="50"
            :slides-per-view="1"
            navigation
            pagination
            loop
            :autoplay="{ delay: 3, disableOnInteraction: false }"
            class="min-w-full"
        >
            <!-- Slide 1 -->
            <SwiperSlide>
                <div class="mx-auto w-2/3 flex flex-col md:flex-row items-center justify-between py-12">
                    <div class="detail-box text-left text-primary1">
                        <h1 class="text-3xl font-bold uppercase mb-4">Kedvező és<br />Kiszámítható</h1>
                        <h2 class="text-2xl text-green-600 font-bold italic mb-4">VW e-up!</h2>
                        <p class="text-xl text-sky-50">
                            Környezetbarát, praktikus és energiatakarékos. Foglalj előre, és élvezd az elektromos
                            mobilitás előnyeit!
                        </p>
                        <div class="mt-6 flex space-x-4">
                            <a href="#"
                                class="bg-sky-500 hover:bg-sky-600 border-2 border-sky-800 hover:text-lime-300 text-white font-semibold px-4 py-2 rounded-xl hover:bg-primary1">
                                Lefoglalom
                            </a>
                        </div>
                    </div>
                    <div class="img-box">
                        <img src="http://backend.vm1.test/storage/carsImages/eupmodel.png" alt="VW e-up!"
                            class="w-full max-w-md mx-auto animate-up-down" />
                    </div>
                </div>
            </SwiperSlide>

            <!-- Slide 2 -->
            <SwiperSlide>
                <div class="mx-auto w-2/3 flex flex-col md:flex-row items-center justify-between py-12">
                    <div class="detail-box text-left text-primary1">
                        <h1 class="text-3xl font-bold uppercase mb-4">Csendes és<br />Dinamikus</h1>
                        <h2 class="text-2xl text-green-600 font-bold italic mb-4">KIA Niro EV</h2>
                        <p class="text-xl text-sky-50">
                            Fedezd fel a vezetés új élményét és élvezd az elektromos autózás kényelmét már ma!
                        </p>
                        <div class="mt-6 flex space-x-4">
                            <a href="#"
                                class="bg-sky-500 hover:bg-sky-600 border-2 border-sky-800 hover:text-lime-300 text-white font-semibold px-4 py-2 rounded-xl hover:bg-primary1">
                                Lefoglalom
                            </a>
                        </div>
                    </div>
                    <div class="img-box">
                        <img src="http://backend.vm1.test/storage/carsImages/kianiromodel.png" alt="KIA Niro EV"
                            class="w-full max-w-md mx-auto animate-up-down" />
                    </div>
                </div>
            </SwiperSlide>

            <!-- Slide 3 -->
            <SwiperSlide>
                <div class="mx-auto w-2/3 flex flex-col md:flex-row items-center justify-between py-12">
                    <div class="detail-box text-left text-primary1">
                        <h1 class="text-3xl font-bold uppercase mb-4">Kényelmes és<br />Stílusos</h1>
                        <h2 class="text-2xl text-green-600 font-bold italic mb-4">Škoda Citigo</h2>
                        <p class="text-xl text-sky-50">
                            Élvezd a szabadságot és a mobilitás örömét! Foglalj előre, és tapasztald meg a fenntartható
                            vezetés örömét!
                        </p>
                        <div class="mt-6 flex space-x-4">
                            <a href="#"
                                class="bg-sky-500 hover:bg-sky-600 border-2 border-sky-800 hover:text-lime-300 text-white font-semibold px-4 py-2 rounded-xl hover:bg-primary1">
                                Lefoglalom
                            </a>
                        </div>
                    </div>
                    <div class="img-box">
                        <img src="http://backend.vm1.test/storage/carsImages/citigomodel.png" alt="Škoda Citigo"
                            class="w-full max-w-md mx-auto animate-up-down" />
                    </div>
                </div>
            </SwiperSlide>
        </Swiper>
    </section>
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";

export default {
    components: {
        Swiper,
        SwiperSlide,
    },
};
</script>

<style scoped>
@keyframes upDown {
    0% {
        transform: translateY(-15px);
    }
    50% {
        transform: translateY(15px);
    }
    100% {
        transform: translateY(-15px);
    }
}

.animate-up-down {
    animation: upDown 3s ease-in-out infinite;
}
</style>
