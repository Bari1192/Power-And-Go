<template>
    <!-- Kártya -->
    <div class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl
             hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
        <img class="object-cover w-64 h-32 ml-3 rounded-md"
            :src="`http://backend.vm1.test/storage/carsImages/${src}.png`" :alt="imgalt">
        <div class="flex flex-col justify-between p-4 leading-normal">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ title }}
            </h5>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                <slot />
            </p>
        </div>
    </div>
    <div class="flex justify-end ml-4">
        <button class="w-1/4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 my-2 mx-4 rounded"
            @click.stop="editFleet">
            Módosítás
        </button>
        <button class="w-1/4 bg-red-500 hover:bg-red-700 text-white font-bold p-1 my-2 rounded"
            @click.stop="deleteFleet">
            Törlés
        </button>
    </div>

    <!-- Szerkesztési Űrlap Komponens -->
    <EditFleetCar 
        :isEditing="isEditing"
        :carData="editableData"
        :carId="src"
        @save="saveChanges"
        @cancel="cancelEdit"
    />
</template>


<script>
import EditFleetCar from '@layouts/fleet/EditFleetCar.vue';

export default {
    components: {
        EditFleetCar
    },
    props: {
        src: [String, Number],
        imgalt: [String, Number],
        title: [String, Number],
        motor_power: [Number],
        top_speed: [Number],
        driving_range: [Number],
        abroncs: [String],
    },
    data() {
        return {
            isEditing: false,
            editableData: {
                title: '',
                motor_power: 0,
                top_speed: 0,
                driving_range: 0,
                abroncs: ''
            }
        };
    },
    methods: {
        editFleet() {
            this.editableData = {
                title: this.title,
                motor_power: this.motor_power,
                top_speed: this.top_speed,
                driving_range: this.driving_range,
                abroncs: this.abroncs,
            };
            this.isEditing = true;
        },
        saveChanges(id, data) {
            this.$emit('update', id, data);
            this.isEditing = false;
        },
        cancelEdit() {
            this.isEditing = false;
        },
        deleteFleet() {
            this.$emit('delete', this.src);
        },
    },
};
</script>