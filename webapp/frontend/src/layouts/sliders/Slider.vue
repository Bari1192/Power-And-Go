<template>
    <div class="swiper-container bg-lime-200 bg-opacity-75 rounded-2xl overflow-hidden">
        <div class="background-img">
                <Swiper :modules="modules" :space-between="50" :speed="1500" :slides-per-view="1" :autoplay="{
                    delay: 5000,
                    disableOnInteraction: false
                }" :navigation="{
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                }" :loop="true" class="swipper-class min-w-full"
                    @swiper="onSwiper" @slideChange="onSlideChange">

                    <!-- Első -->
                    <SwiperSlide>
                        <OneSliderSlide newsBanner="A kényelem nem jár többé lemondással!"
                            titleAD="Új lendületre vágysz?" modelName="A KIA Niro Megadja!"
                            adDescription="Fedezd fel a vezetés új élményét és élvezd az elektromos autózás kényelmét már ma!"
                            linkDescription="Megnézem" linkToWhere="#"
                            imgSrc="http://backend.vm1.test/storage/carsImages/kianiromodel.png" imgAlt="KIA Niro EV" />
                    </SwiperSlide>

                    <!-- Második -->
                    <SwiperSlide>
                        <OneSliderSlide newsBanner="A megújuló városi cruiser!" titleAD="Fürge, még is praktikus?"
                            modelName="Igen, a VW e-up!"
                            adDescription="Környezetbarát, praktikus és energiatakarékos. Az elektromos autók úttörője!"
                            linkDescription="Megnézem" linkToWhere="#"
                            imgSrc="http://backend.vm1.test/storage/carsImages/eupmodel.png"
                            imgAlt="VW e-up autó model" />
                    </SwiperSlide>

                    <!-- Harmadik -->
                    <SwiperSlide>
                        <OneSliderSlide newsBanner="A szabadság szerelmeseinek!"
                            titleAD="A Megbízhatóság az első?" modelName="A Škoda Citigo megadja!" adDescription=" Élvezd a szabadságot és a mobilitás örömét! Foglalj előre, és tapasztald meg a
                                fenntartható
                                vezetés örömét!" linkDescription="Megnézem" linkToWhere="#"
                            imgSrc="http://backend.vm1.test/storage/carsImages/citigomodel.png" imgAlt="Škoda Citigo" />
                    </SwiperSlide>

                    <div class="swiper-button-next custom-next-button mr-8"></div>
                    <div class="swiper-button-prev custom-prev-button ml-8"></div>
                    <div class="border-b-8 border-lime-100 rounded-b-2xl pb-12"></div>
                </Swiper>
            </div>
        </div>
</template>

<script>
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import '@assets/styles/SliderStyle.css'
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';
import OneSliderSlide from '@layouts/sliders/OneSliderSlide.vue';

export default {
    components: {
        Swiper,
        SwiperSlide,
        OneSliderSlide,
    },
    setup() {
        const onSwiper = (swiper) => { };
        const onSlideChange = () => { };
        return {
            onSwiper,
            onSlideChange,
            modules: [Navigation, Pagination, Autoplay]
        };
    }
};
</script>