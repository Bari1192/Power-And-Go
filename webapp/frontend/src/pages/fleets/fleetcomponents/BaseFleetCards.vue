<template>
    <!-- Megjelenítjük a kártyát -->
    <div
        class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl transition-all duration-150 ease-in-out active:translate-y-[2px] active:border-b-2 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
        <img class="object-cover w-64 h-32 ml-3 rounded-md"
            :src="`http://backend.vm1.test/storage/carsImages/${id}.png`"
            :alt="manufacturer + ' ' + carmodel + ' képe'">
        <div class="flex flex-col justify-between p-4 leading-normal">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ manufacturer + ' ' + carmodel }}
            </h5>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                <slot />
            </p>
        </div>
    </div>
    <div class="flex justify-end ml-4">
        <button class="w-1/4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 my-2 mx-4 rounded"
            @click.stop="editFleet" :disabled="isEditing">
            Módosítás
        </button>
        <button class="w-1/4 bg-red-500 hover:bg-red-700 text-white font-bold p-1 my-2 rounded"
            @click.stop="deleteFleet" :disabled="isEditing">
            Törlés
        </button>
    </div>

    <!-- Szerkesztési űrlap -->
    <EditFleetCar :isEditing="isEditing" :carData="editableData" :carId="id" @save="saveChanges" @cancel="cancelEdit" />
</template>

<script>
import EditFleetCar from '@layouts/fleet/EditFleetCar.vue';

export default {
    name: 'BaseFleetCards',
    components: {
        EditFleetCar
    },
    props: {
        id: [String, Number],
        manufacturer: String,
        carmodel: String,
        motor_power: Number,
        top_speed: Number,
        driving_range: Number,
        tire_size: String,
    },
    data() {
        return {
            isEditing: false,
            editableData: {
                manufacturer: '',
                carmodel: '',
                motor_power: 0,
                top_speed: 0,
                driving_range: 0,
                tire_size: ''
            }
        };
    },
    methods: {
        editFleet() {
            this.editableData = {
                manufacturer: this.manufacturer,
                carmodel: this.carmodel,
                motor_power: this.motor_power,
                top_speed: this.top_speed,
                driving_range: this.driving_range,
                tire_size: this.tire_size,
            };
            this.isEditing = true;
            this.$emit('edit', this.id);
        },
        saveChanges(id, data) {
            this.$emit('update', id, data);
            this.isEditing = false;
        },
        cancelEdit() {
            this.isEditing = false;
        },
        deleteFleet() {
            this.$emit('delete', this.id);
        },
    },
    emits: ['update', 'delete', 'edit']
};
</script>

<style scoped>
button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}
</style>