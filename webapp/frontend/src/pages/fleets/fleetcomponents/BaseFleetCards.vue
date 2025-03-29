<template>
    <div
        class="flex flex-col items-center bg-lime-500/85 border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl 
        transition-all duration-150 ease-in-out active:translate-y-[2px] 
        active:border-b-2  hover:bg-lime-500">
        <!-- Képek -->
        <img class="object-cover w-64 h-32 ml-3 rounded-md"
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
        <button class="w-1/4 bg-yellow-400 hover:bg-yellow-500/90 text-white font-bold py-2 my-2 rounded-xl border-2 border-orange-500/50"
            @click.stop="editFleet" :disabled="isEditing">
            Módosítás
        </button>
        <button class="w-1/4 bg-red-500 hover:bg-red-600/90 text-white font-bold py-2 my-2 ml-2 rounded-xl border-2 border-red-800/50"
            @click.stop="deleteFleet" :disabled="isEditing">
            Törlés
        </button>
    </div>

    <!-- Szerkesztési űrlap -->
    <EditFleetCar :isEditing="isEditing" :carData="editableData" :carId="id" @save="saveChanges" @cancel="cancelEdit" />
</template>

<script>
import EditFleetCar from '@pages/fleets/fleetcomponents/EditFleetCar.vue';

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