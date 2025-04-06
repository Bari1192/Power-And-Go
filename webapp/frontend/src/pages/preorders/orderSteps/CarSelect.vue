<template>
    <div class="hover:transition-transform hover:ease-in hover:scale-105 hover:duration-200">
        <div class="flex flex-col sm:flex-row bg-lime-500/80 border-2 border-gray-500/40 border-dotted rounded-xl shadow-xl 
      transition-all duration-200 ease-in-out hover:bg-emerald-300/70 active:translate-y-[2px] active:border-b-2
      min-h-[200px] sm:h-[170px] w-full overflow-hidden cursor-pointer">

            <!-- Kép - fix méretű konténer -->
            <div
                class="w-full sm:w-1/2  lg:h-[200px]  flex items-center justify-center overflow-hidden bg-white rounded-br-lg">
                <img class="object-cover w-auto h-36 p-8 lg:h-auto max-w-full"
                    :src="`http://backend.vm1.test/storage/carsImages/${id}.png`"
                    :alt="manufacturer + ' ' + carmodel + ' képe'">
            </div>

            <!-- Leírások - 2/3 szélesség nagy képernyőn -->
            <div class="w-full sm:w-1/2 p-2 flex flex-col justify-between  ">
                <div>
                    <h5 class="text-lg xl:text-xl tracking-tight text-white text-center font-medium">
                        {{ manufacturer + ' ' + carmodel }}
                    </h5>
                    <ul class="my-2 font-base text-sm text-nowrap lg:text-wrap text-lime-800/80">
                        <li class="mx-auto pb-2">
                            <i class="fa-solid fa-road xl:pl-3 xl:pr-1 text-lime-800/90"></i> Akár <b>{{ driving_range
                            }}</b> km
                            hatótáv.
                        </li>
                        <li class="mx-auto pb-2">
                            <i class="fa-solid fa-plug  xl:pl-3 xl:pr-1 text-lime-800/90"></i> <b>{{ motor_power }}</b>
                            kW-os
                            akkumulátor.
                        </li>
                        <li class="mx-auto">
                            <i class="fa-solid fa-car xl:pl-3 xl:pr-1 text-lime-800/90"></i>Elérhető <b>{{
                                avaliableCarCount }}</b>
                            db a flottában.
                        </li>
                        <slot />
                    </ul>
                </div>

                <!-- Gomb -->
                <div class="flex justify-center items-center mx-auto sm:justify-start">
                    <button @click="carTypeSelected"
                        class="bg-amber-100 mb-1 border-green-700 hover:border-green-800 hover:bg-white border-2 px-4 py-1 lg:py-2 text-teal-600 hover:text-green-600 tracking-wider font-medium rounded-lg transition-all duration-200 ease-in-out">
                        Kiválasztás
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    id: {
        type: [String, Number],
        required: true,
    },
    manufacturer: {
        type: [String, Number],
        required: true
    },
    carmodel: {
        type: [String, Number],
        required: true,
    },
    driving_range: {
        type: [String, Number],
        required: true,
    },
    motor_power: {
        type: [String, Number],
        required: true,
    },
    avaliableCarCount: {
        type: [String, Number],
        required: true,
    },
});
const emit = defineEmits(['carSelected']);
const carTypeSelected = () => {
    emit('carSelected', props.id);
};
</script>