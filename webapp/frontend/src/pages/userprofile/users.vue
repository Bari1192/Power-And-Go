<template>
    <BaseHeader />

    <div class="flex justify-center mx-auto h-full">
        <!-- "Navbar" baloldalt opciókkal  -->
        <div class="w-2/12 grid grid-rows-6 h-[800px] max-h-full">
            <div class="grid min-w-full h-full ">
                <div class="arrow-container" @click="showThisContent('first', this)">
                    <button class="arrow-button w-full h-full rounded-r-2xl border-b-2 ">
                        Első opció
                    </button>
                </div>
            </div>
            <div class="grid min-w-full min-h-full">
                <div class="arrow-container" @click="showThisContent('second', this)">
                    <button class="arrow-button w-full h-full">
                        Második opció
                    </button>
                </div>
            </div>
            <div class="grid min-w-full min-h-full">
                <div class="arrow-container" @click="showThisContent('third',this)">
                    <button class="arrow-button w-full h-full">
                        Harmadik opció
                    </button>
                </div>
            </div>
            <div class="grid min-w-full min-h-full">
                <div class="arrow-container" @click="showThisContent('fourth',this)">
                    <button class="arrow-button w-full h-full">
                        Negyedik opció
                    </button>
                </div>
            </div>
            <div class="grid min-w-full min-h-full">
                <div class="arrow-container" @click="showThisContent('fifth',this)">
                    <button class="arrow-button w-full h-full">
                        ötödik opció
                    </button>
                </div>
            </div>
            <div class="grid min-w-full min-h-full">
                <div class="arrow-container" @click="showThisContent('sixth',this)">
                    <button class="arrow-button  w-full h-full">
                        Hatodik opció
                    </button>
                </div>
            </div>
        </div>

        <!-- Ez lesz a "content rész" -->
        <div class="w-full text-center m-auto text-slate-700">Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Magni dolores at nulla ratione laboriosam sunt
            perferendis molestiae animi? Nesciunt laudantium similique beatae cumque eveniet eos reiciendis magni minima
            ipsa maiores!</div>

    </div>
    <BaseFooter />
</template>


<script setup>
import BaseHeader from '@components/layout/BaseHeader.vue';
import BaseFooter from '@components/layout/BaseFooter.vue'


</script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Rasa:ital,wght@0,300..700;1,300..700&display=swap');

.arrow-button {
    position: relative;
    clip-path: polygon(0% 0%, 85% 0%, 100% 50%, 85% 100%, 0% 100%);
    width: 100%;
    height: 100%;
    text-align: center;
    font-weight: 500;
    font-size: 22px;
    font-family: 'Nunito', 'Arial';
    transform-origin: left center;
    transition: all 0.5s ease;
    color: rgba(255, 255, 255, 0.9) !important;
    padding: 10px;
    background-size: 200% 200%;
    background-image: linear-gradient(145deg,
            rgba(139, 194, 36, 0.8) 0%,
            rgba(217, 138, 2, 0.6) 50%,
            rgba(139, 194, 36, 0.9) 50%,
            rgba(217, 138, 2, 0.8) 100%);
    background-position: 0 0;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    box-shadow:
        0 4px 15px rgba(0, 0, 0, 0.1),
        inset 0 0 0 1px rgba(255, 255, 255, 0.1);
    border-right: 12px solid #5ea402;
}

.arrow-button {
    position: relative;
    clip-path: polygon(0% 0%, 85% 0%, 100% 50%, 85% 100%, 0% 100%);
    width: 100%;
    height: 100%;
    padding: 1rem;
    transform-origin: left center;
    transition: all 0.3s ease;
}

.arrow-container:hover .arrow-button {
    transform: translateX(16px);
}

.arrow-button.active {
    background-color: rgba(13, 124, 2, .8);
    /* transform: translateX(32px); */
}
</style>