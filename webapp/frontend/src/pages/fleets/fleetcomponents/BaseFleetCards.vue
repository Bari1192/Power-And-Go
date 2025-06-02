<template>
  <div style="font-family: 'Nunito','Arial';"
    class="bg-gradient-to-b from-indigo-950 to-gray-900 border border-slate-700 rounded-xl shadow-xl overflow-hidden shadow-emerald-500/10 hover:shadow-teal-500/20 transition-all duration-300">
    <!-- Kártya tartalom -->
    <div class="relative">
      <!-- Kép -->
      <div class="grid grid-cols-3 justify-center items-center align-middle bg-slate-900">

        <div class="flex flex-col justify-between gap-4 px-8">
          <div>
            <p class="font-bold text-xl text-center gap-2 mb-2">{{ manufacturer }} - {{ carmodel }}</p>
            <div
              class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-indigo-300/60 to-transparent my-3 rounded-full">
            </div>
            <p class="font text-xl text-center">Adatváltoztatás és Jármű kivonás</p>
          </div>
          <button @click="editFleet" :disabled="isEditing"
            class="min-w-[60px] bg-slate-700 hover:bg-slate-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium py-2.5 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-sky-300">
              edit_square
            </span>
            Adatok módosítása
          </button>
          <button @click="deleteFleet" :disabled="isEditing"
            class="min-w-[60px] bg-red-600/20 hover:bg-red-600/30 border border-red-600/50 disabled:opacity-50 disabled:cursor-not-allowed text-white/90 font-medium py-2.5 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-gray-300">
              delete
            </span>
            Jármű végleges kivonása
          </button>
        </div>

        <div class="col-span-2 aspect-auto bg-slate-900">
          <img class="h-[300px] w-[350px] object-contain mx-auto"
            :src="`http://backend.vm1.test/storage/carsImages/${id}.webp`"
            :alt="manufacturer + ' ' + carmodel + ' képe'" @error="handleImageError" />
        </div>
      </div>

      <div
        class="bg-gradient-to-b from-indigo-950 to-gray-900 border border-slate-700 rounded-xl shadow-xl overflow-hidden hover:shadow-emerald-500/10 transition-all duration-300">
        <div class="py-4 mx-auto w-full max-w-7xl flex justify-center items-center divide-x-2 divide-slate-500">
          <div class="flex items-center text-slate-300 px-6">
            <span class="material-symbols-outlined text-emerald-500 mr-2">ev_station</span>
            <span>{{ motor_power }} kW</span>
          </div>
          <div class="flex items-center text-slate-300 px-6">
            <span class="material-symbols-outlined text-emerald-500 mr-2">speed</span>
            <span>{{ top_speed }} km/h</span>
          </div>
          <div class="flex items-center text-slate-300 px-6">
            <span class="material-symbols-outlined text-emerald-500 mr-2">road</span>
            <span>{{ driving_range }} km</span>
          </div>
          <div class="flex items-center text-slate-300 px-6">
            <span class="material-symbols-outlined text-emerald-500 mr-2">sports_soccer</span>
            <span>{{ tire_size }}</span>
          </div>
          <div class="flex items-center text-slate-300 px-6">
            <span class="material-symbols-outlined text-emerald-500 mr-2">warehouse</span>
            <span>{{ manufacturer }}</span>
          </div>
          <div class="flex items-center text-slate-300 px-6">
            <span class="material-symbols-outlined text-emerald-500 mr-2">electric_car</span>
            <span>{{ carmodel }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
    <!-- Szerkesztési űrlap -->
    <EditFleetCar :isEditing="isEditing" :carData="editableData" :carId="id" @save="saveChanges" @cancel="cancelEdit" />
</template>

<script setup>
import { ref } from 'vue';
import EditFleetCar from '@pages/fleets/fleetcomponents/EditFleetCar.vue';

const props = defineProps({
  id: { type: [String, Number], required: true },
  manufacturer: { type: String, required: true },
  carmodel: { type: String, required: true },
  motor_power: { type: Number, required: true },
  top_speed: { type: Number, required: true },
  driving_range: { type: Number, required: true },
  tire_size: { type: String, required: true },
});

const emit = defineEmits(['update', 'delete', 'edit']);
const isEditing = ref(false);
const editableData = ref({
  manufacturer: props.manufacturer,
  carmodel: props.carmodel,
  motor_power: props.motor_power,
  top_speed: props.top_speed,
  driving_range: props.driving_range,
  tire_size: props.tire_size,
});

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

const saveChanges = (id, data) => {
  emit('update', id, data);
  isEditing.value = false;
};

const cancelEdit = () => {
  isEditing.value = false;
};

const deleteFleet = () => {
  emit('delete', props.id);
};

const handleImageError = (e) => {
  e.target.src = 'https://via.placeholder.com/400x300/1e293b/10b981?text=Nincs+kép';
};
</script>