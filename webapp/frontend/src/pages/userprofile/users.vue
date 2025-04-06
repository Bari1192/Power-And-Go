<template>
    <BaseHeader />

    <div class="flex justify-start mx-auto h-full">
        <!-- "Navbar" baloldalt opciókkal  -->
        <div class="w-2/12 grid grid-rows-6 h-[800px] max-h-full">
            <div class="grid min-w-full h-full ">
                <div class="arrow-container min-w-[200px] min-h-[100px] lg:w-full h-full"
                     @click="toggleContent">
                    <button class="arrow-button w-full active h-full 3xl:text-nowrap text-lg lg:text-xl">
                        Előfoglalás rögzítése
                    </button>
                </div>
            </div>
            <div class="grid min-w-full min-h-full">
                <div class="arrow-container min-w-[200px] min-h-[100px] lg:w-full h-full"
                    > <button
                        class="arrow-button w-full h-full 3xl:text-nowrap text-lg lg:text-xl">
                        Autó kiválasztása
                    </button>
                </div>
            </div>
            <div class="grid min-w-full min-h-full">
                <div class="arrow-container min-w-[200px] min-h-[100px] lg:w-full h-full"
                    > <button
                        class="arrow-button w-full h-full 3xl:text-nowrap text-lg lg:text-xl">
                        Felhasználó kiválasztása
                    </button>
                </div>
            </div>
            <div class="grid min-w-full min-h-full">
                <div class="arrow-container min-w-[200px] min-h-[100px] lg:w-full h-full"
                    > <button
                        class="arrow-button w-full h-full 3xl:text-nowrap text-lg lg:text-xl">
                        Helyszín kiválasztása
                    </button>
                </div>
            </div>
            <div class="grid min-w-full min-h-full">
                <div class="arrow-container min-w-[200px] min-h-[100px] lg:w-full h-full"
                    > <button
                        class="arrow-button w-full h-full 3xl:text-nowrap text-lg lg:text-xl">
                        Időpont kiválasztása
                    </button>
                </div>
            </div>
            <div class="grid min-w-full min-h-full">
                <div class="arrow-container min-w-[200px] min-h-[100px] lg:w-full h-full"
                    > <button
                        class="arrow-button w-full h-full 3xl:text-nowrap text-lg lg:text-xl">
                        Foglalás véglegesítése
                    </button>
                </div>
            </div>
        </div>

        <!-- Ez lesz a "content rész" -->
        <div v-if="isContentVisible" class="w-3/5 px-4 lg:w-4/5 text-slate-700 mx-auto ml-32 lg:ml-20 lg:mr-4">
            <div class="my-6">
                <h1 class="text-2xl font-bold">Kérem válassza ki a modelt!</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3 gap-6">
                <div v-for="fleet in fleets" :key="fleet.id" class="w-full">
                    <UserCarPreorder :manufacturer="fleet.manufacturer" :id="fleet.id" :carmodel="fleet.carmodel"
                        :driving_range="fleet.driving_range" :motor_power="fleet.motor_power"
                        :avaliableCarCount="carStore.getCarGroupCountByMotorPower(fleet.motor_power)"
                        @carSelected="handleCarSelected"
                        />
                </div>
            </div>
        </div>
    </div>
    <BaseFooter />
</template>


<script setup>
import BaseHeader from '@components/layout/BaseHeader.vue';
import BaseFooter from '@components/layout/BaseFooter.vue'
import { onMounted, ref } from 'vue';
import { useUserStore } from '@stores/UserStore';
import { useFleetStore } from '@stores/FleetStore';
import { useCarStore } from '@stores/carStore';
import { storeToRefs } from 'pinia';
import BaseUserCard from '@layouts/BaseUserCard.vue';
import UserCarPreorder from '@layouts/users/UserCarPreorder.vue';

const fleetStore = useFleetStore();
const carStore = useCarStore();
const { fleets, cars } = storeToRefs(fleetStore, carStore);
// const userStore = useUserStore();
// const { users } = storeToRefs(userStore);
const isLoading = ref(false);

const isContentVisible = ref(false);
const toggleContent = () => {
    isContentVisible.value = !isContentVisible.value;
}
const selectedCarId = ref(null);

// Ez a függvény kapja meg a kiválasztott autó ID-jét
const handleCarSelected = (carId) => {
  selectedCarId.value = carId;
  console.log(`A kiválasztott autó ID-je: ${carId}`);
};

onMounted(async () => {
    isLoading.value = true;
    await fleetStore.getFleets();
    await carStore.getCars();
    isLoading.value = false;
});

</script>


<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Rasa:ital,wght@0,300..700;1,300..700&display=swap');

.arrow-button {
    position: relative;
    clip-path: polygon(0% 0%, 85% 0%, 100% 50%, 85% 100%, 0% 100%);
    width: 100%;
    height: 100%;
    font-weight: 500;
    text-align: center;
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