<template>
    <div class="flex flex-col items-center bg-lime-500/85 border border-gray-200 rounded-xl shadow-lg md:flex-row md:max-w-xl 
        transition-all duration-150 ease-in-out active:translate-y-[2px] 
        active:border-b-2  hover:bg-lime-500">
        <!-- Képek -->
        <img class="object-cover max-w-[250px] max-h-[180px] ml-3 rounded-md"
            :src="`http://backend.vm1.test/storage/carsImages/${id}.png`"
            :alt="manufacturer + ' ' + carmodel + ' képe'">
        <!-- Leírások -->
        <div class="flex flex-col justify-between p-4 leading-normal ">
            <h5 class="mb-2 text-2xl  tracking-tight text-white">
                {{ manufacturer + ' ' + carmodel }}
            </h5>
            <p class="mb-3 font-base font-thin text-lime-800/80 ">
                <slot />
            </p>
        </div>
    </div>
    <div class="flex justify-end ml-4">
        <button class="w-1/4 bg-yellow-400 hover:bg-yellow-500/90 text-white font-bold py-2 my-2 rounded-xl border-2 border-orange-500/50
        transition-all duration-200 ease-in-out" @click.stop="editFleet" :disabled="isEditing">
            Módosítás
        </button>
        <button class="w-1/4 bg-red-500/90 hover:bg-red-600/90 text-white font-bold py-2 my-2 ml-2 rounded-xl border-2 border-red-800/50
        transition-all duration-200 ease-in-out" @click.stop="deleteFleet" :disabled="isEditing">
            Törlés
        </button>
    </div>

    <!-- Szerkesztési űrlap -->
    <EditFleetCar :isEditing="isEditing" :carData="editableData" :carId="id" @save="saveChanges" @cancel="cancelEdit" />
</template>

<script setup>
import { ref } from 'vue';
import EditFleetCar from '@pages/fleets/fleetcomponents/EditFleetCar.vue';

const props = defineProps({
    id: {
        type: [String,Number],
        required: true
    },
    manufacturer: {
        type: [String,Number],
        required: true
    },
    carmodel: {
        type: [String,Number],
        required: true,
    },
    motor_power: {
        type: [String,Number],
        required: true
    },
    top_speed: {
        type: [String,Number],
        required: true
    },
    driving_range: {
        type: [String,Number],
        required: true
    },
    tire_size: {
        type: [String,Number],
        required: true
    }
});
// visszavárt adatok || Emitek definiálása:
const emit = defineEmits(['update', 'delete', 'edit']);

// Reaktívvá tett állapotok:
const isEditing = ref(false);
const editableData = ref({
    manufacturer: '',
    carmodel: '',
    motor_power: 0,
    top_speed: 0,
    driving_range: 0,
    tire_size: '',
});

// metódusok sorban:
const editFleet = () => {
    editableData.value = {
        manufacturer: props.manufacturer,
        carmodel: props.carmodel,
        motor_power: props.motor_power,
        top_speed: props.top_speed,
        driving_range: props.driving_range,
        tire_size: props.tire_size,
    };
    isEditing.value = true;
    emit('edit', props.id);
};

// funkció gombok (összes) || Módosítás, Törlés és Mentés 
const saveChanges = (id, data) => {
    emit('update', id, data);
    isEditing.value = false;
}
const cancelEdit = () => {
    isEditing.value = false;
}
const deleteFleet = () => {
    emit('delete', props.id);
}
</script>

<style scoped>
button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}
</style>